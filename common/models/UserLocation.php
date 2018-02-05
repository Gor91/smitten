<?php
/**
 * User Location
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models;

use common\components\Filter;
use Yii;

/**
 * This is the model class for table "user_location".
 *
 * @property int $id
 * @property string $userId
 * @property double $lat
 * @property double $lng
 * @property string $countryName
 * @property string $countryCode
 * @property string $cityName
 * @property string $cityCode
 * @property string $created
 * @property string $updated
 *
 * @property User $user
 */
class UserLocation extends BaseModel
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'user_location';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            /* Filters*/
            [['countryName', 'countryCode', 'cityName', 'cityCode'], 'filter', 'filter' => function ($value) {
                return Filter::cleanText($value);
            }],
            [['userId', 'countryName', 'countryCode', 'cityName', 'cityCode', 'lat', 'lng'], 'required', 'message' => 'err.required'],
            [['userId'], 'integer'],
            [['userId'], 'unique'],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']]
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
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'countryName' => Yii::t('app', 'Country Name'),
            'countryCode' => Yii::t('app', 'Country Code'),
            'cityName' => Yii::t('app', 'City Name'),
            'cityCode' => Yii::t('app', 'City Code'),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated')
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