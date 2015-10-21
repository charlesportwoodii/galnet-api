# Commodities

Commodity data is supplied by [EDDB's Data Archives](http://eddb.io/api), and is updated nighly. For convenience, GNA stores commodity and commodity category information with the same ID's as EDDB.

Commodity information can be searched by both name and category name. Moreover, commodities are provided with pagination support by supplying the ```page``` GET parameter.

## Examples

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
			},
			"stations": 5
		},
			{
			"id": 99,
			"name": "Trinkets Of Hidden Fortune",
			"average_price": null,
			"category": {
				"id": 2,
				"name": "Consumer Items"
			},
			"stations": 421
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
			},
			"stations": 5
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
			},
			"stations": 5
		},
		{
			"id": 98,
			"name": "Sap 8 Core Container",
			"average_price": 60228,
			"category": {
				"id": 16,
				"name": "Salvage"
			},
			"stations": 5
		},
		{
			"id": 96,
			"name": "Antiquities",
			"average_price": 120011,
			"category": {
				"id": 16,
				"name": "Salvage"
			},
			"stations": 5
		},
		{
			"id": 95,
			"name": "Ai Relics",
			"average_price": 142067,
			"category": {
				"id": 16,
				"name": "Salvage"
			},
			"stations": 5
		}
	]
}
```

Additionally, if you query a commodity directly by it's ```ID```, it will provide a full list of stations and systems that commodity can be found in.

```
GET /commodity/1
```

```
{
	"data": [{
		"id": 1,
		"name": "Explosives",
		"average_price": 271,
		"category": {
			"id": 1,
			"name": "Chemicals"
		},
		"stations": [
			{
				"station_id": 946,
				"system_id": 9157
			}
			]
	}]
}
```