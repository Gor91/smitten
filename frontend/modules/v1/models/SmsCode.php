<?php

/**
 * Sms code
 *
 * @package    frontend\modules\v1
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\models;

use common\components\Constants;
use Yii;
use yii\helpers\ArrayHelper;

class SmsCode extends \common\models\SmsCode
{
    /**@var $SCENARIO_ACTIVATION string */
    const SCENARIO_ACTIVATION = 'activation';

    /**@var $SCENARIO_FORGOT_PASSWORD string */
    const SCENARIO_FORGOT_PASSWORD = 'forgot_password';

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            /*Activation rule*/
            [['code'], 'required', 'message' => Constants::ERR_WRONG_VALUE, 'on' => self::SCENARIO_ACTIVATION],
            [['code'], 'integer', 'message' => Constants::ERR_WRONG_VALUE, 'on' => self::SCENARIO_ACTIVATION],
            [['code'], 'integer', 'integerOnly' => true, 'min' => Yii::$app->params['code.min'], 'max' => Yii::$app->params['code.max'], 'tooBig' => Constants::ERR_OUT_OF_RANGE, 'tooSmall' => Constants::ERR_OUT_OF_RANGE, 'message' => Constants::ERR_OUT_OF_RANGE, 'on' => self::SCENARIO_ACTIVATION],
            [['userId'], 'unique']
        ]);
    }
}