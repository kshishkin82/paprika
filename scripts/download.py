#!/usr/bin/env python3
from __future__ import annotations

import json
from concurrent.futures import ThreadPoolExecutor, as_completed
from pathlib import Path
from urllib import parse, request


MAX_WORKERS = 10
TIMEOUT_SECONDS = 60

SCRIPT_DIR = Path(__file__).resolve().parent
PROJECT_ROOT = SCRIPT_DIR.parent
INPUT_FILE = SCRIPT_DIR / "result.json"
OUTPUT_DIR = SCRIPT_DIR / "files"


def sanitize_filename(name: str) -> str:
    cleaned = name.replace("\\", "_").replace("/", "_").strip()
    return cleaned or "file"


def choose_filename(item: dict, index: int) -> str:
    filename = item.get("filename")
    if not isinstance(filename, str) or not filename.strip():
        url = str(item.get("url", ""))
        parsed = parse.urlparse(url)
        filename = Path(parse.unquote(parsed.path)).name
    filename = sanitize_filename(filename)
    if "." not in filename:
        filename = f"{filename}_{index}"
    return filename


def load_items() -> list[dict]:
    data = json.loads(INPUT_FILE.read_text(encoding="utf-8"))
    attachments = data.get("attachments", [])
    if not isinstance(attachments, list):
        raise RuntimeError("Field 'attachments' in scripts/result.json must be a list")
    result = []
    for item in attachments:
        if isinstance(item, dict) and isinstance(item.get("url"), str) and item["url"].strip():
            result.append(item)
    return result


def build_targets(items: list[dict]) -> list[tuple[str, Path]]:
    used = set()
    targets = []
    for idx, item in enumerate(items, start=1):
        url = item["url"].strip()
        base = choose_filename(item, idx)
        stem = Path(base).stem
        suffix = Path(base).suffix
        candidate = base
        n = 1
        while candidate in used:
            candidate = f"{stem}_{n}{suffix}"
            n += 1
        used.add(candidate)
        targets.append((url, OUTPUT_DIR / candidate))
    return targets


def download_one(url: str, out_path: Path) -> bool:
    try:
        req = request.Request(
            url,
            headers={
                "user-agent": (
                    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) "
                    "AppleWebKit/537.36 (KHTML, like Gecko) "
                    "Chrome/146.0.0.0 Safari/537.36"
                )
            },
        )
        with request.urlopen(req, timeout=TIMEOUT_SECONDS) as resp:
            data = resp.read()
        out_path.write_bytes(data)
        return True
    except Exception:
        return False


def main() -> None:
    if not INPUT_FILE.exists():
        raise FileNotFoundError(f"Input file not found: {INPUT_FILE}")

    OUTPUT_DIR.mkdir(parents=True, exist_ok=True)
    items = load_items()
    targets = build_targets(items)

    total = len(targets)
    if total == 0:
        print("Nothing to download: no valid URLs in scripts/result.json")
        return

    ok = 0
    failed = 0
    done = 0

    print(f"Start downloading {total} files with {MAX_WORKERS} workers")
    with ThreadPoolExecutor(max_workers=MAX_WORKERS) as pool:
        futures = [pool.submit(download_one, url, path) for url, path in targets]
        for future in as_completed(futures):
            success = future.result()
            done += 1
            if success:
                ok += 1
            else:
                failed += 1
            print(f"\r{done}/{total}", end="", flush=True)

    print()
    print(f"Done. Success: {ok}, Failed: {failed}, Total: {total}")


if __name__ == "__main__":
    main()
