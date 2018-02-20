<?php
/**
 * SMS code
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models;

use common\components\Filter;
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
            /*Filter sms code*/
            [['code'], 'filter', 'filter' => function ($value) {
                return Filter::cleanText($value);
            }],
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

    /**
     * @param $code
     * @return bool
     */
    public static function existsCode($code)
    {
        return static::find()->where(['AND', ['userId' => Yii::$app->user->identity->getId(), 'code' => $code]])->exists();
    }
}