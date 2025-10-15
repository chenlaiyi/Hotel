<?php

namespace common\rpc\model;

use common\rpc\pdo\SwoolePDOActiveRecord;

class DdUser  extends SwoolePDOActiveRecord
{

    static function tableName(): string
    {
        return '{{%user}}';
    }

    function rules()
    {
        // TODO: Implement rules() method.
        return [
          [['store_id']]
        ];
    }
}