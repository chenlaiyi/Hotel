# ���ݿ����

## ��ѯ����

### or

```php
    ['or',['mobile'=>123],['id'=>1]]

```
### and

```php
    ['and',['mobile'=>123],['id'=>1]]
```

### ���Ӳ�ѯ

```php
    ['or',['username'=>'admin'],['and',['id'=>11],['mobile'=>123]],['store_id'=>111]]
```


## ��ҳ��ѯ

```php
    PdoQuery::getInstance()->findPaginated('user',1,11);
```

## ��ѯ������¼

```php
$result = PdoQuery::getInstance()->findOneBy('user',['or',['username'=>'admin'],['and',['id'=>11],['mobile'=>123]],['store_id'=>111]]);

```

## ��ѯ���м�¼
```php
$result = PdoQuery::getInstance()->findAllBy('user',['or',['username'=>'admin'],['and',['id'=>11],['mobile'=>123],['store_id'=>111]]]);
```
## ��ѯ��ҳ
```php
$result = PdoQuery::getInstance()->findAllPaginated('user',1,11);
```
## ��ѯ�����ֶ�
```php
$result = PdoQuery::getInstance()->findfield('user','uid',['or',['username'=>'admin'],['and',['id'=>11],['mobile'=>123]],['store_id'=>111]],'id');
```

## ��ѯ���ִ�е�sql

```php
 $sql = PdoQuery::getInstance()->getLastSql();
```

# ����yii->query�﷨

### ��ѯ������¼

```php
    $PdoQuery = new PdoYii();
    $result = $PdoQuery->table('dd_user')->where(['id'=>232])->orWhere(['mobile'=>12])->orWhere(['store_id'=>2323,'username'=>'admin888'])->one();
```

### ��ѯ���ִ�е�sql
```php
    $sql = $PdoQuery->getLastSql();
```
### ��ѯ��ҳ
```php
    $PdoQuery = new PdoYii();
    $result = $PdoQuery->table('dd_user')->where(['id'=>232])->orWhere(['mobile'=>12])->orWhere(['store_id'=>2323,'username'=>'admin888'])->paginate(1,10);
```
### ��ѯ���м�¼
```php
    $PdoQuery = new PdoYii();
    $result = $PdoQuery->table('dd_user')->where(['id'=>232])->orWhere(['mobile'=>12])->orWhere(['store_id'=>232
3,'username'=>'admin888'])->all();
```
### ��ѯ�����ֶ�
```php
    $PdoQuery = new PdoYii();
    $result = $PdoQuery->table('dd_user')->where(['id'=>232])->orWhere(['mobile'=>12])->orWhere(['store_id'=>2323,'username'=>'admin888'])->select('id,username')->one();
```
