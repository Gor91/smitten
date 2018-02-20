<?php
/**
 * Friends table
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

use yii\db\Migration;

class m180115_000007_friends extends Migration
{

    public function up()
    {
        $this->createTable('friends', [
            'id' => $this->primaryKey(2)->notNull()->unsigned(),
            'from' => $this->bigInteger()->notNull()->unsigned(),
            'to' => $this->bigInteger()->notNull()->unsigned(),
            'statusId' => $this->integer(2)->notNull()->unsigned(),
            'created' => $this->integer(20),
            'updates' => $this->integer(20),
        ]);

        $this->createIndex('indexFromToFriend','friends',['from', 'to'],true);
        $this->createIndex('indexToFromFriend','friends',['to', 'from'],true);

        $this->addForeignKey('fromUserFK', 'friends', 'from', 'user', 'id', "CASCADE", "CASCADE");
        $this->addForeignKey('toUserFK', 'friends', 'to', 'user', 'id', "CASCADE", "CASCADE");
        $this->addForeignKey('friendStatusFK', 'friends', 'statusId', 'friend_status', 'id', "CASCADE", "CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("fromUserFK", "friends");
        $this->dropForeignKey("toUserFK", "friends");
        $this->dropForeignKey("friendStatusFK", "friends");

        $this->dropTable('friends');
    }
}