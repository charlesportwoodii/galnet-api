<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $uid
 * @property string $title
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $published_at
 * @property integer $published_at_native
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'title', 'content'], 'required'],
            [['content'], 'string'],
            [['created_at', 'updated_at', 'published_at', 'published_at_native'], 'integer'],
            [['uid'], 'string', 'max' => 60],
            [['title'], 'string', 'max' => 255],
            [['uid'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'title' => 'Title',
            'content' => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'published_at' => 'Published At',
            'published_at_native' => 'Published At Native',
        ];
    }
}
