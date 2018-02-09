<?php
/**
 * DeleteAction
 *
 * @package    frontend\modules\v1\controllers
 * @subpackage deleteActions
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers\friendActions;

use frontend\modules\v1\models\Friends;
use Yii;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class DeleteAction extends Action
{
    /**
     * @api {DELETE} /friends/{id} Delete friend request
     * @apiVersion 1.0.0
     * @apiName DeleteFriend
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
     * @apiErrorExample Response error: Not Found
     *    HTTP/1.1 404 Not Found
     *  {
     *      "code": 404,
     *      "name": "Not Found"
     *  }
     *
     * @param $id
     * @return null|void|static
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \Throwable
     */
    public function run($id)
    {
        $identityId = Yii::$app->user->identity->getId();
        $model = Friends::findOne(['from' => $id, 'to' => $identityId]);

        if (Friends::find()->friendExists($identityId, $id)) {
            try {
                if ($model->delete()) {
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

        throw new NotFoundHttpException();
    }
}