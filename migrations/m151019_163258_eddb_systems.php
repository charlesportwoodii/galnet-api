<?php

use yii\db\Schema;
use yii\db\Migration;

class m151019_163258_eddb_systems extends Migration
{
    public function safeUp()
    {
        $this->createTable('systems', [
            'id'                => $this->primaryKey(),
            'name'              => $this->string(255),
            'x'                 => $this->float(),
            'y'                 => $this->float(),
            'z'                 => $this->float(),
            'faction'           => $this->string(255),
            'population'        => $this->integer(),
            'government'        => $this->string(),
            'allegiance'        => $this->string(255),
            'state'             => $this->string(),
            'security'          => $this->string(50),
            'primary_economy'   => $this->string(75),
            'needs_permit'      => $this->boolean(),
            'created_at'        => $this->integer(),
            'updated_at'        => $this->integer()
        ]);
    }

    public function safeDown()
    {
        echo "m151019_163258_eddb_systems cannot be reverted.\n";
        return false;
    }
}
