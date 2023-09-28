<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string|null $username
 * @property string $fullname
 * @property string $house
 * @property string $words
 * @property string $region
 * @property string $email
 * @property string|null $password
 * @property string|null $authKey
 * @property string|null $accessToken
 * @property string $role
 *
 * @property Profiles[] $profiles
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username is already taken.'],
            [['username', 'role', 'password'], 'string', 'max' => 32],
            [['authKey', 'accessToken'], 'string', 'max' => 128],
            [['username', 'fullname', 'house', 'words', 'region', 'email', 'password', 'role'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'fullname' => 'First Name',
            'house' => 'House',
            'words' => 'Words',
            'region' => 'Region',
            'email' => 'Email',
            'password' => 'Password',
            // 'authKey' => 'Auth Key',
            // 'accessToken' => 'Access Token',
            'role' => 'Role',
        ];
    }

    /**
     * Gets query for [[Profiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profiles::class, ['user_id' => 'id']);
    }
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return $this->accessToken;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        // foreach (self::$users as $user) {
        //     if (strcasecmp($user['username'], $username) === 0) {
        //         return new static($user);
        //     }
        // }

        // return null;

        return self::findOne(['username'=>$username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public function generateAuthKey()
    {
        return $this->authKey = \Yii::$app->security->generateRandomString();
    }
}
