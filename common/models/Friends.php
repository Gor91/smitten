<?php
/**
 * Friends
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models;

use common\models\DataAccess\FriendQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "friends".
 *
 * @property int $id
 * @property string $from
 * @property string $to
 * @property int $statusId
 * @property int $created
 * @property int $updates
 *
 * @property FriendStatus $status
 * @property User $FromRequest
 */
class Friends extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'friends';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['from', 'to'], 'required'],
            [['from', 'to', 'statusId', 'created', 'updates'], 'integer'],
            [['statusId'], 'exist', 'skipOnError' => true, 'targetClass' => FriendStatus::className(), 'targetAttribute' => ['statusId' => 'id']],
            ['statusId', 'default', 'value' => FriendStatus::PENDING],
            [['from'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['from' => 'id']],
            [['to'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['to' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'statusId' => Yii::t('app', 'Status ID'),
            'created' => Yii::t('app', 'Created'),
            'updates' => Yii::t('app', 'Updates'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(FriendStatus::className(), ['id' => 'statusId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFromRequestFriend()
    {
        return $this->hasOne(User::className(), ['id' => 'from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToRequestFriend()
    {
        return $this->hasOne(User::className(), ['id' => 'to']);
    }

    /**
     * @return FriendQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return new FriendQuery(get_called_class());
    }
}