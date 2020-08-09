<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查信用卡号码是否有效
 * 作    者：zhaoy
 * 修改日期：2019/8/24
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class CreditCard extends Validator
{
    const TYPE = 'CreditCard';

    protected $message = ':field卡号不合法';

    /**
     * 功能：检查信用卡号码是否有效
     * 修改日期：2019/8/17
     *
     * @param \Snow\Validation\Validation $validation 验证调度器
     * @param string $field 字段
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $value = $validation->getValue($field);
        if ($this->verify($value)) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $validation->appendMessage(new Message(strtr($message, [':field' => $label]), $field, self::TYPE, $code));
        return false;
    }

    /**
     * 功能：验证信用卡号
     * 修改日期：2019/8/24
     *
     * @param string $number 信用卡号
     * @return bool
     */
    private function verify($number)
    {
        if (!ctype_digit($number)) {
            return false;
        }
        $digits = array_reverse(str_split($number));
        $hash = '';
        foreach ($digits as $position => $digit) {
            $hash .= ($position % 2 ? $digit * 2 : $digit);
        }
        return array_sum(str_split($hash)) % 10 == 0;
    }
}
