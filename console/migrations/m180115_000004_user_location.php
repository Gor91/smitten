<?php
/**
 * User location table
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

use yii\db\Migration;

class m180115_000004_user_location extends Migration
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

        $this->createIndex('userLocationLatINDEX', 'user_location', 'lat');
        $this->createIndex('userLocationLngINDEX', 'user_location', 'lng');
        $this->createIndex('userLocationCountryNameINDEX', 'user_location', 'countryName');
        $this->createIndex('userLocationCountryCodeINDEX', 'user_location', 'countryCode');
        $this->createIndex('userLocationCiteNameINDEX', 'user_location', 'cityName');
        $this->createIndex('userLocationCityCode INDEX', 'user_location', 'cityCode');
        $this->createIndex('userLocationCreatedINDEX', 'user_location', 'created');
        $this->createIndex('userLocationUpdatedINDEX', 'user_location', 'updated');
    }

    public function down()
    {
        $this->dropForeignKey("userLocationUserFK", "user_location");

        $this->dropTable('user_location');
    }
}