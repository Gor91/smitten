<?php
/**
 * LoginAction
 *
 * @package    frontend\modules\v1\controllers
 * @subpackage userActions
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers\userActions;

use frontend\modules\v1\models\UserStatus;
use Yii;
use frontend\modules\v1\models\User;
use yii\rest\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use common\components\Constants;
use yii\web\ServerErrorHttpException;

class LoginAction extends Action
{
    public function run()
    {
        /* @var $model User */
        $model = new $this->modelClass;
        $model->phone = Yii::$app->request->getBodyParam('phone', null);
        $model->username = Yii::$app->request->getBodyParam('username', null);
        $model->password = Yii::$app->request->getBodyParam('password', null);

        if ($model->validate()) {
            $credential = $model->phone ?: $model->username;
            $user = $model::find()->byUsernameOrPhone($credential);

            if (!is_null($user) && !$model->password) {
                throw new ForbiddenHttpException();
            } else if (is_null($user) || !Yii::$app->getSecurity()->validatePassword($model->password, $user->password)) {
                throw new NotFoundHttpException(Constants::SYS_ERR_INVALID_CREDENTIALS);
            }

            switch ($user->statusId) {
                case UserStatus::PENDING:
                    throw new ForbiddenHttpException(Constants::SYS_ERR_PROFILE_NOT_ACTIVE);
                case UserStatus::BLOCKED:
                    throw new ForbiddenHttpException(Constants::SYS_ERR_PROFILE_BLOCKED);
            }

            return [
                'token' => $user->token,
                'user' => $user
            ];
        }

        if (!$model->hasErrors()) {
            throw new ServerErrorHttpException();
        }

        return $model;
    }
}