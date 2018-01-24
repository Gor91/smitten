<?php
/**
 * Module
 *
 * @apiDefine UserAccess User access only
 * Header key Authorization : Bearer ACCESS_TOKEN
 * Request key ?access-token=ACCESS_TOKEN
 *
 * @info smite app 1.0.0 version
 *
 * @package frontend\modules
 * @subpackage v1
 * @author SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1;


class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\v1\controllers';

    public function init()
    {
        parent::init();
    }
}