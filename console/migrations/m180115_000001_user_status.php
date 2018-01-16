<?php

use yii\db\Migration;

/**
 * Class m180115_000001_user_status
 */
class m180115_000001_user_status extends Migration
{
    public function up()
    {
        $this->createTable('user_status', [
            'id' => $this->primaryKey(2)->notNull()->unsigned(),
            'label' => $this->string(50)->notNull()
        ]);

        $this->insert('user_status', ['label' => 'published']);
        $this->insert('user_status', ['label' => 'blocked']);
        $this->insert('user_status', ['label' => 'deleted']);
    }

    public function down()
    {
        $this->dropTable('user_status');
    }
}