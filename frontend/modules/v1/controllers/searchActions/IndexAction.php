<?php
/**
 * IndexAction
 *
 * @package    frontend\modules\v1\controllers
 * @subpackage searchActions
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers\searchActions;

use common\components\Filter;
use common\models\User;
use Yii;
use yii\rest\Action;

class IndexAction extends Action
{
    public function run($match)
    {
        $match = Filter::cleanText($match);
        $userId = Yii::$app->user->identity->getId();
        return User::find()->matches($match, $userId);
    }
}