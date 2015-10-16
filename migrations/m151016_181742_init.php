<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Initial migration for Galnet News API
 * @class m151016_181742_init
 */
class m151016_181742_init extends Migration
{
    public function safeUp()
    {
        $this->createTable('news', [
            'id'                    => $this->primaryKey(),
            'uid'                   => $this->string(60)->notNull(),
            'title'                 => $this->string(255)->notNull(),
            'content'               => $this->text()->notNull(),
            'created_at'            => $this->integer(),
            'updated_at'            => $this->integer(),
            'published_at'          => $this->integer(),
            'published_at_native'   => $this->integer()
        ]);

        $this->createIndex('uid', 'news', 'uid', true);

        if ($this->db->getDriverName() == "sqlite")
            return;
        
        $this->alterColumn('news', 'content', 'MEDIUMTEXT');
    }

    /**
     * Transaction safe migrate/down method.
     * Drops the news table
     */
    public function safeDown()
    {
        return $this->dropTable('news');
    }
}
