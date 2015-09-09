<?php
use yii\db\Schema;
use yii\db\Migration;

class m150726_142256_drop_user_table extends Migration
{
    public function up()
    {
        $names = $this->db->schema->getTableNames();
        foreach($names as $name) {
            if ($name == 'user') {
                $this->dropTable('user');
            }
        }

    }

    public function down()
    {
        echo "m150726_142256_drop_user_table cannot be reverted.\n";

        return false;
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
