<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-04-09 11:20:54
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2023-04-22 23:35:41
 */

namespace common\components\FileUpload;

use Alioss\Core\MimeTypes;
use common\helpers\ArrayHelper;
use common\helpers\FileHelper as HelpersFileHelper;
use common\helpers\ImageHelper;
use common\helpers\ResultHelper;
use Exception;
use Faker\Provider\Uuid;
use Local\LocalCor;
use Local\LocalException;
use Yii;
use yii\base\Model;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * 文件上传处理.
 */
class Upload extends Model
{
    public mixed  $file;
    private array $_appendRules;

    public function init(): void
    {
        parent::init();
        $extensions         = Yii::$app->params['webuploader']['baseConfig']['accept']['extensions'];
        $this->_appendRules = [
            [['file'], 'file', 'extensions' => $extensions],
        ];
    }

    public function rules(): array
    {
        $baseRules = [];
        return array_merge($baseRules, $this->_appendRules);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function upImage(): array
    {
        $model = new static();
        self::resetFileBasedOnRealImageType();
        $model->file = UploadedFile::getInstanceByName('file');

        if (!$model->file) {
            return false;
        }

        $fileName = Uuid::uuid() . '.' . $model->file->extension;
        if (!ImageHelper::isImg($fileName)) {
            return ResultHelper::json(400, '请检查图片格式', [
                'fileName' => $fileName
            ]);
        }
        if ($model->validate()) {
            $relativePath = Yii::$app->params['imageUploadRelativePath'];
            $successPath  = Yii::$app->params['imageUploadSuccessPath'];
            //$model->file->baseName

            if (!is_dir($relativePath)) {
                HelpersFileHelper::mkdirs($relativePath);
            }
            $Res      = $model->file->saveAs($relativePath . $fileName);
            $cloudOss = [];
            if ($Res) {
                // 云上传
                $Attachment = new OssUpload();
                $cloudOss   = $Attachment->file_remote_upload($successPath . $fileName);
                if ($cloudOss['status'] == 200) {
                    $storage = $cloudOss['data']['storage'];
                } else {
                    $storage = 'local';
                }
                ImageHelper::uploadDb($fileName, $model->file->size, $model->file->type, $model->file->extension, $successPath . $fileName, 0, $storage);
            }

            return ResultHelper::json(200, '上传成功', [
                'cloudOss'   => $cloudOss,
                'url'        => ImageHelper::tomedia($successPath . $fileName),
                'attachment' => $successPath . $fileName,
            ]);
        } else {
            $errors = $model->errors;
            return ResultHelper::json(400, current($errors)[0], $errors);
        }
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public function upVideo(): array
    {
        // 获取上传的文件
        $file = UploadedFile::getInstanceByName('video');

        if (!$file) {
            throw new BadRequestHttpException('未找到上传的视频文件');
        }

        $allowedExtensions = ['mp4', 'avi', 'mov', 'mkv'];
        if (!in_array(strtolower($file->extension), $allowedExtensions)) {
            throw new BadRequestHttpException('不支持的视频格式。支持的格式: ' . implode(', ', $allowedExtensions));
        }

        // 验证文件大小（最大50MB）
        $maxSize = 50 * 1024 * 1024;
        if ($file->size > $maxSize) {
            throw new BadRequestHttpException('视频文件过大，最大支持50MB');
        }


        $uploadDir = Yii::$app->params['videoUploadRelativePath'];
        if (!is_dir($uploadDir)) {
            HelpersFileHelper::mkdirs($uploadDir);
        }

        $fileName = uniqid('video_') . '.' . $file->extension;
        $filePath = $uploadDir . '/' . $fileName;

        if (!$file->saveAs($filePath)) {
            throw new ServerErrorHttpException('保存视频文件失败');
        } else {
            return ResultHelper::json(200, '上传成功', [
                'originalName' => $file->baseName . '.' . $file->extension,
                'size'         => $file->size,
                'mimeType'     => $file->type,
                'url'          => $uploadDir . '/' . $fileName,
            ]);
        }
    }

    public static function resetFileBasedOnRealImageType()
    {
        if (isset($_FILES['file'])) {
            $file     = $_FILES['file'];
            $tmp_name = $file['tmp_name'];

            // 检查是否是图像类型
            $finfo     = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $tmp_name);
            finfo_close($finfo);

            $imageMimeTypes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
            ];

            if (!array_key_exists($mime_type, $imageMimeTypes)) {
                throw new Exception('仅支持 JPEG、PNG 和 GIF 格式的图片');
            }

            $correctExtension = $imageMimeTypes[$mime_type];
            $newFileName      = pathinfo($file['name'], PATHINFO_FILENAME) . '.' . $correctExtension;

            // 重置 $_FILES
            $_FILES['file'] = [
                'name'     => $newFileName,
                'type'     => $mime_type,
                'tmp_name' => $tmp_name,
                'error'    => $file['error'],
                'size'     => $file['size'],
            ];
        }
    }


    /**
     * 文件上传
     * ```
     *  $model = new UploadValidate($config_name);
     *  $result = CommonHelper::myUpload($model, $field, 'invoice');
     * ```.
     *
     * @param object $model \common\models\UploadValidate 验证上传文件
     * @param int $is_chunk
     * @param int $chunk_partSize
     * @param int $chunk_partCount
     * @param int $chunk_partIndex
     * @param string $md5
     * @param string $chunk_md5
     * @return array
     */
    public static function upFile(object $model, int $is_chunk = 0, int $chunk_partSize = 5, int $chunk_partCount = 0, int $chunk_partIndex = 0, string $md5 = '', string $chunk_md5 = '', string $assign_path = ''): array
    {
        //文件上传存放的目录
        $upload_path = Yii::getAlias('@public/attachment/');

        if (!empty($assign_path)) {
            $upload_path = $upload_path . $assign_path . '/';
        }

        $file        = UploadedFile::getInstanceByName('file');
        $model->file = $file;
        // 云上传
        $Attachment = new OssUpload();

        if (!empty($is_chunk)) {
            $uploadSuccessPath = ArrayHelper::objectToarray($file);
            // 缓存目录
            $fileName = pathinfo($uploadSuccessPath['tempName']);
            // 目录分割
            $basePath = date('Ym/d');
            //缓存目录
            $uploadTempDir = str_replace('//', '/', $upload_path . $basePath);
            // 本地上传
            $file = str_replace('//', '/', $uploadTempDir . $fileName['basename']);
            $Res  = HelpersFileHelper::file_move($uploadSuccessPath['tempName'], $file);
            if ($Res) {
                return [
                    'status'  => 0,
                    'message' => '上传成功',
                    'data'    => [
                        // 分片文件路径
                        'file'            => $file,
                        // 分片存放目录
                        'temDir'          => $uploadTempDir,
                        // 分片大小
                        'chunk_partSize'  => (int) $chunk_partSize,
                        // 分片总数
                        'chunk_partCount' => (int) $chunk_partCount,
                        // 分片序号
                        'chunk_partIndex' => (int) $chunk_partIndex,
                        'md5'             => $md5,
                        'chunk_md5'       => $chunk_md5,
                    ],
                ];
            } else {
                return [
                    'status'  => 1,
                    'message' => '上传失败',
                    'data'    => [
                        // 分片文件路径
                        'file'            => $file,
                        // 分片存放目录
                        'temDir'          => $uploadTempDir,
                        // 分片大小
                        'chunk_partSize'  => (int) $chunk_partSize,
                        // 分片总数
                        'chunk_partCount' => (int) $chunk_partCount,
                        // 分片序号
                        'chunk_partIndex' => (int) $chunk_partIndex,
                        'md5'             => $md5,
                        'chunk_md5'       => $chunk_md5,
                    ],
                ];
            }
        }

        $extension = $model->file->extension;

        if ($model->validate()) {
            //生成文件名
            $rand_name = rand(1000, 9999);
            $fileName  = $rand_name . '_' . $model->file->baseName . '.' . $model->file->extension;
            $save_dir  = $upload_path . date('Ym/d/');
            if (!is_dir($save_dir)) {
                HelpersFileHelper::mkdirs($save_dir);
                chmod($save_dir, 0777);
            }
            $uploadSuccessPath = date('Ym/d/') . $fileName;
            $filePath          = $upload_path . $uploadSuccessPath;
            $Res               = $model->file->saveAs($filePath);

            if ($Res) {
                $result['name']       = $model->file->baseName . '.' . $model->file->extension;
                $result['extension']  = $model->file->extension;
                $result['attachment'] = $uploadSuccessPath;

                // 云上传
                $cloudOss = $Attachment->file_remote_upload($uploadSuccessPath);

                if ($cloudOss['status'] == 200) {
                    $storage = $cloudOss['data']['storage'];
                } else {
                    $storage = 'local';
                }

                ImageHelper::uploadDb($fileName, $model->file->size, $model->file->type, $model->file->extension, $uploadSuccessPath, 0, $storage);
                $result['cloudOss'] = $cloudOss;

                $result['url'] = ImageHelper::tomedia($uploadSuccessPath);
                $pathinfo      = pathinfo($uploadSuccessPath);

                $result['file'] = [
                    'name'     => $pathinfo['basename'],
                    'type'     => $result['extension'],
                    'size'     => (int) $model->file->size,
                    'url'      => ImageHelper::tomedia($uploadSuccessPath),
                    'partSize' => $chunk_partSize,
                ];
                return [
                    'status'   => 0,
                    'cloudOss' => $cloudOss,
                    'message'  => '上传成功',
                    'data'     => $result,
                ];
            } else {
                return ResultHelper::json(400, '上传失败');
            }
        } else {
            //上传失败记录日志
            $logPath = Yii::getAlias('@runtime/log/upload/' . date('YmdHis') . '.log');
            HelpersFileHelper::writeLog($logPath, Json::encode($model->errors));

            return [
                'status'  => 1,
                'file'    => $model->file,
                'message' => Json::encode($model->errors),
            ];
        }
    }

    public static function mergeFile($file_name, $file_type, $file_size, $file_parts, $chunk_partSize, $auto_delete_local = true): array
    {
        set_time_limit(0);

        // 本地文件做合并处理
        $LocalCor = new LocalCor($file_name, $file_size, $file_type);

        $upload_path = Yii::getAlias('@public/attachment/' . date('Ym/d'));

        try {
            $baseFile = $LocalCor->mergeParts($file_parts, $upload_path);
            $storage  = 'local';
            $fileInfo = explode('attachment/', $baseFile);

            $file     = $fileInfo[1];
            $pathinfo = pathinfo($baseFile);
            $filesize = filesize($baseFile);

            $Attachment = new OssUpload();
            $cloudOss   = $Attachment->file_remote_upload_util($baseFile, $chunk_partSize, $auto_delete_local);
            if ($cloudOss['status'] !== 200) {
                throw new \Local\LocalException($cloudOss['message']);
            } else {
                $storage = 'alioss';
            }
            $Mimetype = MimeTypes::getMimetype($baseFile);
            ImageHelper::uploadDb($pathinfo['basename'], $filesize, $Mimetype, $pathinfo['extension'], $file, 0, $storage);
        } catch (\Local\LocalException $e) {
            throw new LocalException($e->getMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), '405');
        }

        return [
            'code'    => 0,
            'message' => '上传成功',
            'data'    => [
                'file'       => [
                    'name'     => $file_name,
                    'type'     => $file_type,
                    'size'     => (int) $file_size,
                    'url'      => ImageHelper::tomedia($file),
                    'partSize' => (int) $chunk_partSize,
                ],
                'url'        => ImageHelper::tomedia($file),
                'attachment' => $file,
                'cloudOss'   => $cloudOss,
            ],
        ];
    }

    public static function upFolder($name, $assign_path)
    {
        $dir = Yii::getAlias('@public/attachment/');

        if (!empty($assign_path)) {
            $dir = $dir . $assign_path . '/';
        }
        $dir = $dir . $name;
        if (is_dir($dir)) {
            return true;
        }

        if (mkdir($dir, 0777, false)) {
            return true;
        }
    }
}
