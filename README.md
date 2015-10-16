# Galnet News Network API (GNNA)

_Your galaxy, in JSON_

## What is GNNA

GNNA is an API designed to scrape Galnet for content, and present it in a way that it can be _easily_ consumed by third party clients such as native applications or third party websites _without_ needing to scrape Galnet directly.

### API Endpoints

All Galnet news entries can be retrieved via the ```/news``` endpoint, which supports pagination via the ```page``` GET parameter. Entries can also be retrieved by Galnet date by specifying the ```date``` GET parameter in ```DD-MMM-YYYY``` format.

Pagination details are provided as response headers in the following format:

```
x-pagination-current-page: x
x-pagination-per-page: 20
x-pagination-total-entries: x
x-pagination-total-pages: x
```

### RSS Feed

GNNA has a built in RSS feed endpoint that supports pagination via the ```page``` GET parameter, and is available at ```/news.rss```
