<?php
/**
 * User table
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

use common\models\Language;
use yii\db\Migration;

class m180115_000003_user extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->bigPrimaryKey()->notNull()->unsigned(),
            'statusId' => $this->integer(2)->notNull()->unsigned(),
            'lang' => $this->string(20)->defaultValue(Language::getDefault(true)),
            'fName' => $this->string(60)->notNull(),
            'lName' => $this->string(60)->notNull(),
            'username' => $this->string(60)->unique()->notNull(),
            'password' => $this->string(100)->notNull(),
            'gender' => $this->integer(2)->notNull()->unsigned(),
            'dob' => $this->date(),
            'bio' => $this->string(100),
            'phone' => $this->string(60)->unique()->notNull(),
            'token' => $this->string(100),
            'avatar' => $this->string(100),
            'created' => $this->integer(20),
            'updated' => $this->integer(20)
        ]);

        $this->addForeignKey('userStatusFK', 'user', 'statusId', 'user_status', 'id', "CASCADE", "CASCADE");
        $this->addForeignKey('userLngFK', 'user', 'lang', 'language', 'key', "CASCADE", "CASCADE");
        $this->addForeignKey('userGenderFK', 'user', 'gender', 'gender', 'id', "CASCADE", "CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("userStatusFK", "user");
        $this->dropForeignKey("userLngFK", "user");
        $this->dropForeignKey("userGenderFK", "user");

        $this->dropTable('user');
    }
}