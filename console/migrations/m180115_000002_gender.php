<?php
/**
 * Gender table
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

use yii\db\Migration;

class m180115_000002_gender extends Migration
{
    public function up()
    {
        $this->createTable('gender', [
            'id' => $this->primaryKey(2)->notNull()->unsigned(),
            'label' => $this->string(50)->notNull()
        ]);

        $this->insert('gender', ['label' => 'male']);
        $this->insert('gender', ['label' => 'female']);
    }

    public function down()
    {
        $this->dropTable('gender');
    }
}