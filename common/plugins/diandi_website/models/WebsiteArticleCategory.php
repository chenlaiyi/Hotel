<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-09-16 11:51:26
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-08-01 13:30:15
 */

namespace common\plugins\diandi_website\models;

/**
 * This is the model class for table "{{%diandi_website_article_category}}".
 *
 * @public int      $id
 * @public string   $title
 * @public int      $displayorder
 * @public int|null $pcate
 * @public string   $type
 */
class WebsiteArticleCategory extends \common\components\ActiveRecord\YiiActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function  tableName(): string
    {
        return '{{%diandi_website_article_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'displayorder', 'page_id'], 'required'],
            [['page_id','displayorder', 'pcate', 'bloc_id', 'store_id', 'create_time', 'update_time'], 'integer'],
            [['title'], 'string', 'max' => 30],
            [['thumb'], 'string', 'max' => 255],
        ];
    }

    /**
     * 行为.
     */
    public function behaviors(): array
    {
        /*自动添加创建和修改时间*/
        return [
            [
                'class' => \common\behaviors\SaveBehavior::class,
                'updatedAttribute' => 'update_time',
                'createdAttribute' => 'create_time',
            ],
        ];
    }

    public function getArticle()
    {
        return $this->hasMany(WebsiteArticle::class, ['pcate' => 'id'])->limit(3);
    }

    function getPage()
    {
        return $this->hasOne(WebsitePageConfig::class, ['id' => 'page_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'displayorder' => 'Displayorder',
            'pcate' => 'Pcate',
            'page_id' => '页面标记',
            'bloc_id' => 'bloc_id',
            'store_id' => 'store_id',
        ];
    }
}
