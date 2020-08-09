<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查值是否是合法Ip
 * 作    者：zhaoy
 * 修改日期：2019/8/24
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Ip extends Validator
{
    const TYPE = 'Ip';

    const VERSION_4  = FILTER_FLAG_IPV4;

    const VERSION_6  = FILTER_FLAG_IPV6;

    const OPT_VERSION = 'version';

    const OPT_ALLOW_PRIVATE = 'allowPrivate';

    const OPT_ALLOW_RESERVED = 'allowReserved';

    protected $message = ':field必须是有效的IP地址';

    /**
     * 功能：检查值是否是合法Ip
     * 修改日期：2019/8/24
     *
     * @param \Snow\Validation\Validation $validation 验证调度器
     * @param string $field 字段
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $value = $validation->getValue($field);
        $version = $this->prepareOption(self::OPT_VERSION, $field, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6);
        $allowPrivate = $this->prepareOption(self::OPT_ALLOW_PRIVATE, $field, false) ? 0 : FILTER_FLAG_NO_PRIV_RANGE;
        $allowReserved = $this->prepareOption(self::OPT_ALLOW_RESERVED, $field, false) ? 0 : FILTER_FLAG_NO_RES_RANGE;
        $options = [
            'options' => ['default' => false],
            'flags' => $version | $allowPrivate | $allowReserved
        ];
        if (filter_var($value, FILTER_VALIDATE_IP, $options)) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $message = strtr($message, [':field' => $label]);
        $validation->appendMessage(new Message($message, $field, self::TYPE, $code));
        return false;
    }
}
