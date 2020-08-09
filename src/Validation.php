<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：验证类
 * 作    者：zhaoy
 * 修改日期：2019/8/13
 */


namespace Snow\Validation;

use Snow\Validation\Validation\CombinedFieldsValidator;
use Snow\Validation\Validation\Exception;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\MessageInterface;
use Snow\Validation\Validation\ValidatorInterface;

class Validation implements ValidationInterface
{
    /**
     * @var array|object 待验证数据
     */
    protected $data;

    /**
     * @var array 字段绑定验证器
     */
    protected $validators = [];

    /**
     * @var \Snow\Validation\Validation\Group 错误消息
     */
    protected $messages;

    /**
     * @var array 默认提示消息
     */
    protected $defaultMessages = [];

    /**
     * @var array 字段标签
     */
    protected $labels = [];

    /**
     * Validation constructor.
     * @param array $validators 初始化字段和验证器绑定
     * @throws Exception
     */
    public function __construct(array $validators = [])
    {
        foreach ($validators as $validator) {
            $this->add($validator[0], $validator[1]);
        }
        if (method_exists($this, 'initialize')) {
            $this->{'initialize'}();
        }
    }

    /**
     * 功能：设置要验证的数据
     * 修改日期：2019/8/11
     *
     * @param array|object $data 待验证的数据
     * @throws Exception
     * @return $this
     */
    public function setData($data)
    {
        if (!is_array($data) && !is_object($data)) {
            throw new Exception('data必须是数组或者对象');
        }
        $this->data = $data;
        return $this;
    }

    /**
     * 功能：获取数据
     * 修改日期：2019/8/11
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 功能：向字段添加验证器
     * 修改日期：2019/8/11
     *
     * @param array|string $field 字段
     * @param \Snow\Validation\Validation\ValidatorInterface $validator 验证器
     * @throws \Snow\Validation\Validation\Exception
     * @return $this
     */
    public function add($field, ValidatorInterface $validator)
    {
        if (is_array($field)) {
            if ($validator instanceof CombinedFieldsValidator) {
                if (empty($field)) {
                    throw new Exception('field不能为空');
                }
                $this->validators[] = [$field, $validator];
            } else {
                foreach ($field as $singleField) {
                    $this->validators[] = [$singleField, $validator];
                }
            }
        } elseif (is_string($field)) {
            $this->validators[] = [$field, $validator];
        } else {
            throw new Exception('field必须是数组或字符串');
        }
        return $this;
    }

    /**
     * 功能：`add`方法的别名
     * 修改日期：2019/8/11
     *
     * @param array|string $field 字段
     * @param ValidatorInterface $validator 验证器
     * @throws \Snow\Validation\Validation\Exception
     * @return Validation
     */
    public function rule($field, ValidatorInterface $validator)
    {
        return $this->add($field, $validator);
    }

    /**
     * 功能：给字段添加多个验证器
     * 修改日期：2019/8/11
     *
     * @param array|string $field 字段
     * @param ValidatorInterface[] $validators 验证器
     * @throws \Snow\Validation\Validation\Exception
     * @return $this
     */
    public function rules($field, array $validators)
    {
        foreach ($validators as $validator) {
            $this->add($field, $validator);
        }
        return $this;
    }

    /**
     * 功能：返回验证器
     * 修改日期：2019/8/11
     *
     * @return Validation\Validator[] 验证器
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * 功    能：设置默认提示
     * 修改日期：2019/8/13
     *
     * @param array $messages 提示消息
     * @return $this
     */
    public function setDefaultMessages(array $messages = [])
    {
        $this->defaultMessages = array_merge($this->defaultMessages, $messages);
        return $this;
    }

    /**
     * 功    能：获取默认提示
     * 修改日期：2019/8/13
     *
     * @param string $type 验证类型
     * @return string|array|null
     */
    public function getDefaultMessage(string $type)
    {
        return $this->defaultMessages[$type] ?? null;
    }

    /**
     * 功能：设置字段标签
     * 修改日期：2019/8/11
     *
     * @param array $labels 标签
     * @return $this
     */
    public function setLabels(array $labels)
    {
        $this->labels = $labels;
        return $this;
    }

    /**
     * 功能：获取字段标签
     * 修改日期：2019/8/11
     *
     * @param array|string $field 字段
     * @return string
     */
    public function getLabel($field)
    {
        if (is_array($field)) {
            $field = implode(', ', $field);
        }
        $labels = $this->labels;
        if (isset($labels[$field])) {
            return $labels[$field];
        }
        return $field;
    }

    /**
     * 功能：将消息追加到消息列表中
     * 修改日期：2019/8/11
     *
     * @param Validation\MessageInterface $message 消息对象
     * @return $this
     */
    public function appendMessage(MessageInterface $message)
    {
        $messages = $this->messages;
        if (!is_object($message)) {
            $messages = new Group();
        }
        $messages->appendMessage($message);
        $this->messages = $messages;
        return $this;
    }

    /**
     * 功能：获取字段值
     * 修改日期：2019/8/11
     *
     * @param string $field 字段
     * @return mixed
     */
    public function getValue(string $field)
    {
        $data = $this->data;
        if (is_array($data) && isset($data[$field])) {
            $value = $data[$field];
        } elseif (is_object($data) && isset($data->{$field})) {
            $value = $data->{$field};
        }
        if (!isset($value)) {
            $field = trim($field, '.');
            if (strpos($field, '.')) {
                $fieldArr = explode('.', $field);
                $value = $data;
                foreach ($fieldArr as $v) {
                    if (isset($value[$v])) {
                        $value = $value[$v];
                    } elseif (isset($value->{$v})) {
                        $value = $value->{$v};
                    } else {
                        $value = null;
                        break;
                    }
                }
            }
        }
        return $value ?? null;
    }

    /**
     * 功能：判断空值是否验证
     * 修改日期：2019/8/11
     *
     * @param array|string $field 字段
     * @param ValidatorInterface $validator 验证器
     * @throws Exception
     * @return bool
     */
    protected function preChecking($field, ValidatorInterface $validator)
    {
        if (is_array($field)) {
            foreach ($field as $singleField) {
                $result = $this->preChecking($singleField, $validator);
                if ($result) {
                    return $result;
                }
            }
        } else {
            $allowEmpty = $validator->getOption('allowEmpty', false);
            if ($allowEmpty) {
                if (method_exists($validator, 'isAllowEmpty')) {
                    return $validator->isAllowEmpty($this, $field);
                }
                $value = $this->getValue($field);
                if (is_array($allowEmpty)) {
                    foreach ($allowEmpty as $emptyValue) {
                        if ($emptyValue === $value) {
                            return true;
                        }
                    }
                    return false;
                }
                return empty($value);
            }
        }
        return false;
    }


    /**
     * 功能：根据一组规则对一组数据进行校验
     * 修改日期：2019/8/11
     *
     * @param array|object $data 待验证数据
     * @throws Exception
     * @return \Snow\Validation\Validation\Group|bool
     */
    public function validate($data = null)
    {
        $validators = $this->validators;

        if (empty($validators)) {
            throw new Exception('没有要验证的验证器');
        }
        $messages = new Group();
        $this->messages = $messages;
        if (method_exists($this, 'beforeValidate')) {
            $status = $this->{'beforeValidate'}($data, $messages);
            if ($status === false) {
                return false;
            }
        }
        if (!is_null($data)) {
            $this->setData($data);
        }
        foreach ($validators as $scope) {
            if (!is_array($scope) || !isset($scope[0], $scope[1]) || !($scope[1] instanceof ValidatorInterface)) {
                throw new Exception($scope[1] . '验证器无效');
            }
            $field = $scope[0];
            /**
             * @var \Snow\Validation\Validation\ValidatorInterface $validator
             */
            $validator = $scope[1];
            if ($this->preChecking($field, $validator)) {
                continue;
            }
            if ($validator->validate($this, $field) === false) {
                if ($validator->getOption('cancelOnFail', false)) {
                    break;
                }
            }
        }
        if (method_exists($this, 'afterValidate')) {
            $this->{'afterValidate'}($data, $this->messages);
        }
        return $this->messages;
    }

    /**
     * 功    能：返回一组错误消息（执行validate之后）
     * 修改日期：2019/8/14
     *
     * @return Group
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
