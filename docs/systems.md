# Systems

System information can be queried for by searching against the ```/systems``` endpoint. This endpoint supports pagination via the ```page``` parameter. Systems by default are sorted in alphanumeric order by their name, but this can be changed by specifying the ```sort``` paramtered with the parameter name and the order ```asc|desc```.

```
GET /systems?sort=id&order=asc
GET /systems?sort=name&orderdesc
```

This endpoint also supports direct searching against the following properties

```
government
allegiance
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

## Examples

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