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
            'user_id' => $this->bigInteger()->notNull()->unsigned(),
            'city_id' => $this->integer()->notNull()->unsigned()

        ]);

        $this->addForeignKey('userLocationUserFK', 'user_location', 'user_id', 'user', 'id', "CASCADE", "CASCADE");
        $this->addForeignKey('userLocationCityFK', 'user_location', 'city_id', 'city', 'id', "CASCADE", "CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("userLocationUserFK", "user_location");
        $this->dropForeignKey("userLocationCityFK", "user_location");

        $this->dropTable('user_location');
    }
}