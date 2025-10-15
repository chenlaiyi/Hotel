<?php

/**
 * @Author: Wang Chunsheng 2192138785@qq.com
 * @Date:   2020-03-09 01:37:15
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2020-09-08 08:36:59
 */



$config = [
    'components' => [
        'fans' => [
            'class' => 'common\modules\openWeixin\components\Fans'
        ],
        'OpenApp' => [
            'class' => 'common\modules\openWeixin\components\OpenApp'
        ],
        'WxappClient' => [
            'class' => 'common\modules\openWeixin\components\WxappClient'
        ],
    ],
    'params' => [
        // token有效期是否验证 默认不验证
        'user.accessTokenValidity' => true,
        // token有效期 默认 2 小时
        'user.accessTokenExpire' => 2 * 60 * 60,
        // 'user.accessTokenExpire' => 60, //1分钟
        // refresh token有效期是否验证 默认开启验证
        'user.refreshTokenValidity' => true,
        // refresh token有效期 默认30天
        'user.refreshTokenExpire' => 30 * 24 * 60 * 60,
        // 签名验证默认关闭验证，如果开启需了解签名生成及验证
        'user.httpSignValidity' => false,
        // 签名授权公钥秘钥
        'user.httpSignAccount' => [
            'doormen' => 'e3de3825cfbf',
        ],
        // 微信开放平台配置 具体可参考EasyWechat
        'wechatOpenPlatformConfig' => [
             /**
             * 账号基本信息，请从微信公众平台/开放平台获取
             */
            'app_id'   => env('WECHATOPENPLATFORMCONFIG_APP_ID'),
            'secret'   => env('WECHATOPENPLATFORMCONFIG_SECRET'),
            'token'    => env('WECHATOPENPLATFORMCONFIG_TOKEN'),
            'aes_key'  => env('WECHATOPENPLATFORMCONFIG_AES_KEY'),

            /**
             * 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            * 使用自定义类名时，构造函数将会接收一个 `EasyWeChat\Kernel\Http\Response` 实例
            */
            'response_type' => 'array',

            /**
             * 日志配置
             *
             * level: 日志级别, 可选为：
             *         debug/info/notice/warning/error/critical/alert/emergency
             * path：日志文件位置(绝对路径!!!)，要求可写权限
             */
            'log' => [
                'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => Yii::getAlias('@runtime/openWeixin/'.date('Y/m/d').'/easywechat.log'),
                        'level' => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => Yii::getAlias('@runtime/openWeixin/'.date('Y/m/d').'/easywechat.log'),
                        'level' => 'info',
                    ],
                ],
            ],
            'cache' => Yii::$app->cache,
            // 添加这行配置
            /**
             * 接口请求相关配置，超时时间等，具体可用参数请参考：
             * http://docs.guzzlephp.org/en/stable/request-config.html
             *
             * - retries: 重试次数，默认 1，指定当 Http 请求失败时重试的次数。
             * - retry_delay: 重试延迟间隔（单位：ms），默认 500
             * - log_template: 指定 HTTP 日志模板，请参考：https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php
             */
            'Http' => [
                'max_retries' => 1,
                'retry_delay' => 500,
                'timeout' => 5.0,
                // 'base_uri' => 'https://api.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri
            ],

            /**
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址
             */
            'oauth' => [
                'scopes'   => ['snsapi_login'],
                'callback' =>  Yii::$app->request->input('redirect_url','/store/#/dashboard')
            ],
        ],

      

       
    ]

];

return $config;
