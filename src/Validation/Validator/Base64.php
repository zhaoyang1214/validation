<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Base64字串类型检测
 * 作    者：chenmh
 * 修改日期：2019/8/27
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Base64 extends Validator
{
    const TYPE = 'Base64';

    protected $message = ':field必须是Base64格式串';

    /**
     * 功能：判断字符串是否为Base64字串
     * 修改日期：2019/8/27
     *
     * @param \Snow\Validation\Validation $validation 验证类
     * @param string $field 字段
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $value = (string)$validation->getValue($field);
        if (preg_match('#^[A-Za-z0-9+/\n\r]+={0,2}$#', $value) && mb_strlen($value, 'UTF-8') % 4 === 0) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $validation->appendMessage(new Message(strtr($message, [':field' => $label]), $field, self::TYPE, $code));
        return false;
    }
}
