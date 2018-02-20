<?php
/**
 * User Interest table
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

use yii\db\Migration;

class m180219_000009_user_interest extends Migration
{
    public function up()
    {
        $this->createTable('user_interest', [
            'id' => $this->bigPrimaryKey()->notNull()->unsigned(),
            'userId' => $this->bigInteger()->notNull()->unsigned(),
            'tags' => $this->text()->notNull(),
            'created' => $this->integer(20),
            'updates' => $this->integer(20)
        ]);

        $this->addForeignKey('userInterestFK', 'user_interest', 'userId', 'user', 'id', "CASCADE", "CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey('userInterestFK', 'user_interest');

        $this->dropTable('user_interest');
    }
}
