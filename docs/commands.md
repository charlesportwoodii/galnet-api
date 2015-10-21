# Developing

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

# Importing Commands

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

### Import EDDB Commodities

Commodity information is fetched from EDDB's data archives. As this data only updates once a day, you should only fetch it once a day.

```
./yii import commodities
```


### Importing EDDB Systems & Stations

System and station information can be imported by running the following command. Note that this process could take a long time depending upon your system

```
./yii import/systems
./yii import/stations
```