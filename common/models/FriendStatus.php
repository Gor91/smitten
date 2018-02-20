<?php
/**
 * FriendStatus
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "friend_status".
 *
 * @property int $id
 * @property string $label
 *
 * @property Friends[] $friends
 */
class FriendStatus extends ActiveRecord
{
    /** @var $PENDING */
    const PENDING = 1;

    /** @var $FRIEND */
    const FRIEND = 2;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'friend_status';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['label'], 'required'],
            [['label'], 'string', 'max' => 50],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label' => Yii::t('app', 'Label'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriends()
    {
        return $this->hasMany(Friends::className(), ['statusId' => 'id']);
    }
}
