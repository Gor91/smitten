<?php
/**
 * Sms code table
 *
 * @package    console
 * @subpackage migration
 * @author     SIXELIT <sixelit.com>
 */

use yii\db\Migration;

class m180115_000005_sms_code extends Migration
{
    public function up()
    {
        $this->createTable('sms_code', [
            'id' => $this->bigPrimaryKey()->notNull()->unsigned(),
            'userId' => $this->bigInteger()->unique()->unsigned()->notNull(),
            'code' => $this->integer(10)->unsigned()->notNull(),
            'created' => $this->bigInteger(20)->unsigned(),
            'updated' => $this->bigInteger(20)->unsigned()
        ]);

        $this->addForeignKey('userSMSCodeFK', 'sms_code', 'userId', 'user', 'id', "CASCADE", "CASCADE");

        $this->createIndex('SMSCodeINDEX', 'sms_code', 'code');
    }

    public function down()
    {
        $this->dropForeignKey('userSMSCodeFK', 'sms_code');

        $this->dropTable('sms_code');
    }
}