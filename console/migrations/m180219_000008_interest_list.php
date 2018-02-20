<?php
/**
 * Interest List table
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

use yii\db\Migration;

class m180219_000008_interest_list extends Migration
{
    public function up()
    {
        $this->createTable('interest_list', [
            'id' => $this->primaryKey(3)->notNull()->unsigned(),
            'tag' => $this->string(100)->unique()->notNull()
        ]);

        $this->insert('interest_list', ['tag' => 'Astrology']);
        $this->insert('interest_list', ['tag' => 'Board Games']);
        $this->insert('interest_list', ['tag' => 'Sports']);
        $this->insert('interest_list', ['tag' => 'Cooking']);
        $this->insert('interest_list', ['tag' => 'Camping']);
        $this->insert('interest_list', ['tag' => 'Movies']);
        $this->insert('interest_list', ['tag' => 'Dancing']);
        $this->insert('interest_list', ['tag' => 'Creative arts']);
        $this->insert('interest_list', ['tag' => 'Food']);
        $this->insert('interest_list', ['tag' => 'Extreme']);
        $this->insert('interest_list', ['tag' => 'Fashion']);
        $this->insert('interest_list', ['tag' => 'Hiking']);
        $this->insert('interest_list', ['tag' => 'Music']);
        $this->insert('interest_list', ['tag' => 'Yoga']);
        $this->insert('interest_list', ['tag' => 'Modelling']);
        $this->insert('interest_list', ['tag' => 'Reading']);
        $this->insert('interest_list', ['tag' => 'Video games']);
        $this->insert('interest_list', ['tag' => 'Writing']);

        $this->createIndex('indexInterestListTags', 'interest_list', 'tag', true);
    }

    public function down()
    {
        $this->dropTable('interest_list');
    }
}
