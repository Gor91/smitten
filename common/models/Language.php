<?php
/**
 * Language
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models;

use yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "language".
 *
 * @property string $key
 * @property string $label
 * @property integer $default
 *
 * @property User[] $users
 */
class Language extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'language';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['key', 'label'], 'required'],
            [['default'], 'integer'],
            [['default'], 'default', 'value' => 0],
            [['key', 'label'], 'string', 'max' => 20]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'key' => Yii::t('app', 'Key'),
            'label' => Yii::t('app', 'Label'),
            'default' => Yii::t('app', 'Default')
        ];
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['lng' => 'key']);
    }

    /**
     * @param bool $onlyKey
     * @return mixed
     */
    public static function getSupported($onlyKey = false)
    {
        if ($onlyKey) {
            return array_values(ArrayHelper::map(self::find()->all(), 'key', 'key'));
        }

        return array_values(ArrayHelper::map(self::find()->all(), 'key', 'label'));
    }

    /**
     * @param bool $onlyKey
     * @return null|string
     */
    public static function getDefault($onlyKey = false)
    {
        $model = self::findOne(['default' => 1]);

        if (!is_null($model)) {
            if ($onlyKey) {
                return $model->key;
            }

            return $model;
        }

        return null;
    }
}
