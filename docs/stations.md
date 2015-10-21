# Stations

Information about a stations can be retrieved via the ```/stations``` endpoint, and information for a particular station can be found at ```/stations/<id:\d+>```. Stations by default are sorted in alphanumeric order, but this can be changed by specifying the ```sort``` paramtered with the parameter name and the order ```asc|desc```.

```
GET /stations?sort=name&order=desc
GET /stations/937?sort=name&order=asc
```

Output for these endpoints will be displayed in the following format.

```
{
    "data": [
        {
            "id": 937,
            "name": "Aachen Town",
            "max_landing_pad_size": "L",
            "distance_to_star": 4746,
            "faction": "Alioth Independents",
            "government": "Democracy",
            "allegiance": "Alliance",
            "state": "None",
            "type": "Coriolis Starport",
            "has_blackmarket": true,
            "has_commodities": true,
            "has_refuel": true,
            "has_repair": true,
            "has_rearm": true,
            "has_outfitting": true,
            "has_shipyard": true,
            "created_at": 1445363901,
            "updated_at": 1445363901,
            "system": {
                "id": 718,
                "name": "Alioth",
                "x": -33.65625,
                "y": 72.46875,
                "z": -20.65625,
                "faction": "Alioth Independents",
                "population": 10000000000,
                "government": "Democracy",
                "allegiance": "Alliance",
                "state": "Expansion",
                "security": "High",
                "primary_economy": "Service",
                "needs_permit": true,
                "created_at": null,
                "updated_at": null
            },
            "economies": [
                "Service"
            ],
            "commodities": {
                "listings": [
                    {
                        "commodity_id": 83,
                        "name": "Painite",
                        "supply": 0,
                        "demand": 63640,
                        "buy_price": 0,
                        "sell_price": 36038
                    }
                ],
                "imports": [
                    {
                        "commodity_id": 46,
                        "name": "Platinum"
                    }
                ],
                "exports": [
                    {
                        "commodity_id": 75,
                        "name": "Biowaste"
                    }
                ],
                "prohibitied": [
                    {
                        "commodity_id": 82,
                        "name": "Toxic Waste"
                    }
                ]
            },
            "neighboring_stations": [
                {
                    "id": 487,
                    "name": "Irkutsk",
                    "distance_to_star": 7783
                }
            ]
        },
        {...}
    ]
}
```

Like systems, stations can be searched against. The following properties will search against a direct match

```
id
has_shipyard
has_outfitting
has_rearm
has_repair
has_refuel
has_commodities
type
state
max_landing_pad_size
government
allegiance
```

```
GET /stations?allegiance=Empire
GET /stations?allergiance=Federation&max_landing_pad_size=M
```

Additionally, the following properties can be searched against as partials

```
name
faction
```

```
GET /stations?name=Orbital
GET /stations?name=Kaku Plant
GET stations?faction=Kou Hua
```

Finally, you can also sort by star distance by specifying the distance directly, by specifying a ```starDistanceMap``` parameter, which will filter stations by their distance form their primary star using the following query parameters```>=, >, =, <, <=```.

```
GET /stations?distance_to_star=1233592&starDistanceMap=>=
```

> Kaku Plant...