<?php
/**
 * Language table
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

use yii\db\Migration;

class m180115_000000_language extends Migration
{
    public function up()
    {
        $this->createTable('language', [
            'key' => $this->string(20)->notNull(),
            'label' => $this->string(20)->notNull(),
            'default' => $this->boolean()->defaultValue(0)
        ]);

        $this->addPrimaryKey('languagePK', 'language', 'key');

        $this->insert('language', ['label' => 'English', 'key' => 'en', 'default' => 1]);
        $this->insert('language', ['label' => 'Russian', 'key' => 'ru']);
        $this->insert('language', ['label' => 'French', 'key' => 'fr']);
        $this->insert('language', ['label' => 'German', 'key' => 'de']);
        $this->insert('language', ['label' => 'Japanese', 'key' => 'ja']);
        $this->insert('language', ['label' => 'Chinese', 'key' => 'zh-Hant']);
    }

    public function down()
    {
        $this->dropTable('language');
    }
}