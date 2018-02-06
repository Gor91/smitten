<?php
/**
 * UserQuery
 *
 * @package    common\models
 * @subpackage dataAccess
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models\DataAccess;

use common\components\Filter;
use Yii;
use yii\data\ActiveDataProvider;
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
     * @param $match
     * @param $userId
     * @return ActiveDataProvider
     */
    public function matches($match, $userId)
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;

        $query = parent::where(['!=', 'id', $userId]);
        $query->andWhere([
            'OR',
            ['like', 'user.fName', Filter::cleanText($match)],
            ['like', 'user.fName', Filter::cleanText($match, true)],
            ['like', 'user.username', Filter::cleanText($match, true)]
        ]);


        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 50,
                'pageParam' => 'offset',
                'pageSizeParam' => 'limit'
            ],
        ]);
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