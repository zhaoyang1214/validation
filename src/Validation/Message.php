<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：消息类
 * 作    者：zhaoy
 * 修改日期：2019/8/13
 */


namespace Snow\Validation\Validation;

class Message implements MessageInterface
{
    /**
     * @var string 消息类型
     */
    protected $type;

    /**
     * @var string 消息详情
     */
    protected $message;

    /**
     * @var mixed 字段
     */
    protected $field;

    /**
     * @var int 消息码
     */
    protected $code;

    /**
     * Message constructor.
     * @param string $message 错误信息
     * @param string|array|null $field 字段
     * @param string|null $type 消息类型
     * @param int|null $code 消息码
     */
    public function __construct(string $message, $field = null, string $type = null, int $code = null)
    {
        $this->message = $message;
        $this->field = $field;
        $this->type = $type;
        $this->code = $code ?? 0;
    }

    /**
     * 功能：设置消息类型
     * 修改日期：2019/8/10
     *
     * @param string $type 消息类型
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * 功能：返回消息类型
     * 修改日期：2019/8/10
     *
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * 功能：设置消息详情
     * 修改日期：2019/8/10
     *
     * @param string $message 消息详情
     * @return $this
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * 功能：返回详细详情
     * 修改日期：2019/8/10
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * 功能：设置与消息相关的字段名
     * 修改日期：2019/8/10
     *
     * @param mixed $field 字段名
     * @return $this
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * 功能：返回与消息相关的字段名
     * 修改日期：2019/8/10
     *
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * 功能：设置消息编码
     * 修改日期：2019/8/10
     *
     * @param int $code 消息编码
     * @return $this
     */
    public function setCode(int $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * 功能：返回消息编码
     * 修改日期：2019/8/10
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * 功能：当成字符串输出
     * 修改日期：2019/8/13
     *
     * @return string
     */
    public function __toString()
    {
        return $this->message;
    }
}
