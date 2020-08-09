<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查值是否不包含在值列表中
 * 作    者：zhaoy
 * 修改日期：2019/8/24
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class ExclusionIn extends Validator
{
    const TYPE = 'ExclusionIn';

    /**
     * @var string 值范围选项参数
     */
    const OPT_DOMAIN = 'domain';

    /**
     * @var string 是否严格校验选项参数
     */
    const OPT_STRICT = 'strict';

    protected $message = ':field必须不能在:domain范围内';

    /**
     * 功能：检查值是否不包含在值列表中
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
        $domain = $this->prepareOption(self::OPT_DOMAIN, $field);
        if (!is_array($domain)) {
            throw new Exception(slef::class . '验证器' . self::OPT_DOMAIN . '选项必须是数组');
        }
        $strict = (bool)$this->prepareOption(self::OPT_STRICT, $field, false);
        if (!in_array($value, $domain, $strict)) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $message = strtr($message, [
            ':field' => $label,
            ':domain' => implode(',', $domain),
        ]);
        $validation->appendMessage(new Message($message, $field, self::TYPE, $code));
        return false;
    }
}
