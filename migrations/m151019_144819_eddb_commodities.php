<?php

use yii\db\Schema;
use yii\db\Migration;

class m151019_144819_eddb_commodities extends Migration
{
    public function safeUp()
    {
        $this->createTable('commodities_categories', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->createTable('commodities', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'category_id' => $this->integer(),
            'average_price' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'FOREIGN KEY(category_id) REFERENCES commodities_categories(id)'
        ]);
    }

    public function safeDown()
    {
        echo "m151019_144819_eddb_commodities cannot be reverted.\n";
        return false;
    }
}
