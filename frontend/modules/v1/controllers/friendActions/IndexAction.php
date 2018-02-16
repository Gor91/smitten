<?php
/**
 * IndexAction
 *
 * @package    frontend\modules\v1\controllers
 * @subpackage friendActions
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers\friendActions;

use frontend\modules\v1\models\Friends;
use Yii;
use yii\rest\Action;

class IndexAction extends Action
{

    /**
     * @api {GET} /friends Friend list
     * @apiVersion 1.0.0
     * @apiName GetList
     * @apiGroup Friend
     * @apiPermission UserAccess
     *
     * @apiSuccessExample  Response success
     *     HTTP/1.1 200 Ok
     *     {
     *         "code": 200,
     *         "name": "OK",
     *         "data": {
     *             "result": [
     *                 {
     *                   "id": "24",
     *                   "fName": "Jone",
     *                   "lName": "Smith",
     *                   "username": "jone21",
     *                   "gender": 1,
     *                   "lang": "fr",
     *                   "phone": "+37444746579",
     *                   "bio": "I am looking for a life partner and someone who share the same interests as me",
     *                   "age": 29,
     *                   "avatar": "http://smite.sixelitprojects.com/avatars/2018/2/5a86e10455da0.jpg",
     *                   "location": [
     *                       {
     *                           "countryName": "India",
     *                           "countryCode": "IN",
     *                           "cityName": "Mumbai",
     *                           "cityCode": "mumbai",
     *                           "lat": 19.1005,
     *                           "lng": 73.0303
     *                       }
     *                   ]
     *                 }
     *             ],
     *             "_links": {
     *                 "self": {
     *                     "href": "http://api.smite.sixelitprojects.com/v1/friends?offset=1"
     *                 }
     *             },
     *             "_meta": {
     *                 "totalCount": 1,
     *                 "pageCount": 1,
     *                 "currentPage": 1,
     *                 "perPage": 10
     *             }
     *         }
     *     }
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function run()

    {
        $identityId = Yii::$app->user->identity->getId();
        return Friends::find()->friendList($identityId);
    }
}