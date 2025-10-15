<?php

namespace common\modules\officialaccount\services;

use common\helpers\ErrorsHelper;
use common\helpers\FileHelper;
use common\helpers\loggingHelper;
use common\helpers\ResultHelper;
use common\helpers\StringHelper;
use common\models\User;
use common\modules\officialaccount\models\enums\WechatMaterialTypesEnum;
use common\modules\officialaccount\models\OfficialaccountWechatMedia;
use common\services\BaseService;
use EasyWeChat\Kernel\Messages\Article;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\Pagination;
use yii\db\StaleObjectException;

/**
 * 素材操作
 */
class WechatMaterialService extends BaseService
{

    private int $bloc_id;

    public function __construct()
    {
        parent::__construct();
        $user_id = Yii::$app->user->identity->user_id ?? 0;
        $this->bloc_id = User::find()->where(['id' => $user_id])->select(['bloc_id'])->scalar();;
    }


    /**
     * 获取永久素材
     * @param $type
     * @param int $page
     * @param int $pageSize
     * @return array
     * @throws InvalidConfigException
     */
    static function getMaterial($type, int $page = 1, int $pageSize = 10): array
    {
//        $type 素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
        if (!in_array($type,WechatMaterialTypesEnum::getConstantsByName())){
            return ResultHelper::json(400,'素材类型错误');
        }
        $wechat = OfficialaccountService::getWechatApp(1);

        $offset = ($page - 1) * $pageSize;
        return $wechat->material->list($type, $offset, $pageSize);
    }

    /**
     * 删除永久素材
     * @param $mediaId
     * @return array
     * @throws InvalidConfigException
     */
    static function deleteMaterial($mediaId):array
    {
        $wechat = OfficialaccountService::getWechatApp(1);
        self::deleteLocalMaterial($mediaId);
        return $wechat->material->delete($mediaId);
    }


    /**
     * 删除本地永久素材
     * @param $mediaId
     * @return array
     */
    static function deleteLocalMaterial($mediaId): array
    {
        $query = OfficialaccountWechatMedia::find()->where(['material'=>$mediaId])->one();
        try {
            if ($query->delete()) {
                return ResultHelper::json(200, '删除成功');
            } else {

                return ResultHelper::json(200, '删除失败');
            }
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        } catch (Throwable $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }
    }

    /**
     * 保存素材
     * @return void
     */
    static function saveMaterial($filename,$result,$type,$url,$local_url,$material,$news_update_time): bool
    {
        $OfficialaccountWechatMedia = new OfficialaccountWechatMedia();
        $OfficialaccountWechatMedia->load([
            'filename' => $filename,
            'result' => $result,
            'type' => $type,
            'url' => $url,
            'local_url' => $local_url,
            'material' => $material,
            'news_update_time' => $news_update_time
        ]);

        return $OfficialaccountWechatMedia->save();
    }

    /**
     * 素材同步
     * @param $type
     * @param int $page
     * @return array
     * @throws InvalidConfigException
     */
    public static function synchronous($type, int &$page = 1): array
    {
        $pageSize = 20;
        $list =  self::getMaterial($type, $page,$pageSize);
        $OfficialaccountWechatMedia = new OfficialaccountWechatMedia();
        $media_id = $OfficialaccountWechatMedia->find()->select('media_id')->distinct('media_id')->column();
        loggingHelper::writeLog('officialaccount','WechatMaterialService/synchronous','素材同步',[
            'page'=>$page,
            'media_id'=>$media_id,
            'pageSize'=>$pageSize,
            'item_count'=>$list['item_count']
        ]);
        if ($list['item_count']>0){
            foreach ($list['item'] as $item) {
                if (!in_array($item['media_id'],$media_id)){
                    $_OfficialaccountWechatMedia = clone  $OfficialaccountWechatMedia;
                    $_OfficialaccountWechatMedia->setAttributes([
                        'filename' => $item['name'],
                        'type' => $type,
                        'url' => $item['url'],
                        'media_id' => $item['media_id'],
                        'tags'=>json_encode($item['tags']),
                        'status'=>1,
                        'local_url' => self::saveRemoteFileToLocal($item['url'],$item['name']),
                        'news_update_time' => date('Y-m-d H:i:s',$item['update_time'])
                    ]);

                    if (!$_OfficialaccountWechatMedia->save()){
                        $msg = ErrorsHelper::getModelError($_OfficialaccountWechatMedia);
                        return ResultHelper::json(400, $msg);
                    }
                }

            }
            $page++;
            self::synchronous($type, $page);
        }

        return ResultHelper::json(200, '获取成功');

    }

    /**
     * 获取文件后缀
     * @param $filename
     * @return string
     * @throws \Exception
     */
    static function getFileExtension($filename): string
    {
        // 使用内置函数 pathinfo 获取文件信息
        $fileInfo = pathinfo($filename);
        $extension = $fileInfo['extension'] ?? '';
        // 返回文件后缀
        return  StringHelper::uuid('random').time().'.'.$extension;
    }



    /**
     * 下载远程文件并保存到本地
     *
     * @param string $remoteUrl 远程文件地址
     * @return string 本地路径
     */
    static function saveRemoteFileToLocal(string $remoteUrl,$fileName): string
    {
        $localPath = Yii::getAlias('@attachment'.date('/Y/m/d/'));

        FileHelper::mkdirs($localPath);

        // 初始化 cURL
        $ch = curl_init();

        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_URL, $remoteUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 如果你需要验证 SSL 证书，请设置为 true
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 以二进制模式传输数据

        // 执行 cURL 请求，获取文件内容
        $fileContent = curl_exec($ch);

        // 检查是否有错误发生
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return false;
        }

        // 关闭 cURL 资源
        curl_close($ch);
        $fileName = self::getFileExtension($fileName);
        // 将获取到的文件内容保存到本地文件
        $result = file_put_contents($localPath.$fileName, $fileContent);

        // 检查文件是否成功保存
        if ($result !== false) {
            return date('/Y/m/d/').$fileName;
        } else {
            return '';
            // 还可以添加额外的错误处理逻辑，例如检查目录权限、磁盘空间等
        }
    }

    /**
     * 上传素材
     * @param mixed $id
     * @return array
     * @throws InvalidConfigException
     */
    public static function upWxMaterial(mixed $id)
    {
        $detail = OfficialaccountWechatMedia::find()->where(['id'=>$id])->one();

        $wechat = OfficialaccountService::getWechatApp(1);
        $Material = $detail->toArray();
        $path = Yii::getAlias("@attachment/".$Material['local_url']);
        switch ($detail->type){
            case 'image':
                $result = $wechat->material->uploadImage($path);
                // {
                //    "media_id":MEDIA_ID,
                //    "url":URL
                // }
            break;
            case 'voice':
                $result = $wechat->material->uploadVoice($path);
                // {
                //    "media_id":MEDIA_ID,
                // }
                break;
            case 'video':
                $result = $wechat->material->uploadVideo($path, "视频标题", "视频描述");
                // {
                //    "media_id":MEDIA_ID,
                // }
                break;
            case    'thumb':
                $result = $wechat->material->uploadThumb($path);
                // {
                //    "media_id":MEDIA_ID,
                // }

                // 上传单篇图文
                $mediaId = 0;
                $article = new Article([
                    'title' => 'xxx',
                    'thumb_media_id' => $mediaId,
                    //...
                ]);
                $wechat->material->uploadArticle($article);

                // 或者多篇图文
                $wechat->material->uploadArticle([$article]);
                break;
            default:
                $result = $wechat->material->uploadImage($path);
                break;

        }
        try {
            $detail->status = 1;
            $detail->news_update_time = date('Y-m-d H:i:s',time());
            $detail->media_id = $result['media_id'];
            $detail->url = $result['url']??'';
            $detail->update();
        } catch (StaleObjectException $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        } catch (Throwable $e) {
            return ResultHelper::json(400, $e->getMessage(), (array)$e);
        }
        $resource = $wechat->material->get($result['media_id']);
        loggingHelper::writeLog('officialaccount','upWxMaterial','素材上传结果',[
            'detail'=>$detail->toArray(),
            'result'=>$result,
            'resource'=>$resource
        ]);
//        $detail->media_id = $result['media_id'];
        return ResultHelper::json(200, '获取成功');
    }

    /**
     * 获取素材计数
     * @throws InvalidConfigException
     */
    public static function MaterialCount(): array
    {
        $wechat = OfficialaccountService::getWechatApp(1);
        if (!is_object($wechat)){
            return ResultHelper::json(400,'请检查公众号配置是否正确');
        }
        return $wechat->material->stats();
    }


    /**
     * 获取本地库永久素材
     * @return array
     */
    function getLocalMaterial($type,$page = 1, $pageSize = 10)
    {
        //        $type 素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
        if (!in_array($type,WechatMaterialTypesEnum::listData())){
            return ResultHelper::json(400,'素材类型错误');
        }
        $query = OfficialaccountWechatMedia::find()->where(['bloc_id'=>$this->bloc_id]);
        $count = $query->count();
        // 使用总数来创建一个分页对象
        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => $pageSize,
            'page' => $page,
            'pageParam' => 'page',
        ]);
        return $query->offset($pagination->offset)->limit($pagination->limit)->asArray()->all();
    }
}