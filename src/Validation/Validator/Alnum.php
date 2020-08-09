<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：做字母和数字字符检测
 * 作    者：zhaoy
 * 修改日期：2019/8/13
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Alnum extends Validator
{
    const TYPE = 'Alnum';

    protected $message = ':field必须只包含字母和数字';

    /**
     * 功能：做字母和数字字符检测
     * 修改日期：2019/8/11
     *
     * @param \Snow\Validation\Validation $validation 验证类
     * @param string $field 字段
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $value = $validation->getValue($field);
        if (ctype_alnum($value)) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $validation->appendMessage(new Message(strtr($message, [':field' => $label]), $field, self::TYPE, $code));
        return false;
    }
}
