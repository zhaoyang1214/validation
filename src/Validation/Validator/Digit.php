<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查数字字符（纯数字）
 * 作    者：zhaoy
 * 修改日期：2019/8/24
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Digit extends Validator
{
    const TYPE = 'Digit';

    protected $message = ':field必须是纯数字';

    /**
     * 功能：检查数字字符（纯数字）
     * 修改日期：2019/8/24
     *
     * @param \Snow\Validation\Validation $validation 验证调度器
     * @param string $field 字段
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $value = (string)$validation->getValue($field);
        if (ctype_digit($value)) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $message = strtr($message, [':field' => $label]);
        $validation->appendMessage(new Message($message, $field, self::TYPE, $code));
        return false;
    }
}
