<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：验证值是否在两个值的范围之间
 * 作    者：zhaoy
 * 修改日期：2019/8/16
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Between extends Validator
{
    const TYPE = 'Between';

    /**
     * @var string 最小值选项参数
     */
    const OPT_MINIMUM = 'minimum';

    /**
     * @var string 最大值选项参数
     */
    const OPT_MAXIMUM = 'maximum';

    protected $message = ':field必须在:minimum到:maximum的范围内';

    /**
     * 功能：验证值是否在两个值的范围之间
     * 修改日期：2019/8/16
     *
     * @param \Snow\Validation\Validation $validation 验证调度器
     * @param string $field 字段
     * @throws \Snow\Validation\Validation\Validator\Exception
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $value = $validation->getValue($field);
        $minimum = $this->prepareOption(self::OPT_MINIMUM, $field);
        $maximum = $this->prepareOption(self::OPT_MAXIMUM, $field);
        if (!isset($minimum)) {
            throw new Exception(self::class . '验证器必须设置' . self::OPT_MINIMUM . '选项');
        }
        if (!isset($maximum)) {
            throw new Exception(self::class . '验证器必须设置' . self::OPT_MAXIMUM . '选项');
        }
        if ($value >= $minimum && $value <= $maximum) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $message = strtr($message, [
            ':field' => $label,
            ':minimum' => $minimum,
            ':maximum' => $maximum,
        ]);
        $validation->appendMessage(new Message($message, $field, self::TYPE, $code));
        return false;
    }
}
