<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查字符串长度是否相等
 * 作    者：zhaoy
 * 修改日期：2019/9/2
 */


namespace Snow\Validation\Validation\Validator\StringLength;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Equal extends Validator
{
    const TYPE = 'StringLengthEqual';

    const OPT_LENGTH = 'length';

    protected $message = ':field长度必须为:length个字符';

    /**
     * 功能：检查字符串长度是否相等
     * 修改日期：2019/9/2
     *
     * @param \Snow\Validation\Validation $validation 验证类
     * @param string $field 字段
     * @throws \Snow\Validation\Validation\Validator\Exception
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $length = $this->prepareOption(self::OPT_LENGTH, $field);
        if (is_null($length)) {
            throw new Validator\Exception(self::class . '验证器' . self::OPT_LENGTH . '选项必须设置');
        }
        $value = (string)$validation->getValue($field);
        if (function_exists('mb_strlen')) {
            $strLen = mb_strlen($value, 'utf-8');
        } else {
            $strLen = strlen($value);
        }
        if ($strLen == $length) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $replacePairs = [
            ':field' => $label,
            ':length' => $length,
        ];
        $validation->appendMessage(new Message(strtr($message, $replacePairs), $field, self::TYPE, $code));
        return false;
    }
}
