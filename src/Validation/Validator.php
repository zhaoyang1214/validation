<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：desc
 * 作    者：zhaoy
 * 修改日期：2019/8/13
 */


namespace Snow\Validation\Validation;

abstract class Validator implements ValidatorInterface
{
    const OPT_MESSAGE = 'message';

    const OPT_LABEL = 'label';

    const OPT_CODE = 'code';

    const OPT_ALLOW_EMPTY = 'allowEmpty';

    const OPT_CANCEL_ON_FAIL = 'cancelOnFail';

    /**
     * @var array 选项
     */
    protected $options;

    /**
     * @var string|array 错误提示
     */
    protected $message;

    /**
     * Validator constructor.
     * @param array $options 选项
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * 功能：检查是否定义了选项
     * 修改日期：2019/8/11
     *
     * @param string $key 选项key
     * @return bool
     */
    public function hasOption(string $key)
    {
        return isset($this->options[$key]);
    }

    /**
     * 功能：获取选项值
     * 修改日期：2019/8/11
     *
     * @param string $key 选项key
     * @param mixed $defaultValue 默认值
     * @return mixed
     */
    public function getOption(string $key, $defaultValue = null)
    {
        return $this->options[$key] ?? $defaultValue;
    }

    /**
     * 功能：设置选项
     * 修改日期：2019/8/11
     *
     * @param string $key 选项key
     * @param mixed $value 选项值
     * @return $this
     */
    public function setOption(string $key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * 功能：执行验证
     * 修改日期：2019/8/11
     *
     * @param \Snow\Validation\Validation $validation 验证类
     * @param array|string $field 字段
     * @return bool
     */
    abstract public function validate(\Snow\Validation\Validation $validation, $field);

    /**
     * 功能：获取字段标签
     * 修改日期：2019/8/11
     *
     * @param \Snow\Validation\Validation $validation 验证类
     * @param string|array $field 字段
     * @return string
     */
    protected function prepareLabel(\Snow\Validation\Validation $validation, $field)
    {
        $label = $this->getOption('label');
        if (is_string($field) && is_array($label) && isset($label[$field])) {
            $label = $label[$field];
        }
        if (empty($label)) {
            $label = $validation->getLabel($field);
        }
        return is_string($label) ? $label : '';
    }

    /**
     * 功能：准备验证消息
     * 修改日期：2019/8/11
     *
     * @param \Snow\Validation\Validation $validation 验证类
     * @param string $field 字段
     * @param string $type 验证器类型
     * @param string|null $messageKey 消息key
     * @return string
     */
    protected function prepareMessage(\Snow\Validation\Validation $validation, string $field, string $type, string $messageKey = null)
    {
        $message = $this->getOption('message');
        if (is_array($message)) {
            if (is_null($messageKey)) {
                if (isset($message[$field])) {
                    $message = $message[$field];
                }
            } elseif (isset($message[$messageKey])) {
                if (is_string($message[$messageKey])) {
                    $message = $message[$messageKey];
                } elseif (is_array($message[$messageKey]) && isset($message[$messageKey][$field])) {
                    $message = $message[$messageKey][$field];
                }
            }
        }
        if (!is_string($message)) {
            $message = $validation->getDefaultMessage($type);
            if (!is_null($messageKey) && is_array($message) && isset($message[$messageKey])) {
                $message = $message[$messageKey];
            }
        }
        if (!is_string($message)) {
            $message = $this->message ?? null;
            if (is_array($message) && isset($message[$messageKey])) {
                $message = $message[$messageKey];
            }
        }
        return is_string($message) ? $message : '';
    }

    /**
     * 功能：准备验证编码
     * 修改日期：2019/8/11
     *
     * @param string $field 字段
     * @return int
     */
    protected function prepareCode(string $field)
    {
        $code = $this->getOption('code');
        if (is_array($code) && isset($code[$field])) {
            $code = $code[$field];
        }
        if (empty($code)) {
            $code = $this->code ?? null;
        }
        return is_int($code) ? $code : 0;
    }

    /**
     * 功能：准备选项
     * 修改日期：2019/8/17
     *
     * @param string $optionKey 选项key
     * @param string|null $field 字段
     * @param mixed $defaultValue 默认值
     * @return mixed
     */
    protected function prepareOption(string $optionKey, string $field = null, $defaultValue = null)
    {
        $option = $this->getOption($optionKey);
        if (is_array($option)) {
            if (isset($option[$field])) {
                $option = $option[$field];
            } elseif (!(is_null($defaultValue) || is_array($defaultValue))) {
                $option = $defaultValue;
            }
        }
        return $option ?? $defaultValue;
    }
}
