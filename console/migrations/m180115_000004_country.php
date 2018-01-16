<?php

use yii\db\Migration;

/**
 * Class m180115_000004_country
 */
class m180115_000004_country extends Migration
{
    public function up()
    {
        $this->createTable('country',[
            'id' => $this->primaryKey()->notNull()->unsigned(),
            'name' => $this->string(20)->notNull(),
            'code' => $this->integer()->notNull()->unsigned()
        ]);
    }

    public function down()
    {
        $this->dropTable('country');
    }
}