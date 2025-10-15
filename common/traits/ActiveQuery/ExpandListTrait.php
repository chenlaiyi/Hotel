<?php

/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2025-06-18 10:21:39
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2025-06-30 11:19:21
 */

namespace common\traits\ActiveQuery;

trait ExpandListTrait
{
    /**
     * json 字段
     *
     * @var string|array
     */
    //    public string $jsonField = '';

    //    public array $expandListField = [];
    public array $expandList = [];

    public function afterFind()
    {
        $this->expandList = $this->getExpandList($this->attributes, $this->attributeLabels());
        parent::afterFind();
    }

    public function getExpandList(array $value, $labels = []): array
    {
        $json = [];

        /**
         * 如果类定义了属性expandListField
         */
        if (property_exists($this, 'expandListField')) {
            $expandLists = $this->expandListField;
            if ($expandLists) {
                foreach ($expandLists as $field) {
                    $json[] = [
                        'label' => $labels[$field] ?? '请配置' . $field . '的描述',
                        'prop'  => $value[$field] ?? '-',
                    ];
                }
            }
        }

        if (property_exists($this, 'jsonField')) {
            $field = $this->jsonField;
            /**
             * json格式必须是包含label、prop
             */
            if (key_exists($field, $value) && is_string($value[$field])) {
                $list = json_decode($value[$field] ?? '', true);
                foreach ($list as $key => $item) {
                    $json[] = [
                        'label' => $labels[$key] ?? '请配置' . $key . '的描述',
                        'prop'  => $item,
                    ];
                }
            }
            if (key_exists($field, $value) && is_array($value[$field])) {
                foreach ($value[$field] as $key => $item) {
                    if ($item) {
                        $json[] = [
                            'label' => $labels[$key] ?? '请配置' . $key . '的描述',
                            'prop'  => $item,
                        ];
                    }
                }
            }
        }
        $this->expandList = $json;

        return $json;
    }
}
