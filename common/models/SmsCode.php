<?php
/**
 * SMS code
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "sms_code".
 *
 * @property string $id
 * @property string $userId
 * @property int $code
 * @property string $created
 * @property string $updated
 *
 * @property User $user
 */
class SmsCode extends BaseModel
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'sms_code';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['userId', 'code'], 'required'],
            [['userId', 'code'], 'integer'],
            [['userId'], 'unique'],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User ID'),
            'code' => Yii::t('app', 'Code'),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}