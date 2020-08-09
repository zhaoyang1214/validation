<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查两个值是否相同
 * 作    者：zhaoy
 * 修改日期：2019/8/17
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Confirmation extends Validator
{
    const TYPE = 'Confirmation';

    /**
     * @var string 要比较的字段选项
     */
    const OPT_WITH = 'with';

    /**
     * @var string 要比较的字段的标签选项
     */
    const OPT_LABEL_WITH = 'labelWith';

    /**
     * @var string 忽略大小写选项
     */
    const OPT_IGNORE_CASE = 'ignoreCase';

    protected $message = ':field与:with必须相同';

    /**
     * 功能：检查两个值是否相同
     * 修改日期：2019/8/17
     *
     * @param \Snow\Validation\Validation $validation 验证调度器
     * @param string $field 字段
     * @throws \Snow\Validation\Validation\Validator\Exception
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $fieldWith = $this->prepareOption(self::OPT_WITH, $field);
        if (is_null($fieldWith)) {
            throw new Exception(self::class . '验证器必须设置' . self::OPT_WITH . '选项');
        }
        $value = $validation->getValue($field);
        $valueWith = $validation->getValue($fieldWith);
        $ignoreCase = $this->prepareOption(self::OPT_IGNORE_CASE, $field, false);
        if (!is_bool($ignoreCase)) {
            $ignoreCase = false;
        }
        if ($this->compare($value, $valueWith, $ignoreCase)) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $labelWith = $this->getOption(self::OPT_LABEL_WITH);
        $labelWith = $labelWith ?? $this->prepareLabel($validation, $fieldWith);
        $message = strtr($message, [
            ':field' => $label,
            ':with' => $labelWith,
        ]);
        $validation->appendMessage(new Message($message, $field, self::TYPE, $code));
        return false;
    }

    /**
     * 功能：比较两个值是否相同
     * 修改日期：2019/8/17
     *
     * @param mixed $value 第一个值
     * @param mixed $valueWith 第二个值
     * @param bool $ignoreCase 忽略大小写
     * @return bool
     */
    protected function compare($value, $valueWith, $ignoreCase)
    {
        if ($ignoreCase) {
            if (function_exists('mb_strtolower')) {
                $value = mb_strtolower($value, 'UTF-8');
                $valueWith = mb_strtolower($valueWith, 'UTF-8');
            } else {
                $value = strtolower($value);
                $valueWith = strtolower($valueWith);
            }
        }
        return $value === $valueWith;
    }
}
