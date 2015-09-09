<?php

use yii\db\Schema;
use yii\db\Migration;

class m150726_142513_create_user_table extends Migration
{
    public function up()
    {
        $this->createTable('user',
        [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING.' NOT NULL ',
            'email' => Schema::TYPE_STRING.' NOT NULL ',
            'password_hash' => Schema::TYPE_STRING.' NOT NULL ',
            'status' => Schema::TYPE_SMALLINT.' NOT NULL ',
            'auth_key' => Schema::TYPE_STRING.'(32) NOT NULL ',
            'create_at' => Schema::TYPE_INTEGER.' NOT NULL ',
            'update_at' => Schema::TYPE_INTEGER.' NOT NULL ',
        ]
        );
    }

    public function down()
    {
        $this->dropTable('user');
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
