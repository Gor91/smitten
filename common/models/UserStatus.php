<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class UserStatus extends ActiveRecord
{
    /** @var $PENDING */
    const PENDING = 1;

    /** @var $ACTIVE */
    const ACTIVE = 2;

    /** @var $BLOCKED */
    const BLOCKED = 3;


    /**
     * @return string
     */
    public static function tableName()
    {
        return 'user_status';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['label'], 'required'],
            [['label'], 'string', 'max' => 50]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label' => Yii::t('app', 'Label')
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        return false;
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['statusId' => 'id']);
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            self::PENDING => 'PENDING',
            self::ACTIVE => 'ACTIVE',
            self::BLOCKED => 'BLOCKED',
            self::DELETED => 'DELETED'
        ];
    }

}