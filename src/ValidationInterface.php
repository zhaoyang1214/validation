<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：验证接口
 * 作    者：zhaoy
 * 修改日期：2019/8/13
 */


namespace Snow\Validation;

use Snow\Validation\Validation\MessageInterface;
use Snow\Validation\Validation\ValidatorInterface;

interface ValidationInterface
{
    /**
     * 功能：向字段添加验证器
     * 修改日期：2019/8/11
     *
     * @param array|string $field 字段
     * @param ValidatorInterface $validator 验证器
     * @return $this
     */
    public function add($field, ValidatorInterface $validator);

    /**
     * 功能：`add`方法的别名
     * 修改日期：2019/8/11
     *
     * @param array|string $field 字段
     * @param ValidatorInterface $validator 验证器
     * @return Validation
     */
    public function rule($field, ValidatorInterface $validator);

    /**
     * 功能：给字段添加多个验证器
     * 修改日期：2019/8/11
     *
     * @param array|string $field 字段
     * @param ValidationInterface[] $validators 验证器
     * @return $this
     */
    public function rules($field, array $validators);

    /**
     * 功能：返回验证器
     * 修改日期：2019/8/11
     *
     * @return array
     */
    public function getValidators();

    /**
     * 功    能：设置默认提示
     * 修改日期：2019/8/13
     *
     * @param array $messages 提示消息
     * @return $this
     */
    public function setDefaultMessages(array $messages = []);

    /**
     * 功    能：获取默认提示
     * 修改日期：2019/8/13
     *
     * @param string $type 验证类型
     * @return mixed
     */
    public function getDefaultMessage(string $type);

    /**
     * 功能：设置字段标签
     * 修改日期：2019/8/11
     *
     * @param array $labels 字段标签
     * @return $this
     */
    public function setLabels(array $labels);

    /**
     * 功能：获取字段标签
     * 修改日期：2019/8/11
     *
     * @param array|string $field 字段
     * @return string
     */
    public function getLabel($field);

    /**
     * 功    能：将消息追加到消息列表中
     * 修改日期：2019/8/13
     *
     * @param MessageInterface $message 消息对象
     * @return $this
     */
    public function appendMessage(MessageInterface $message);

    /**
     * 功能：获取字段值
     * 修改日期：2019/8/11
     *
     * @param string $field 字段
     * @return mixed
     */
    public function getValue(string $field);

    /**
     * 功能：根据一组规则对一组数据进行校验
     * 修改日期：2019/8/11
     *
     * @param array|object $data 待验证数据
     * @return mixed
     */
    public function validate($data = null);
}
