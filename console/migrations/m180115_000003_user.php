<?php

use yii\db\Migration;

/**
 * Class m180115_000003_user
 */
class m180115_000003_user extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->bigPrimaryKey()->notNull()->unsigned(),
            'status_id' => $this->integer(2)->notNull()->unsigned(),
            'f_name' => $this->string(20)->notNull(),
            'l_name' => $this->string(20)->notNull(),
            'username' => $this->string(20)->notNull(),
            'email' => $this->string(20)->notNull(),
            'password' => $this->string(20)->notNull(),
            'gender' => $this->integer(2)->notNull()->unsigned(),
            'dob' => $this->date(),
            'bio' => $this->string(100),
            'phone' => $this->string(20)->notNull(),
            'token' => $this->string(100),
            'avatar' => $this->string(100),
            'created' => $this->integer(15),
            'updated' => $this->integer(15)
        ]);

        $this->addForeignKey('userStatusFK', 'user', 'status_id', 'user_status', 'id', "CASCADE", "CASCADE");
        $this->addForeignKey('userGenderFK', 'user', 'gender', 'gender', 'id', "CASCADE", "CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey("userStatusFK", "user");
        $this->dropForeignKey("userGenderFK", "user");

        $this->dropTable('user');
    }
}