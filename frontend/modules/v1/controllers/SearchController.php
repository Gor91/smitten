<?php
/**
 * SearchController
 *
 * @apiDefine Search Search
 *
 * @package    frontend\modules\v1
 * @subpackage controllers
 * @author     SIXELIT <sixelit.com>
 */

namespace frontend\modules\v1\controllers;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use frontend\controllers\RestController;
use yii\filters\auth\QueryParamAuth;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class SearchController extends RestController
{

    /**
     * Allows two types of authorization.
     *   1.Query param authorization
     *   2.Oauth2 authorization(bearer authorization).
     *
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge([
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    HttpBearerAuth::className(),
                    QueryParamAuth::className()
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ], parent::behaviors());
    }

    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass = 'frontend\modules\v1\models\User';

    /**
     * @var string|array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = [
        'class' => 'frontend\components\RestSerializer',
        'collectionEnvelope' => 'result'
    ];

    /**
     * @return array
     */
    public function actions()
    {
        $actions = [];

        $actions['index'] = [
            'class' => 'frontend\modules\v1\controllers\searchActions\IndexAction',
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}