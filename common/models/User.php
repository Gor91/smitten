<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\components\Filter;
use common\models\UserStatus;
use common\models\DataAccess\UserQuery;

/**
 * User model
 *
 * @property int    $id
 * @property int    $statusId
 * @property string $fName
 * @property string $lName
 * @property string $username
 * @property string $password
 * @property int    $gender
 * @property string $dob
 * @property string $bio
 * @property string $phone
 * @property string $token
 * @property string $avatar
 * @property int    $created
 * @property int    $updated
 */
class User extends ActiveRecord implements IdentityInterface
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            /* Filters */
            [['fName', 'lName', 'username', 'bio', 'password'], 'filter', 'filter' => function ($value) {
                return Filter::cleanText($value);
            }],
            ['username', 'filter', 'filter' => function ($value) {
                return Filter::removeAtFromNickname($value);
            }],
            /* Validation rules */
            ['statusId', 'default', 'value' => UserStatus::PENDING],
            ['statusId', 'in', 'range' => [ UserStatus::PENDING,  UserStatus::BLOCKED]],
            [['avatar', 'bio'], 'default', 'value' => null],
            ['password', 'string', 'min' => 6, 'max' => 20],
            [['dob'], 'date', 'format' => 'yyyy-mm-dd']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'statusId' => Yii::t('app', 'Status Id'),
            'fName' => Yii::t('app', 'First Name'),
            'lName' => Yii::t('app', 'Last Name'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'gender' => Yii::t('app', 'Gender'),
            'dob' => Yii::t('app', 'Day of bird'),
            'bio' => Yii::t('app', 'Bio'),
            'phone' => Yii::t('app', 'Phone'),
            'token' => Yii::t('app', 'Access Token'),
            'avatar' => Yii::t('app', 'Avatar   '),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated')
        ];
    }

    /**
     * @return array
     */
    public function fields()
    {
        $fields = ['id', 'fName', 'lName', 'username', 'phone'];

        if ($this->bio) {
            $fields[] = 'bio';
        }

        if ($this->dob) {
            $fields[] = 'dob';
        }

        return $fields;
    }


    /**
     * @return UserQuery|\yii\db\ActiveQuery
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
    /**
     * @param int|string $id
     * @return null|IdentityInterface|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'statusId' => UserStatus::ACTIVE]);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return void|IdentityInterface
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @param $username
     * @return null|static
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'statusId' =>  UserStatus::ACTIVE]);
    }

    /**
     * @return int|mixed|string
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return mixed|string
     */
    public function getAuthKey()
    {
        return $this->token;
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @param $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash(md5($password));
    }

    /**
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->token = Yii::$app->security->generateRandomString();
    }
}