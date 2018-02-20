<?php
/**
 * InterestList
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models;

use common\models\DataAccess\InterestListQuery;
use Yii;

/**
 * This is the model class for table "interest_list".
 *
 * @property int $id
 * @property string $tag
 */
class InterestList extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'interest_list';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['tag'], 'required'],
            [['tag'], 'string', 'max' => 100],
            [['tag'], 'unique'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tag' => Yii::t('app', 'Tag'),
        ];
    }

    /**
     * @return InterestListQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return new InterestListQuery(get_called_class());
    }
}
