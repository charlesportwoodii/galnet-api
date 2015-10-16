# Galnet News Network API (GNNA)

_Your galaxy, in JSON_

[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/charlesportwoodii/galnet-api.svg?style=flat-square)](https://scrutinizer-ci.com/g/charlesportwoodii/galnet-api/)
[![Downloads](https://img.shields.io/packagist/dt/charlesportwoodii/galnet-api.svg?style=flat-square)](https://packagist.org/packages/charlesportwoodii/galnet-api)
[![Gittip](https://img.shields.io/gittip/charlesportwoodii.svg?style=flat-square "Gittip")](https://www.gittip.com/charlesportwoodii/)
[![License](https://img.shields.io/badge/license-MIT-orange.svg?style=flat-square "License")](https://github.com/charlesportwoodii/galnet-api/blob/master/LICENSE.md)
[![Yii](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat-square)](http://www.yiiframework.com/)

## What is GNNA

GNNA is an API designed to scrape Galnet for content, and present it in a way that it can be _easily_ consumed by third party clients such as native applications or third party websites _without_ needing to scrape Galnet directly.

For licensing information check the [LICENSE.md](LICENSE.md) file.

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

## Developing

1. Clone the repository and install the necessary composer dependencies:

```
git clone git@github.com:charlesportwoodii/galnet-api
cd galnet-api
composer install --prefer-dist -ov
```

2. Create ```config/db.php```. A example SQLite database connection is shown as follows. If you want to use a different database connection string, reference the [yii\db\Connection](http://www.yiiframework.com/doc-2.0/yii-db-connection.html) class.

```
<?php return [
	'dsn' => 'sqlite:/' . __DIR__ . '/../runtime/db.sqlite',
    'class' => 'yii\db\Connection',
	'charset' => 'utf8'
];
```

3. Initialize the database

```
./yii migrate/up --interactive=0
```

4. Import data from Galnet

```
./yii import
```

### Commands

By default, the ```import``` command will just import data for _today_. If you want to import data from a specific range supply the Galnet dates. For example, the following command will import everything between October 16th 2015 and October 21st 2015.

```
./yii import "16-OCT-3301" "21-OCT-3301"
```

Alternativly, you can import everything by passing ```start``` as the first argument.

```
./yii import start
```

### Contributing

There are several ways you can contribute to the development of GNNA:

- Submit a PR with a new feature
- Create a detailed issue
- Donate money to support hosting
