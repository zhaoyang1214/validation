<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：验证字段的值是否唯一
 * 作    者：zhaoy
 * 修改日期：2019/8/27
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\CombinedFieldsValidator;

class Uniqueness extends CombinedFieldsValidator
{
    const TYPE = 'Uniqueness';

    const OPT_MODEL = 'model';

    const OPT_METHOD = 'method';

    const OPT_ATTRIBUTE = 'attribute';

    const OPT_CONVERT = 'convert';

    protected $message = ':field已存在';

    /**
     * 功能：验证字段的值是否唯一
     * 修改日期：2019/8/27
     *
     * @param \Snow\Validation\Validation $validation 验证类
     * @param string|array $field 字段
     * @throws \Snow\Validation\Validation\Validator\Exception
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $fieldArr = (array)$field;
        $fields = implode(',', $fieldArr);
        $model = $this->getOption(self::OPT_MODEL);
        $method = $this->getOption(self::OPT_METHOD, 'isUniqueness');
        if (!is_object($model) || !method_exists($model, $method)) {
            throw  new Exception(self::class . '验证器' . self::OPT_MODEL . '选项必须是一个对象，且含有"' . $method . '"方法');
        }
        $data = $validation->getData();
        $convert = $this->getOption(self::OPT_CONVERT);
        if (is_callable($convert)) {
            $data = call_user_func($convert, $data);
        }
        foreach ($fieldArr as $k => $f) {
            $attribute = $this->prepareOption(self::OPT_ATTRIBUTE, $f);
            if (is_string($attribute)) {
                $fieldArr[$k] = $attribute;
                $data[$attribute] = $data[$f];
                unset($data[$f]);
            }
        }
        $returnedValue = call_user_func([$model, $method], $data, is_string($field) ? $fieldArr[0] : $fieldArr);
        if ($returnedValue) {
            return true;
        }
        $label = $this->prepareLabel($validation, $fields);
        $message = $this->prepareMessage($validation, $fields, self::TYPE);
        $code = $this->prepareCode($fields);
        $message = strtr($message, [':field' => $label]);
        $validation->appendMessage(new Message($message, $field, self::TYPE, $code));
        return false;
    }
}
