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
            'created_at'                => $this->integer(),
            'updated_at'                => $this->integer(),            
            'FOREIGN KEY(system_id) REFERENCES systems(id)'
        ]);

        $this->createTable('station_commodities', [
            'id'            => $this->primaryKey(),
            'station_id'    => $this->integer(),
            'commodity_id'  => $this->integer(),
            'supply'        => $this->integer(),
            'buy_price'     => $this->integer(),
            'sell_price'    => $this->integer(),
            'demand'        => $this->integer(),
            'collected_at'  => $this->integer(),
            'update_count'  => $this->integer(),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer(),
            'type'          => $this->string(),
            'FOREIGN KEY(station_id) REFERENCES stations(id)',
            'FOREIGN KEY(commodity_id) REFERENCES commodities(id)'
        ]);

        $this->createIndex('station_commodities_uk', 'station_commodities', 'station_id, commodity_id', true);

        $this->createTable('station_economies', [
            'id'            => $this->primaryKey(),
            'station_id'    => $this->integer(),
            'name'          => $this->string(),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer(), 
            'FOREIGN KEY(station_id) REFERENCES stations(id)',
        ]);

        $this->createIndex('station_economies_uk', 'station_economies', 'station_id, name', true);
    }

    public function safeDown()
    {
        $this->dropTable('station_economies');
        $this->dropTable('station_commodities');
        $this->dropTable('stations');
    }
}
