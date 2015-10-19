<?php

use yii\db\Schema;
use yii\db\Migration;

class m151019_195524_eddb_stations extends Migration
{
    public function safeUp()
    {
        $this->createTable('stations', [
            'id'                        => $this->primaryKey(),
            'name'                      => $this->string(),
            'system_id'                 => $this->integer(),
            'max_landing_pad_size'      => $this->string(),
            'distance_to_star'          => $this->integer(),
            'faction'                   => $this->string(),
            'government'                => $this->string(),
            'allegiance'                => $this->string(),
            'state'                     => $this->string(),
            'type'                      => $this->string(),
            'has_blackmarket'           => $this->boolean(),
            'has_commodities'           => $this->boolean(),
            'has_refuel'                => $this->boolean(),
            'has_repair'                => $this->boolean(),
            'has_rearm'                 => $this->boolean(),
            'has_outfitting'            => $this->boolean(),
            'has_shipyard'              => $this->boolean(),
            'updated_at'                => $this->integer(),
            'created_at'                => $this->integer(),
            'FOREIGN KEY(system_id) REFERENCES systems(id)'
        ]);

        $this->createTable('station_commodities', [
            'station_id'    => $this->integer(),
            'commodity_id'  => $this->integer(),
            'supply'        => $this->integer(),
            'buy_price'     => $this->integer(),
            'sell_price'    => $this->integer(),
            'demand'        => $this->integer(),
            'collected_At'  => $this->integer(),
            'update_count'  => $this->integer(),
            'created_at'    => $this->integer(),
            'FOREIGN KEY(station_id) REFERENCES stations(id)',
            'FOREIGN KEY(commodity_id) REFERENCES commodities(id)'
        ]);

        $this->createIndex('station_commodities_uk', 'station_commodities', 'station_id, commodity_id', true);

        $this->createTable('station_economies', [
            'station_id'    => $this->integer(),
            'name'          => $this->string(),
            'FOREIGN KEY(station_id) REFERENCES stations(id)',
        ]);

        $this->createIndex('station_economies_uk', 'station_economies', 'station_id, name', true);

        $this->createTable('station_import_commodities', [
            'station_id'    => $this->integer(),
            'commodity_id'  => $this->integer(),
            'FOREIGN KEY(station_id) REFERENCES stations(id)',
        ]);

        $this->createIndex('station_import_commodities_uk', 'station_import_commodities', 'station_id, commodity_id', true);

        $this->createTable('station_export_commodities', [
            'station_id'    => $this->integer(),
            'commodity_id'  => $this->integer(),
            'FOREIGN KEY(station_id) REFERENCES stations(id)',
        ]);

        $this->createIndex('station_export_commodities_uk', 'station_export_commodities', 'station_id, commodity_id', true);

        $this->createTable('station_prohibited_commodities', [
            'station_id'    => $this->integer(),
            'commodity_id'  => $this->integer(),
            'FOREIGN KEY(station_id) REFERENCES stations(id)',
        ]);

        $this->createIndex('station_prohibited_commodities_uk', 'station_prohibited_commodities', 'station_id, commodity_id', true);
    }

    public function safeDown()
    {
        $this->dropTable('station_import_commodities');
        $this->dropTable('station_export_commodities');
        $this->dropTable('station_prohibited_commodities');
        $this->dropTable('station_economies');
        $this->dropTable('station_commodities');
        $this->dropTable('stations');
    }
}
