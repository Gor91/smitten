<?php
/**
 * FriendQuery
 *
 * @package    common\models
 * @subpackage dataAccess
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models\DataAccess;

use common\models\Friends;
use frontend\modules\v1\models\FriendStatus;
use frontend\modules\v1\models\User;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class FriendQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return array|Friends[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return array|null|\yii\db\ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $from
     * @param $to
     * @return bool
     */
    public function friendExists($from, $to)
    {
        return parent::where([
            'OR',
            ['AND', ['from' => $from, 'to' => $to]],
            ['AND', ['from' => $to, 'to' => $from]]
        ])->exists();
    }

    /**
     * @param $userId
     * @return ActiveDataProvider
     */
    public function friendList($userId)
    {
        $queryFrom = User::find();
        $queryFrom->joinWith(['fromRequestUser'])
            ->where(['to' => $userId])
            ->andWhere(['friends.statusId' => FriendStatus::FRIEND]);

        $queryTo = User::find();
        $queryTo->joinWith(['toRequestUser'])
            ->where(['from' => $userId])
            ->andWhere(['friends.statusId' => FriendStatus::FRIEND]);

        $query = $queryFrom->union($queryTo);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 10,
                'pageParam' => 'offset',
                'pageSizeParam' => 'limit'
            ]
        ]);
    }

    /**
     * @param $userId
     * @return ActiveDataProvider
     */
    public function checkPendingList($userId)
    {
        $queryTo = User::find();
        $queryTo->joinWith(['fromRequestUser'])
            ->where(['to' => $userId])
            ->andWhere(['friends.statusId' => FriendStatus::PENDING]);

        return new ActiveDataProvider([
            'query' => $queryTo,
            'pagination' => [
                'defaultPageSize' => 10,
                'pageParam' => 'offset',
                'pageSizeParam' => 'limit'
            ]
        ]);
    }
}