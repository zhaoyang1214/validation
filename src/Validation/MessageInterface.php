<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：消息接口
 * 作    者：zhaoy
 * 修改日期：2019/8/13
 */


namespace Snow\Validation\Validation;

interface MessageInterface
{

    /**
     * 功能：设置消息类型
     * 修改日期：2019/8/10
     *
     * @param string $type 类型
     * @return $this
     */
    public function setType(string $type);

    /**
     * 功能：返回消息类型
     * 修改日期：2019/8/10
     *
     * @return string
     */
    public function getType();

    /**
     * 功能：设置详细消息
     * 修改日期：2019/8/10
     *
     * @param string $message 消息
     * @return $this
     */
    public function setMessage(string $message);

    /**
     * 功能：返回详细消息
     * 修改日期：2019/8/10
     *
     * @return string
     */
    public function getMessage();

    /**
     * 功能：设置与消息相关的字段名
     * 修改日期：2019/8/10
     *
     * @param mixed $field 字段
     * @return $this
     */
    public function setField($field);

    /**
     * 功能：返回与消息相关的字段名
     * 修改日期：2019/8/10
     *
     * @return mixed
     */
    public function getField();

    /**
     * 功能：设置消息编码
     * 修改日期：2019/8/10
     *
     * @param int $code 消息编码
     * @return $this
     */
    public function setCode(int $code);

    /**
     * 功能：返回消息编码
     * 修改日期：2019/8/10
     *
     * @return int
     */
    public function getCode();
}
