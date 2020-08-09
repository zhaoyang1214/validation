<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查值是否为有效日期
 * 作    者：zhaoy
 * 修改日期：2019/8/24
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Date extends Validator
{
    const TYPE = 'Date';

    /**
     * @var string 日期格式
     */
    const OPT_FORMAT = 'format';

    protected $message = ':field必须是有效的日期';

    /**
     * 功能：检查值是否为有效日期
     * 修改日期：2019/8/24
     *
     * @param \Snow\Validation\Validation $validation 验证调度器
     * @param string $field 字段
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $value = (string)$validation->getValue($field);
        $format = $this->prepareOption(self::OPT_FORMAT, $field, 'Y-m-d');
        if ($this->checkDate($value, $format)) {
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
     * 功能：验证日期
     * 修改日期：2019/8/24
     *
     * @param string $value 字段值
     * @param string $format 日期格式
     * @return bool
     */
    protected function checkDate($value, $format)
    {
        if (!is_string($value)) {
            return false;
        }
        $dateTime = \DateTime::createFromFormat($format, $value);
        $errors = \DateTime::getLastErrors();
        return !(($errors['warning_count'] > 0 || $errors['error_count'] > 0 || strcmp($dateTime->format($format), $value) !== 0));
    }
}
