<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查值是否匹配正则表达式
 * 作    者：zhaoy
 * 修改日期：2019/8/24
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Regex extends Validator
{
    const TYPE = 'Regex';

    const OPT_PATTERN = 'pattern';

    protected $message = ':field匹配失败';

    /**
     * 功能：检查值是否匹配正则表达式
     * 修改日期：2019/8/24
     *
     * @param \Snow\Validation\Validation $validation 验证调度器
     * @param string $field 字段
     * @throws \Snow\Validation\Validation\Validator\Exception
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $value = (string)$validation->getValue($field);
        $pattern = $this->prepareOption(self::OPT_PATTERN, $field);
        if (empty($pattern)) {
            throw new Exception(slef::class . '验证器必须设置' . self::OPT_PATTERN . '选项');
        }
        if (preg_match($pattern, $value, $matches)) {
            $failed = $matches[0] !== $value;
        } else {
            $failed = true;
        }
        if ($failed) {
            $label = $this->prepareLabel($validation, $field);
            $message = $this->prepareMessage($validation, $field, self::TYPE);
            $code = $this->prepareCode($field);
            $message = strtr($message, [':field' => $label]);
            $validation->appendMessage(new Message($message, $field, self::TYPE, $code));
            return false;
        }
        return true;
    }
}
