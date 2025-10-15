<?php

namespace common\modules\officialaccount\services;

use common\helpers\ResultHelper;
use common\helpers\StringHelper;
use common\models\User;
use common\modules\officialaccount\models\enums\WechatMenuTypeEnum;
use common\modules\officialaccount\models\enums\WechatMsgTypeEnum;
use common\modules\officialaccount\models\OfficialaccountWechatMenu;
use common\services\BaseService;
use Exception;
use Yii;

class WechatMenuService extends BaseService
{

    private int $bloc_id;

    public function __construct()
    {
        parent::__construct();
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $this->bloc_id = User::find()->where(['id' => $user_id])->select(['bloc_id'])->scalar();;
    }



    /**
     * 获取本地公众号菜单
     * @return array
     * @throws Exception
     */
    function getWechatLocalMenu(): array
    {
        $menus = OfficialaccountWechatMenu::find()->where(['bloc_id' => $this->bloc_id])->with(['child as sub_button'])->asArray()->all();
        return $menus;
    }


    /**
     * 获取公众号菜单
     * @return array
     * @throws Exception
     */
    static function getWechatMenu(): array
    {
        $wechat = Yii::$app->wechatApp->getApp(1);
        $current = $wechat->menu->current();
        $menuTree = $current['selfmenu_info']['button'];
        $lists = [];
        foreach ($menuTree as $item) {
            $item['menuLevel'] = '1';
            $item['id'] = StringHelper::uuid('random');
            if (array_key_exists('type', $item)) {
                $item['menuType'] = self::getMenuType($item['type']);
            }

            foreach ($item['sub_button']['list'] as $sub_button) {
                $sub_button['id'] = StringHelper::uuid('random');
                $sub_button['pid'] = $item['id'];
                $sub_button['menuLevel'] = '2';
                $sub_button['menuType'] = self::getMenuType($sub_button['type']);

                $item['children'][] = $sub_button;
            }
            unset($item['sub_button']);
            $lists[] = $item;
        }

        return $lists;
    }

    static function getMenuType($type): mixed
    {
        $list = WechatMsgTypeEnum::getConstantsByName();
        if (in_array($type, WechatMenuTypeEnum::getConstansLists())) {
            return 1;
        } else {
            return key_exists($type, $list) ? $list[$type] : 1;
        }
    }

    public static function createMenu(array $menu): array
    {
        $buttons = [
            [
                "type" => "click",
                "name" => "今日歌曲",
                "key" => "V1001_TODAY_MUSIC"
            ],
            [
                "name" => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "搜索",
                        "url" => "http://www.soso.com/"
                    ],
                    [
                        "type" => "view",
                        "name" => "视频",
                        "url" => "http://v.qq.com/"
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我们",
                        "key" => "V1001_GOOD"
                    ],
                ],
            ],
        ];
        $wechat = Yii::$app->wechat->app;
        $wechat->menu->create($menu);
        return ResultHelper::json(200,'保存成功');
    }

    function editMenu()
    {
        
    }
}