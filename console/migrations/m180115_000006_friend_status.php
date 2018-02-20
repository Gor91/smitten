<?php
/**
 * Friends status table
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

use yii\db\Migration;

class m180115_000006_friend_status extends Migration
{
    public function up()
    {
        $this->createTable('friend_status', [
            'id' => $this->primaryKey(2)->notNull()->unsigned(),
            'label' => $this->string(50)->notNull()
        ]);

        $this->insert('friend_status', ['label' => 'pending']);
        $this->insert('friend_status', ['label' => 'friend']);

    }

    public function down()
    {
        $this->dropTable('friend_status');
    }
}