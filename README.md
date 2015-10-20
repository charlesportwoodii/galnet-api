# Galnet News API (GNA)

_Your galaxy, in JSON_

[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/charlesportwoodii/galnet-api.svg?style=flat-square)](https://scrutinizer-ci.com/g/charlesportwoodii/galnet-api/)
[![Downloads](https://img.shields.io/packagist/dt/charlesportwoodii/galnet-api.svg?style=flat-square)](https://packagist.org/packages/charlesportwoodii/galnet-api)
[![Gittip](https://img.shields.io/gittip/charlesportwoodii.svg?style=flat-square "Gittip")](https://www.gittip.com/charlesportwoodii/)
[![License](https://img.shields.io/badge/license-MIT-orange.svg?style=flat-square "License")](https://github.com/charlesportwoodii/galnet-api/blob/master/LICENSE.md)
[![Yii](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat-square)](http://www.yiiframework.com/)

This code has not been created in association with Frontier Developments and is unsupported by them, and may break at any time. Use at your own risk.

For licensing information check the [LICENSE.md](LICENSE.md) file.

----------------------

## What is GNA

GNA is your comprehensive API endpoint for anything Elite Dangerous.

Originally intended to supply Galnet News data in a simple JSON API. GNA has been enhanced to pull information from multiple resources to provide a detailed JSON API for multiple resources including:

- Galnet News
- Commodities
- Systems
- Stations
- PowerPlay (rankings, controlled systems, etc...)
- Community Goals
- Ships
- Ship components * modules

## How to use GNA?

Want to use GNA without setting up your own server? You can fetch data from [https://www.galnet.news](https://www.galnet.news).

> Note: Current data and usage is available for free, but hosting is not :(. If you want to donate to keep GNA up and free please contact me directly. Additionally, if you plan on doing massive data analysis, consider running GNA on your own servers.

----------------------

## What information can I get with GNA?

GNA sources data from multiple sources (EDDB, INARA, Galnet Community News). Listed below is all the information you can currently retrieve through GNA.

### Galnet News RSS Feed

GNA has a built in RSS feed endpoint that supports pagination via the ```page``` GET parameter, and is available at ```/rss```.

### API Endpoints

Pagination details are provided as response headers in the following format:

```
x-pagination-current-page: x
x-pagination-per-page: 20
x-pagination-total-entries: x
x-pagination-total-pages: x
```

#### Galnet News

All Galnet news entries can be retrieved via the ```/``` endpoint, which supports pagination via the ```page``` GET parameter. Entries can also be retrieved by Galnet date by specifying the ```date``` GET parameter in ```DD-MMM-YYYY``` format.

##### Examples

```
GET /
/GET /?page=5
```

```
{
	"data": [
		{
			"uid": "5624c54d9657ba260699dc70",
			"tite": "Pilots Call for Starship Lighting Enquiry",
			"content": "More than 220,000 pilots have signed a petition calling for an inquiry into the lack of anti-collision lights on starships and emergency lighting on system-authority ships. Jenson Zanetti, chairman of the Pilots Federation Safety Board, stated:\n\"At some point in their careers, most pilots will be struck by an authority or private vessel they did not see, which can be disastrous for both parties. If our proposals are implemented, we predict a massive decrease in the number of fatal collisions in and around space stations.\"\nThe petition calls for all system-authority vessels to be fitted with lighting equipment to help pilots identify these important craft, and for anti-collision lights to be fitted to all starships. The petition concludes by stating that the lights only need to be used within a station's no-fire zone, and when the ship's main lights are active.\nCommander Devenish, Alliance News Network",
			"published": 1445230800,
			"galnet_publication_time": 42027487200,
			"url": "https://community.elitedangerous.com/galnet/uid/5624c54d9657ba260699dc70"
		},
		{
			"uid": "5624b01c9657ba260699dc6f",
			"tite": "GalNet Weekly Economic Report",
			"content": "In this weekly digest, the latest GalNet data is compiled to show the economic state of a short list of minor factions and the star systems they inhabit.\nHere are 10 of the 11,041 minor factions currently enjoying an economic boom:\nPeople's Meldrith Values Party\nMovement for LHS 2429 Democrats\nAllied LHS 380 Law Party\nIsita Confederation\nTefenhua Alliance Union\nBharu Silver Public Partners\nGakiutl Blue Mafia\nCarthage Silver Drug Empire\nMaitis Energy Company\nTheta Indi Advanced Incorporated\nWhen in boom, the wealth of a system is increased for the duration and all trade missions have double the effect on influence. Boom can also positively increase a minor faction's influence. Boom can be entered by consistent trade profits and completed trade contracts. Booms tend to last until they naturally expire or until some other indicator takes precedence, such as famine.\nData is correct at time of publishing.",
			"published": 1445230800,
			"galnet_publication_time": 42027487200,
			"url": "https://community.elitedangerous.com/galnet/uid/5624b01c9657ba260699dc6f"
		},
		{...},
	]
}
```

#### Commodities

Commodity data is supplied by [EDDB's Data Archives](http://eddb.io/api), and is updated nighly. For convenience, GNA stores commodity and commodity category information with the same ID's as EDDB.

Commodity information can be searched by both name and category name. Moreover, commodities are provided with pagination support by supplying the ```page``` GET parameter.

##### Examples

```
GET /commodities
GET /commodities?page=3
```

```
{
	"data": [
		{
			"id": 100,
			"name": "Trade Data",
			"average_price": null,
			"category": {
				"id": 16,
				"name": "Salvage"
			}
		},
			{
			"id": 99,
			"name": "Trinkets Of Hidden Fortune",
			"average_price": null,
			"category": {
				"id": 2,
				"name": "Consumer Items"
			}
		},
		{...}
	]
}
```

> Note: Searching by name or category is somewhat case sensitive.

```
GET /commodities?name=Trade Data
```

```
{
	"data": [
		{
			"id": 100,
			"name": "Trade Data",
			"average_price": null,
			"category": {
				"id": 16,
				"name": "Salvage"
			}
		}
	]
}
```

```
GET /commodities?category=Salvage
```

```
{
	"data": [
		{
			"id": 100,
			"name": "Trade Data",
			"average_price": null,
			"category": {
				"id": 16,
				"name": "Salvage"
			}
		},
		{
			"id": 98,
			"name": "Sap 8 Core Container",
			"average_price": 60228,
			"category": {
				"id": 16,
				"name": "Salvage"
			}
		},
		{
			"id": 96,
			"name": "Antiquities",
			"average_price": 120011,
			"category": {
				"id": 16,
				"name": "Salvage"
			}
		},
		{
			"id": 95,
			"name": "Ai Relics",
			"average_price": 142067,
			"category": {
				"id": 16,
				"name": "Salvage"
			}
		}
	]
}
```

#### Systems

System information can be queried for by searching against the ```/systems``` endpoint. This endpoint supports pagination via the ```page``` parameter. Results can be sorted by specifying the ```sort``` parameter with the property name you wish to specify against. The sort order can be adjusted by specifying ```asc``` or ```desc``` as part of the sort GET parameter.

By default systems will be sorted by name.

```
GET /systems?sort=id asc
GET /systems?sort=name desc
```

This endpoint also supports direct searching against the following properties

```
state
security
needs_permit // use 0 or 1 for boolean properties
```

```
GET /systems?id=105
GET /systems?needs_permit=1&state=Boom
```

Additionally, the following properties support searching.

```
name
faction
government
allegiance
primary_economy
```

```
GET /systems?name=10
GET /systems?name=10 Tauri
GET /systems?name=CD-51&allegiance=Empire
```

Finally, you can also sort by population by specifying the population directly, by specifying a ```populationMap``` parameter, which will filter systems with a population using one of the valid query parmeters```>=, >, =, <, <=```.

```
GET /systems?population=100000&populationMap=>=
```

Feel free to mix and match multiple query parameters for complex searches. Data from this endpoint will list information for that system, and all stations currently in the system.

```
{
	"data": [
		{
			"id": 3036,
			"name": "CD-51 102",
			"x": 40.71875,
			"y": -124.15625,
			"z": 36.78125,
			"faction": "CD-51 102 Company",
			"population": 10672279,
			"government": "Corporate",
			"allegiance": "Empire",
			"state": null,
			"security": "High",
			"primary_economy": "High Tech",
			"needs_permit": false,
			"created_at": null,
			"updated_at": null,
			"stations": [
				{
					"id": 443,
					"name": "McDermott Enterprise",
					"system_id": 3036,
					"max_landing_pad_size": "L",
					"distance_to_star": 2602,
					"faction": "CD-51 102 Company",
					"government": "Dictatorship",
					"allegiance": "Empire",
					"state": "None",
					"type": "Coriolis Starport",
					"has_blackmarket": false,
					"has_commodities": true,
					"has_refuel": true,
					"has_repair": true,
					"has_rearm": true,
					"has_outfitting": true,
					"has_shipyard": true,
					"created_at": 1445294619,
					"updated_at": 1445294619
				}
			]
		},
		{...}
	]
}
```

To see information for a specific system, request that system directly using it's ```id```

```
GET /systems/100
```

> Note: the systems endpoint does not show all information for a station. To view more information about a station, request that station directly
----------------------

## Developing

1. Clone the repository and install the necessary composer dependencies:
```
git clone git@github.com:charlesportwoodii/galnet-api
cd galnet-api
composer install --prefer-dist -ov
```

2. Create ```config/db.php```. A example PostgreSQL database connection is shown as follows. If you want to use a different database connection string, reference the [yii\db\Connection](http://www.yiiframework.com/doc-2.0/yii-db-connection.html) class.
```
<?php return [
	'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=127.0.0.1;port=5432;dbname=gna',
    'username' => 'gna',
    'password' => '<password>',
    'charset' => 'utf8',
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

5. Import data from other sources
```
# View available commands
./yii help import
./yii import/<command>
```

### Importing Commands

A full list of available commands can be found by running:

```
# View available commands
./yii help import
```

#### Import Galnet News
By default, the ```import``` command will just import data for _today_. If you want to import data from a specific range supply the Galnet dates. For example, the following command will import everything between October 16th 2015 and October 21st 2015.

```
./yii import "16-OCT-3301" "21-OCT-3301"
```

Alternativly, you can import everything by passing ```start``` as the first argument.

```
./yii import start
```

#### Import EDDB Commodities

Commodity information is fetched from EDDB's data archives. As this data only updates once a day, you should only fetch it once a day.

```
./yii import commodities
```


#### Importing EDDB Systems & Stations

System and station information can be imported by running the following command. Note that this process could take a long time depending upon your system

```
./yii import/systems
./yii import/stations
```
### Contributing

There are several ways you can contribute to the development of GNNA:

- Submit a PR with a new feature
- Create a detailed issue
- Donate money to support hosting
