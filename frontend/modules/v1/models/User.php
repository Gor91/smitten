<?php
/**
 * User
 *
 * @package    frontend\modules\v1
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\models;

use borales\extensions\phoneInput\PhoneInputValidator;
use common\components\Constants;
use common\models\Language;
use Yii;
use yii\helpers\ArrayHelper;

class User extends \common\models\User
{
    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            /*Registration rules*/
            [['fName', 'lName', 'username', 'password', 'phone', 'gender'], 'required', 'message' => Constants::ERR_REQUIRED, 'on' => self::SCENARIO_CREATE],
            [['token', 'username', 'phone'], 'unique', 'message' => Constants::ERR_EXIST, 'on' => self::SCENARIO_CREATE],
            [['fName', 'lName', 'username'], 'string', 'min' => '2', 'max' => '60', 'message' => Constants::ERR_TYPE_NOT_ALLOWED, 'tooShort' => Constants::ERR_MIN_LENGTH, 'tooLong' => Constants::ERR_MAX_LENGTH, 'on' => self::SCENARIO_CREATE],
            [['password'], 'string', 'min' => '6', 'max' => '60', 'message' => Constants::ERR_TYPE_NOT_ALLOWED, 'tooShort' => Constants::ERR_MIN_LENGTH, 'tooLong' => Constants::ERR_MAX_LENGTH, 'on' => self::SCENARIO_CREATE],
            [['phone'], 'string', 'on' => self::SCENARIO_CREATE],
            [['phone'], PhoneInputValidator::className(), 'message' => Constants::ERR_NOT_VALID, 'on' => self::SCENARIO_CREATE],
            [['lang'], 'in', 'range' => Language::getSupported(true), 'message' => Constants::ERR_WRONG_VALUE, 'on' => self::SCENARIO_CREATE],
            [['lang'], 'default', 'value' => Language::getDefault(true), 'on' => self::SCENARIO_CREATE],
            [['dob'], 'validateUserBirthDate', 'on' => self::SCENARIO_CREATE],
            /*Login rules*/
            [['password'], 'required', 'message' => Constants::ERR_REQUIRED, 'on' => self::SCENARIO_LOGIN],
            [['username'], 'required', 'message' => Constants::ERR_PHONE_OR_USERNAME_REQUIRED, 'when' => function ($model) {
                return !$model->phone && !$model->username;
            }, 'on' => self::SCENARIO_LOGIN]
        ]);
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateUserBirthDate($attribute, $params)
    {
        $date = new \DateTime();

        date_sub($date, date_interval_create_from_date_string(Yii::$app->params['age.min']));
        $minAgeDate = date_format($date, 'Y-m-d');
        date_sub($date, date_interval_create_from_date_string(Yii::$app->params['age.max']));
        $maxAgeDate = date_format($date, 'Y-m-d');

        if ($this->$attribute > $minAgeDate) {
            $this->addError($attribute, Constants::ERR_AGE_SMALL);
        } elseif ($this->$attribute < $maxAgeDate) {
            $this->addError($attribute, Constants::ERR_AGE_BIG);
        }
    }
}