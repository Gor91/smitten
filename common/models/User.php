<?php
/**
 * User
 *
 * @package    common
 * @subpackage models
 * @author     SIXELIT <sixelit.com>
 */

namespace common\models;

use DateTime;
use Yii;
use yii\web\IdentityInterface;
use common\components\Filter;
use common\models\DataAccess\UserQuery;
use frontend\modules\v1\models\Friends;

/**
 * User model
 *
 * @property int $id
 * @property int $statusId
 * @property string $lang
 * @property string $fName
 * @property string $lName
 * @property string $username
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
class User extends BaseModel implements IdentityInterface
{
    /** @var $SCENARIO_CREATE */
    const SCENARIO_CREATE = 'create';

    /** @var $SCENARIO_LOGIN */
    const SCENARIO_LOGIN = 'login';

    /** @var $SCENARIO_CHANGE_PROFILE */
    const SCENARIO_CHANGE_PROFILE = 'change_profile';

    /** @var $PATH_AVA                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          ````TARS */
    const PATH_AVATARS = 'avatars';

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
    public function rules()
    {
        return [
            /* Filters */
            [['fName', 'lName', 'username', 'bio', 'password','phone'], 'filter', 'filter' => function ($value) {
                return Filter::cleanText($value);
            }],
            ['username', 'filter', 'filter' => function ($value) {
                return Filter::removeAtFromNickname($value);
            }],
            /* Validation rules */
            ['statusId', 'default', 'value' => UserStatus::PENDING],
            ['statusId', 'in', 'range' => [UserStatus::PENDING, UserStatus::ACTIVE, UserStatus::BLOCKED]],
            [['avatar', 'bio'], 'default', 'value' => null],
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
            'lang' => Yii::t('app', 'Language Id'),
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
        $fields = ['id', 'fName', 'lName', 'username', 'gender', 'lang', 'phone'];

        if ($this->bio) {
            $fields[] = 'bio';
        }

        if ($this->dob) {
            $fields['age'] = function () {
                return self::calculateAge($this->dob);
            };
        }

        if ($this->avatar) {
            $fields['avatar'] = function () {
                return Yii::getAlias(sprintf('%s/%s', Yii::$app->params['url.storage'], $this->avatar));
            };
        }

        $fields[] = 'location';

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
     * @return \yii\db\ActiveQuery
     */
    public function getFromRequestFriend()
    {
        return $this->hasMany(Friends::className(), ['from' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToRequestFriend()
    {
        return $this->hasMany(Friends::className(), ['to' => 'id']);
    }

    /**
     * @return bool
     */
    public function isBirthDay()
    {
        return date('m-d') == date('m-d', strtotime($this->dob));
    }

    /**
     * @param $dob
     * @return int
     */
    public static function calculateAge($dob)
    {
        try {
            $birthDate = new DateTime($dob);
            $today = new DateTime('today');

            return $birthDate->diff($today)->y;
        } catch (\Exception $e) {
            Yii::error($e, 'app');
        }

        return 0;
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
     * @return null|IdentityInterface|static
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    /**
     * @param $username
     * @return null|static
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'statusId' => UserStatus::ACTIVE]);
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