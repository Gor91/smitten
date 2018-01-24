<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\components\Filter;

/**
 * User model
 *
 * @property int $id
 * @property int $status_id
 * @property string $f_name
 * @property string $l_name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property int $gender
 * @property string $dob
 * @property string $bio
 * @property string $phone
 * @property string $token
 * @property string $avatar
 * @property int $created
 * @property int $updated
 */
class User extends ActiveRecord implements IdentityInterface
{
    /** @var $STATUS_BLOCKED */
    const STATUS_BLOCKED = 1;
    /** @var $STATUS_ACTIVE */
    const STATUS_ACTIVE = 2;
    /** @var $STATUS_PENDING */
    const STATUS_PENDING = 3;

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
            [['f_name', 'l_name', 'username', 'bio', 'email', 'password'], 'filter', 'filter' => function ($value) {
                return Filter::cleanText($value);
            }],
            [['username', 'email', 'password'], 'filter', 'filter' => function ($value) {
                return Filter::cleanAllInnerSpaces($value);
            }],
            ['username', 'filter', 'filter' => function ($value) {
                return Filter::removeAtFromNickname($value);
            }],
            /* Validation rules */
            ['status_id', 'default', 'value' => self::STATUS_PENDING],
            ['status_id', 'in', 'range' => [self::STATUS_BLOCKED, self::STATUS_PENDING]],
            [['avatar', 'bio'], 'default', 'value' => null],
            ['password', 'string', 'min' => 6, 'max' => 20],
            ['email', 'email'],
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
            'status_id' => Yii::t('app', 'Status Id'),
            'f_name' => Yii::t('app', 'First Name'),
            'l_name' => Yii::t('app', 'Last Name'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
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
        $fields = ['id', 'f_name', 'l_name', 'username', 'email', 'phone'];
        if ($this->bio) {
            $fields[] = 'bio';
        }
        if ($this->dob) {
            $fields[] = 'dob';
        }
        return $fields;
    }

    /**
     * @param int|string $id
     * @return null|IdentityInterface|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
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
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
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
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->token = Yii::$app->security->generateRandomString();
    }
}