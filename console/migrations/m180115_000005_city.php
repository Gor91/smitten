<?php

use yii\db\Migration;

/**
 * Class m180115_000005_city
 */
class m180115_000005_city extends Migration
{
    public function up()
    {
        $this->createTable('city', [
            'id' => $this->primaryKey()->notNull()->unsigned(),
            'country_id' => $this->integer()->notNull()->unsigned(),
            'name' => $this->string(20),
            'code' => $this->integer()->notNull()->unsigned(),
            'lat' => $this->float(20)->notNull(),
            'lng' => $this->float(20)->notNull()
        ]);

        $this->addForeignKey('cityCountryFK', 'city', 'country_id', 'country', 'id', "CASCADE", "CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("cityCountryFK", "city");

        $this->dropTable('city');
    }
}