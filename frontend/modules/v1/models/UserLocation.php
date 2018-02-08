<?php
/**
 * User Location
 *
 * @package    frontend\modules\v1
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\models;

use common\components\Constants;
use yii\helpers\ArrayHelper;

/**
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
class UserLocation extends \common\models\UserLocation
{
    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            /** registration rules*/
            [['userId', 'countryName', 'countryCode', 'cityName', 'cityCode', 'lat', 'lng'], 'required', 'message' => Constants::ERR_REQUIRED, 'on' => User::SCENARIO_CREATE],
            [['lat', 'lng'], 'number', 'message' => Constants::ERR_WRONG_VALUE, 'on' => User::SCENARIO_CREATE],
            [['countryName', 'countryCode', 'cityName', 'cityCode'], 'string', 'max' => 255, 'on' => User::SCENARIO_CREATE]
        ]);
    }
}