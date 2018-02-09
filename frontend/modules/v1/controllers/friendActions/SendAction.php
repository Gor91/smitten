<?php
/**
 * SendAction
 *
 * @package    frontend\modules\v1\controllers
 * @subpackage friendActions
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers\friendActions;

use frontend\modules\v1\models\Friends;
use frontend\modules\v1\models\Gender;
use Yii;
use yii\rest\Action;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

class SendAction extends Action
{
    /**
     * @api {POST} /friends/{id} Send friend action
     * @apiVersion 1.0.0
     * @apiName SendFriend
     * @apiGroup Friend
     * @apiPermission UserAccess
     *
     * @apiSuccessExample  Response success
     *     HTTP/1.1 200 Ok
     *     {
     *         "code": 202,
     *         "name": "Accepted"
     *     }
     *
     * @apiErrorExample Response error: Forbidden
     *    HTTP/1.1 403 Forbidden
     *  {
     *      "code": 403,
     *      "name": "Forbidden"
     *  }
     *
     * @param $id
     * @return Friends|void
     * @throws ForbiddenHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id)
    {
        $identityId = Yii::$app->user->identity->getId();

        if ($id != $identityId && Yii::$app->user->identity->gender == Gender::FEMALE) {

            /** @var $model Friends */
            $model = new $this->modelClass;
            $model->from = $identityId;
            $model->to = $id;

            if ($model->validate() && !Friends::find()->friendExists($identityId, $id)) {
                try {
                    if ($model->save()) {
                        Yii::$app->getResponse()->setStatusCode(202);
                        return;
                    }
                } catch (\Exception $e) {
                    Yii::error($e->getMessage(), 'app');
                }

                if (!$model->hasErrors()) {
                    throw new ServerErrorHttpException();
                }
                return $model;
            }
        }

        throw new ForbiddenHttpException();
    }
}