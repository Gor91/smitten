<?php

use yii\db\Migration;

/**
 * Class m180115_000006_user_location
 */
class m180115_000006_user_location extends Migration
{
    public function up()
    {
        $this->createTable('user_location', [
            'id' => $this->primaryKey()->notNull()->unsigned(),
            'userId' => $this->bigInteger()->unique()->unsigned()->notNull(),
            'lat' => $this->float()->notNull(),
            'lng' => $this->float()->notNull(),
            'countryName' => $this->string(255),
            'countryCode' => $this->string(255),
            'cityName' => $this->string(255),
            'cityCode' => $this->string(255),
            'created' => $this->bigInteger(20)->unsigned(),
            'updated' => $this->bigInteger(20)->unsigned()
        ]);

        $this->addForeignKey('userLocationUserFK', 'user_location', 'userId', 'user', 'id', "CASCADE", "CASCADE");

        $this->createIndex()
    }

    public function down()
    {
        $this->dropForeignKey("userLocationUserFK", "user_location");
        $this->dropForeignKey("userLocationCityFK", "user_location");

        $this->dropTable('user_location');
    }
}