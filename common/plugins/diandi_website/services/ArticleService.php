<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-09-23 09:19:03
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-08-01 13:30:30
 */

namespace common\plugins\diandi_website\services;

use common\helpers\ArrayHelper;
use common\helpers\ImageHelper;
use common\plugins\diandi_website\models\searchs\WebsiteArticle;
use common\plugins\diandi_website\models\searchs\WebsiteArticleCategory;
use common\plugins\diandi_website\models\WebsitePageConfig;
use common\services\BaseService;
use Yii;

class ArticleService extends BaseService
{
    public static function getCate($pcate, $type)
    {
        $where = [];
        $where['bloc_id'] = Yii::$app->params['bloc_id'];
        if ($pcate) {
            $where['pcate'] = $pcate;
        }

        if ($type) {
            $where['type'] = $type;
        }

        $list = WebsiteArticleCategory::find()->where($where)->with(['article'])->asArray()->all();
        foreach ($list as $key => &$value) {
            $value['thumb'] = ImageHelper::tomedia($value['thumb']);
        }

        return ArrayHelper::itemsMerge($list, 0, 'id', 'pcate');
    }

    public static function getList($pageName = '', $keywords = '', $pcate = '', $ccate = '', $ishot = null)
    {
        $where = [];
        $where['bloc_id'] = Yii::$app->params['bloc_id'];
        $whereType = [];
        $page_id = WebsitePageConfig::find()->where(['type' => $pageName])->select(['id'])->scalar();
        if (!empty($page_id)) {
            $pcate = WebsiteArticleCategory::find()->where(['page_id'=>$page_id])->select(['id'])->scalar();
            $whereType['pcate'] = $pcate;
        }

        $whereLike = [];
        if (!empty($keywords)) {
            $whereLike = [
                'or',
                ['like', 'description', $keywords],
                ['like', 'title', $keywords],
            ];
        }

        if (!empty($pcate)) {
            $where['pcate'] = $pcate;
        }
        if (!empty($ccate)) {
            $where['ccate'] = $ccate;
        }
        if (!empty($ishot)) {
            $where['ishot'] = $ishot;
        }

        $list = WebsiteArticle::find()->where($where)->andWhere($whereType)->andWhere($whereLike)->orderBy('displayorder')->asArray()->all();

        foreach ($list as $key => &$value) {
            $value['thumb'] = ImageHelper::tomedia($value['thumb']);
            $value['create_time'] = date('Y-m-d H:i', $value['create_time']);
        }

        return $list;
    }

    public static function getDetail($id)
    {
        $where = [];
        $where['bloc_id'] = Yii::$app->params['bloc_id'];

        $where['id'] = $id;

        $detail = WebsiteArticle::find()->where($where)->asArray()->one();
        $detail['thumb'] = ImageHelper::tomedia($detail['thumb']);
        $detail['content'] = stripslashes($detail['content']);
        $detail['create_time'] = date('Y-m-d H:i', $detail['create_time']);

        return $detail;
    }
}
