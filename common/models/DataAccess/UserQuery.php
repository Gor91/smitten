<?php
/**
 * UserQuery
 *
 * @package    common\models
 * @subpackage dataAccess
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models\DataAccess;

use yii\db\ActiveQuery;
use common\models\User;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return array|User[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return User|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $param
     * @return array|null|\yii\db\ActiveRecord
     */
        public function byUsernameOrPhone($param)
    {
        return parent::where(['OR', ['username' => $param], ['phone' => $param]])->one();
    }
}