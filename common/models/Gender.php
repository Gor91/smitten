<?php
/**
 * Gender
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models;

use Yii;

/**
 * This is the model class for table "gender".
 *
 * @property int $id
 * @property string $label
 *
 * @property User[] $users
 */
class Gender extends \yii\db\ActiveRecord
{
    /** @var $MALE*/
    const MALE = 1;

    /** @var $FEMALE*/
    const FEMALE = 2;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'gender';
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
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['gender' => 'id']);
    }
}
