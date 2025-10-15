# 扩展模块rpc

## 服务

    特别注意，服务名称首字母必须大写

```php
    <?php
    
    namespace addons\diandi_hotel;
    
    use common\rpc\AddonsRpcService;
    
    class rpc extends AddonsRpcService
    {
        public function serviceName(): string
        {
            return 'Diandi_hotel'; //首字母必须大写
        }
    
    }
```

## 模块

```php
    <?php
    
    namespace addons\diandi_hotel\rpc;
    
    use addons\diandi_hotel\models\Rpc\ceshiRpcModel;
    use common\models\DdUser;
    use common\rpc\BaseAbstractServiceModule;
    //use common\rpc\model\DdUser;
    use EasySwoole\FastDb\FastDb;
    use Exception;
    
    class Device extends BaseAbstractServiceModule
    {
        public static string $moduleName = 'Device';
    
        function moduleName(): string
        {
            return 'Device';
        }
    
    
        function authOptional(): array
        {
            return [];
        }
    
        /**
         * @throws Exception
         */
        function ceshi()
        {
            $result = User::findRecord(11)->asArray();
    
            $this->response()->setMsg(['a'=>1221,'b'=>$result]);
        }
    
    
    }
```

## 数据请求

    端口为rpc启动的时候配置的端口

```
    http://ip:8080/diandi_hotel/device/ceshi
```