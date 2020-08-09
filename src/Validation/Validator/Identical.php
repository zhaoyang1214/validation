<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查值是否与其他值相同
 * 作    者：zhaoy
 * 修改日期：2019/8/24
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Identical extends Validator
{
    const TYPE = 'Identical';

    /**
     * @var string 服务条款选项参数
     */
    const OPT_ACCEPTED = 'accepted';

    /**
     * @var string 比较值选项参数
     */
    const OPT_VALUE = 'value';

    protected $message = ':field必须为:value';

    /**
     * 功能：检查值是否与其他值相同
     * 修改日期：2019/8/24
     *
     * @param \Snow\Validation\Validation $validation 验证调度器
     * @param string $field 字段
     * @throws \Snow\Validation\Validation\Validator\Exception
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $value = $validation->getValue($field);
        $accepted = $this->prepareOption(self::OPT_ACCEPTED, $field);
        if (is_null($accepted)) {
            $accepted = $this->prepareOption(self::OPT_VALUE, $field);
            if (is_null($accepted)) {
                throw new Exception(self::class . '验证器必须设置' . self::OPT_ACCEPTED . '或' . self::OPT_VALUE . '选项');
            }
        }
        if ($value === $accepted) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $message = strtr($message, [
            ':field' => $label,
            ':value' => $accepted,
        ]);
        $validation->appendMessage(new Message($message, $field, self::TYPE, $code));
        return false;
    }
}
