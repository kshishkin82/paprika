#!/usr/bin/env python3
from __future__ import annotations

import argparse
from concurrent.futures import ThreadPoolExecutor, as_completed
from html.parser import HTMLParser
from pathlib import Path
from urllib import parse, request


MAX_WORKERS = 10
TIMEOUT_SECONDS = 60
USER_AGENT = (
    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) "
    "AppleWebKit/537.36 (KHTML, like Gecko) "
    "Chrome/146.0.0.0 Safari/537.36"
)
SCRIPT_DIR = Path(__file__).resolve().parent


class CoverHrefParser(HTMLParser):
    def __init__(self) -> None:
        super().__init__()
        self.hrefs: list[str] = []
        self.cover_src: str | None = None

    def handle_starttag(self, tag: str, attrs: list[tuple[str, str | None]]) -> None:
        attrs_dict = dict(attrs)
        class_attr = attrs_dict.get("class", "")

        if tag == "a":
            href = attrs_dict.get("href")
            if not href or not class_attr:
                return
            classes = class_attr.split()
            if "uk-position-cover" in classes:
                self.hrefs.append(href.strip())
            return

        if tag == "img" and self.cover_src is None:
            classes = class_attr.split()
            src = attrs_dict.get("src") or attrs_dict.get("data-src")
            if src and "wp-post-image" in classes:
                self.cover_src = src.strip()


def sanitize_filename(name: str) -> str:
    cleaned = name.replace("\\", "_").replace("/", "_").strip()
    return cleaned or "file"


def filename_from_url(url: str, index: int) -> str:
    parsed = parse.urlparse(url)
    name = Path(parse.unquote(parsed.path)).name
    if not name:
        name = f"file_{index}"
    name = sanitize_filename(name)
    if "." not in name:
        name = f"{name}.bin"
    return name


def unique_target(output_dir: Path, filename: str, used: set[str]) -> Path:
    stem = Path(filename).stem
    suffix = Path(filename).suffix
    candidate = filename
    n = 1
    while candidate in used:
        candidate = f"{stem}_{n}{suffix}"
        n += 1
    used.add(candidate)
    return output_dir / candidate


def folder_name_from_page_url(page_url: str) -> str:
    parsed = parse.urlparse(page_url)
    path_parts = [part for part in parse.unquote(parsed.path).split("/") if part]
    if path_parts:
        name = sanitize_filename(path_parts[-1])
        if name:
            return name
    host = sanitize_filename(parsed.netloc.replace(":", "_"))
    return host or "downloaded_files"


def fetch_html(page_url: str) -> str:
    req = request.Request(page_url, headers={"user-agent": USER_AGENT})
    with request.urlopen(req, timeout=TIMEOUT_SECONDS) as resp:
        charset = resp.headers.get_content_charset() or "utf-8"
        return resp.read().decode(charset, errors="replace")


def collect_download_links(page_url: str, html: str) -> list[str]:
    parser = CoverHrefParser()
    parser.feed(html)

    normalized: list[str] = []
    seen: set[str] = set()
    for href in parser.hrefs:
        abs_url = parse.urljoin(page_url, href)
        if abs_url not in seen:
            seen.add(abs_url)
            normalized.append(abs_url)
    return normalized


def collect_cover_image_url(page_url: str, html: str) -> str | None:
    parser = CoverHrefParser()
    parser.feed(html)
    if not parser.cover_src:
        return None
    return parse.urljoin(page_url, parser.cover_src)


def cover_filename(page_url: str, cover_url: str) -> str:
    page_tail = folder_name_from_page_url(page_url)
    ext = Path(parse.unquote(parse.urlparse(cover_url).path)).suffix
    if not ext:
        ext = ".bin"
    return f"cover_{page_tail}{ext}"


def download_one(url: str, out_path: Path) -> bool:
    try:
        req = request.Request(url, headers={"user-agent": USER_AGENT})
        with request.urlopen(req, timeout=TIMEOUT_SECONDS) as resp:
            out_path.write_bytes(resp.read())
        return True
    except Exception:
        return False


def download_by_selector(page_url: str, base_dir: str | Path, workers: int) -> None:
    folder_name = folder_name_from_page_url(page_url)
    output_dir = Path(base_dir).expanduser().resolve() / folder_name
    output_dir.mkdir(parents=True, exist_ok=True)

    html = fetch_html(page_url)
    links = collect_download_links(page_url, html)
    cover_url = collect_cover_image_url(page_url, html)

    total = len(links) + (1 if cover_url else 0)
    if total == 0:
        print("No links found for selector .uk-position-cover[href] and no img[class=wp-post-image]")
        return

    used_names: set[str] = set()
    targets: list[tuple[str, Path]] = []
    for idx, link in enumerate(links, start=1):
        filename = filename_from_url(link, idx)
        targets.append((link, unique_target(output_dir, filename, used_names)))
    if cover_url:
        filename = cover_filename(page_url, cover_url)
        targets.append((cover_url, unique_target(output_dir, filename, used_names)))

    print(f"Found {total} files. Start download with {workers} workers.")

    done = 0
    ok = 0
    failed = 0
    with ThreadPoolExecutor(max_workers=max(workers, 1)) as pool:
        futures = [pool.submit(download_one, url, out_path) for url, out_path in targets]
        for future in as_completed(futures):
            done += 1
            if future.result():
                ok += 1
            else:
                failed += 1
            print(f"\r{done}/{total}", end="", flush=True)

    print()
    print(f"Done. Success: {ok}, Failed: {failed}, Total: {total}")
    print(f"Saved to: {output_dir}")


def main() -> None:
    parser = argparse.ArgumentParser(
        description="Download all files matched by selector .uk-position-cover[href] from a page."
    )
    parser.add_argument("url", help="Page URL to parse")
    parser.add_argument(
        "--base-dir",
        default=str(SCRIPT_DIR),
        help="Base directory where URL-based folder will be created (default: scripts)",
    )
    parser.add_argument(
        "-w",
        "--workers",
        type=int,
        default=MAX_WORKERS,
        help=f"Number of parallel downloads (default: {MAX_WORKERS})",
    )
    args = parser.parse_args()
    download_by_selector(args.url, args.base_dir, args.workers)


if __name__ == "__main__":
    main()
