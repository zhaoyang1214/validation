<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：手机号码格式字串检测
 * 作    者：chenmh
 * 修改日期：2019/8/27
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Phone extends Validator
{
    const TYPE = 'Phone';

    protected $message = ':field必须是合法的手机号码';

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
        $value = $validation->getValue($field);
        if (preg_match("/^1[345789]\d{9}$/", $value)) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $validation->appendMessage(new Message(strtr($message, [':field' => $label]), $field, self::TYPE, $code));
        return false;
    }
}
