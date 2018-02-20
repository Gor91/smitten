<?php
/**
 * User status table
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

use yii\db\Migration;

class m180115_000001_user_status extends Migration
{
    public function up()
    {
        $this->createTable('user_status', [
            'id' => $this->primaryKey(2)->notNull()->unsigned(),
            'label' => $this->string(50)->notNull()
        ]);

        $this->insert('user_status', ['label' => 'pending']);
        $this->insert('user_status', ['label' => 'active']);
        $this->insert('user_status', ['label' => 'blocked']);
    }

    public function down()
    {
        $this->dropTable('user_status');
    }
}