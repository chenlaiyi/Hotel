# composer 的使用

## 更新到指定版本
### https://getcomposer.org/download/
```php
    swoole-cli .\composer.phar self-update --snapshot 2.8.1
```


## 清除缓存

```php
    swoole-cli .\composer.phar clear-cache
```

```php
    composer config -g bin-dir "D:\Program Files\Git\bin"  # 替换为您的 Git bin 实际路径
```

composer config --global --auth github-oauth.github.com *****************