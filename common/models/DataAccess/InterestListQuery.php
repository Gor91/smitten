<?php
/**
 * InterestListQuery
 *
 * @package    common\models
 * @subpackage dataAccess
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models\DataAccess;

use common\components\Filter;
use common\models\InterestList;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use common\models\User;

class InterestListQuery extends ActiveQuery
{

    /**
     * @param null $db
     * @return array|InterestList[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return array|null|InterestList
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return ActiveDataProvider
     */
    public function listTags()
    {
        $query = parent::select(['tag']);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 20,
                'pageParam' => 'offset',
                'pageSizeParam' => 'limit'
            ],
        ]);
    }
}