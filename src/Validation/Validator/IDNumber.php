<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查18位身份证号码是否正确
 * 作    者：zhaoy
 * 修改日期：2019/8/25
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class IDNumber extends Validator
{
    const TYPE = 'IDNumber';

    protected $message = ':field必须是合法身份证号码';

    /**
     * 功能：检查18位身份证号码是否正确
     * 修改日期：2019/8/25
     *
     * @param \Snow\Validation\Validation $validation 验证调度器
     * @param string $field 字段
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $value = $validation->getValue($field);
        if ($this->verifyIDNumber($value)) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $message = strtr($message, [':field' => $label]);
        $validation->appendMessage(new Message($message, $field, self::TYPE, $code));
        return false;
    }

    /**
     * 功能：验证身份证号
     * 修改日期：2019/8/25
     *
     * @param string $number 18位身份证号码
     * @return bool
     */
    private function verifyIDNumber(string $number)
    {
        $len = strlen($number);
        if ($len != 18 || !ctype_digit(substr($number, 0, -1))) {
            return false;
        }
        $num = str_split($number);
        $weights = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        $valid = [1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;
        foreach ($weights as $i => $weight) {
            $sum += $num[$i] * $weight;
        }
        $mode = $sum % 11;
        return $valid[$mode] == strtoupper($num[17]) ? true : false;
    }
}
