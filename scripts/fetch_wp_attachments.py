#!/usr/bin/env python3
import json
from urllib import parse, request


URL = "https://paprika-studio.ru/wp-admin/admin-ajax.php"
OUTPUT_FILE = "result.json"
PER_PAGE = 80

HEADERS = {
    "accept": "*/*",
    "accept-language": "en-US,en;q=0.9,ru;q=0.8",
    "content-type": "application/x-www-form-urlencoded; charset=UTF-8",
    "origin": "https://paprika-studio.ru",
    "priority": "u=1, i",
    "referer": "https://paprika-studio.ru/wp-admin/upload.php",
    "sec-ch-ua": '"Chromium";v="146", "Not-A.Brand";v="24", "Google Chrome";v="146"',
    "sec-ch-ua-mobile": "?0",
    "sec-ch-ua-platform": '"macOS"',
    "sec-fetch-dest": "empty",
    "sec-fetch-mode": "cors",
    "sec-fetch-site": "same-origin",
    "user-agent": (
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) "
        "AppleWebKit/537.36 (KHTML, like Gecko) "
        "Chrome/146.0.0.0 Safari/537.36"
    ),
    "x-requested-with": "XMLHttpRequest",
    "cookie": (
        "wordpress_sec_25e35bb86cefceba11d3aad1cf35af4a="
        "manager_paprika2%7C1774888818%7C8H5rYNM5ol1U4bZ8Di3nL5ytN99g4d2xBfUhVrag8SL%7C"
        "b6270b0aa7358b6d79471205baa9cbc53f078297b8fe50313286dced4ed9c46f; "
        "wordpress_test_cookie=WP%20Cookie%20check; "
        "PHPSESSID=f5a59c71ffcd3738bb3b89db13be2a58; "
        "wordpress_logged_in_25e35bb86cefceba11d3aad1cf35af4a="
        "manager_paprika2%7C1774888818%7C8H5rYNM5ol1U4bZ8Di3nL5ytN99g4d2xBfUhVrag8SL%7C"
        "5b4d783757fd12892d3a68b6ca0227ab3ddc0ad06c655ee823cfd26543154e93; "
        "wp-settings-6=libraryContent%3Dupload%26editor%3Dtinymce; "
        "wp-settings-time-6=1773679219"
    ),
}

FORM_DATA = {
    "action": "query-attachments",
    "post_id": "0",
    "query[orderby]": "date",
    "query[order]": "DESC",
    "query[posts_per_page]": str(PER_PAGE),
}


def fetch_page(page: int) -> list[dict]:
    form_data = dict(FORM_DATA)
    form_data["query[paged]"] = str(page)
    data = parse.urlencode(form_data).encode("utf-8")
    req = request.Request(URL, data=data, headers=HEADERS, method="POST")

    with request.urlopen(req) as resp:
        raw = resp.read().decode("utf-8")

    parsed = json.loads(raw)
    if not parsed.get("success"):
        raise RuntimeError(f"WP returned error on page {page}: {parsed}")

    data_field = parsed.get("data", [])
    if not isinstance(data_field, list):
        raise RuntimeError(f"Unexpected response format on page {page}")
    return data_field


def main() -> None:
    attachments_result = []
    seen_ids = set()
    page = 1

    while True:
        attachments = fetch_page(page)
        if not attachments:
            break

        for item in attachments:
            item_id = item.get("id")
            if item_id in seen_ids:
                continue
            seen_ids.add(item_id)

            attachments_result.append(
                {
                    "url": item.get("url"),
                    "link": item.get("link"),
                    "title": item.get("title"),
                    "filename": item.get("filename"),
                }
            )

        print(f"Page {page}: {len(attachments)} attachments")
        page += 1

    result = {
        "total_attachments": len(attachments_result),
        "attachments": attachments_result,
    }

    with open(OUTPUT_FILE, "w", encoding="utf-8") as f:
        json.dump(result, f, ensure_ascii=False, indent=2)

    print(f"Saved {len(attachments_result)} attachments to {OUTPUT_FILE}")


if __name__ == "__main__":
    main()
