<?php

namespace api\controllers;

use common\helpers\ResultHelper;
use Yii;

class HotelController extends BaseController
{
    public array $authOptional = ['rooms', 'meetings', 'articles'];

    public function actionRooms(): array
    {
        $db = Yii::$app->db;
        $blocId = (int) Yii::$app->request->get('bloc_id', 1);
        $limit = (int) Yii::$app->request->get('limit', 6);

        $rows = $db->createCommand("
            SELECT id, title,
                   COALESCE(NULLIF(thumb, ''), '') AS thumb,
                   COALESCE(NULLIF(sales, ''), '') AS summary
            FROM dd_diandi_place_room_type
            WHERE bloc_id = :bloc_id
            ORDER BY displayorder DESC, id DESC
            LIMIT :limit
        ")
            ->bindValue(':bloc_id', $blocId)
            ->bindValue(':limit', $limit)
            ->queryAll();

        return ResultHelper::json(200, '获取成功', [
            'items' => array_map(static function ($row) {
                return [
                    'id' => (int) $row['id'],
                    'title' => $row['title'],
                    'summary' => $row['summary'],
                    'thumb' => $row['thumb']
                ];
            }, $rows)
        ]);
    }

    public function actionMeetings(): array
    {
        $db = Yii::$app->db;
        $blocId = (int) Yii::$app->request->get('bloc_id', 1);
        $limit = (int) Yii::$app->request->get('limit', 3);

        $rows = $db->createCommand("
            SELECT id, title,
                   COALESCE(NULLIF(remark, ''), '') AS remark,
                   COALESCE(NULLIF(thumb, ''), '') AS thumb
            FROM dd_diandi_place_room
            WHERE bloc_id = :bloc_id
            ORDER BY displayorder DESC, id DESC
            LIMIT :limit
        ")
            ->bindValue(':bloc_id', $blocId)
            ->bindValue(':limit', $limit)
            ->queryAll();

        return ResultHelper::json(200, '获取成功', [
            'items' => array_map(static function ($row) {
                return [
                    'id' => (int) $row['id'],
                    'title' => $row['title'],
                    'remark' => $row['remark'],
                    'thumb' => $row['thumb']
                ];
            }, $rows)
        ]);
    }

    public function actionArticles(): array
    {
        $db = Yii::$app->db;
        $blocId = (int) Yii::$app->request->get('bloc_id', 1);
        $limit = (int) Yii::$app->request->get('limit', 4);

        $rows = $db->createCommand("
            SELECT a.id, a.title,
                   COALESCE(NULLIF(a.description, ''), '') AS summary,
                   COALESCE(NULLIF(a.thumb, ''), '') AS thumb,
                   a.create_time,
                   c.name AS categoryName
            FROM dd_diandi_website_article a
            LEFT JOIN dd_diandi_website_category c ON c.id = a.pcate
            WHERE a.bloc_id = :bloc_id
            ORDER BY a.is_top DESC, a.id DESC
            LIMIT :limit
        ")
            ->bindValue(':bloc_id', $blocId)
            ->bindValue(':limit', $limit)
            ->queryAll();

        return ResultHelper::json(200, '获取成功', [
            'items' => array_map(static function ($row) {
                return [
                    'id' => (int) $row['id'],
                    'title' => $row['title'],
                    'summary' => $row['summary'],
                    'thumb' => $row['thumb'],
                    'categoryName' => $row['categoryName'] ?? '',
                    'createdAt' => (string) $row['create_time']
                ];
            }, $rows)
        ]);
    }
}
