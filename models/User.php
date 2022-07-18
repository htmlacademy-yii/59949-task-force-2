<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string $phone
 * @property string $telegram
 * @property string $description
 * @property string|null $birth_date
 * @property int|null $avatar_file_id
 * @property int $category_id
 * @property int $city_id
 * @property int|null $is_executor
 * @property int|null $is_busy
 * @property int|null $is_hidden_contacts
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Files $avatarFile
 * @property Categories $category
 * @property Cities $city
 * @property Responses[] $responses
 * @property Reviews[] $reviews
 * @property Reviews[] $reviews0
 * @property Tasks[] $tasks
 * @property Tasks[] $tasks0
 */
class User extends \yii\db\ActiveRecord
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
            [['email', 'name', 'password', 'phone', 'telegram', 'description', 'category_id', 'city_id'], 'required'],
            [['birth_date', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['avatar_file_id', 'category_id', 'city_id', 'is_executor', 'is_busy', 'is_hidden_contacts'], 'integer'],
            [['email'], 'string', 'max' => 128],
            [['name'], 'string', 'max' => 150],
            [['password'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 20],
            [['telegram'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 500],
            [['email'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            [['avatar_file_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::class, 'targetAttribute' => ['avatar_file_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Name',
            'password' => 'Password',
            'phone' => 'Phone',
            'telegram' => 'Telegram',
            'description' => 'Description',
            'birth_date' => 'Birth Date',
            'avatar_file_id' => 'Avatar File ID',
            'category_id' => 'Category ID',
            'city_id' => 'City ID',
            'is_executor' => 'Is Executor',
            'is_busy' => 'Is Busy',
            'is_hidden_contacts' => 'Is Hidden Contacts',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[AvatarFile]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvatarFile()
    {
        return $this->hasOne(File::class, ['id' => 'avatar_file_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponses()
    {
        return $this->hasMany(Response::class, ['executor_id' => 'id'])->inverseOf('user');
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::class, ['customer_id' => 'id'])->inverseOf('user');
    }

    /**
     * Gets query for [[Reviews0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews0()
    {
        return $this->hasMany(Review::class, ['executor_id' => 'id'])->inverseOf('user');
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id'])->inverseOf('user');
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id'])->inverseOf('user');
    }
}
