<?php

namespace frontend\models\v1\controllers\userAction;

use frontend\models\v1\models\User;
use yii\rest\Action;

class IndexAction extends Action
{
    public function run($match = null)
    {
        return User::find()->all();
    }
}