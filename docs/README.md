# GNA Documentation

This folder contains documentation for how to use GNA

## General API Information

GNA provides data in a standard JSON API.

### Pagination details

Endpoints that support pagination details are provided as response headers in the following format:

```
x-pagination-current-page: x
x-pagination-per-page: 20
x-pagination-total-entries: x
x-pagination-total-pages: x
```
