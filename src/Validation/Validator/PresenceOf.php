<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：验证字段的值不是null、空字符串或空数组
 * 作    者：zhaoy
 * 修改日期：2019/8/24
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class PresenceOf extends Validator
{
    const TYPE = 'PresenceOf';

    protected $message = ':field不能为空';

    /**
     * 功能：验证字段的值不是null、空字符串或空数组
     * 修改日期：2019/8/24
     *
     * @param \Snow\Validation\Validation $validation 验证调度器
     * @param string $field 字段
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $value = $validation->getValue($field);
        if ($value === null || $value === '' || (is_array($value) && !count($value))) {
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
