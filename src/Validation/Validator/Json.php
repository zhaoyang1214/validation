<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Json格式字串检测
 * 作    者：chenmh
 * 修改日期：2019/8/27
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Json extends Validator
{
    const TYPE = 'Json';

    protected $message = ':field必须是Json格式串';

    /**
     * 功能：判断字符串是否为Json字串
     * 修改日期：2019/8/27
     *
     * @param \Snow\Validation\Validation $validation 验证类
     * @param string $field 字段
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $value = (string)$validation->getValue($field);
        json_decode($value);
        if (json_last_error() === JSON_ERROR_NONE) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $validation->appendMessage(new Message(strtr($message, [':field' => $label]), $field, self::TYPE, $code));
        return false;
    }
}
