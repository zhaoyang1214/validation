<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：一组验证消息
 * 作    者：zhaoy
 * 修改日期：2019/8/13
 */


namespace Snow\Validation\Validation;

class Group implements \Countable, \ArrayAccess, \Iterator
{
    protected $position = 0;

    /**
     * @var \Snow\Validation\Validation\MessageInterface[] 一组消息对象
     */
    protected $messages;

    /**
     * Group constructor.
     * @param array|null $messages 消息对象数组
     */
    public function __construct(array $messages = [])
    {
        $this->messages = $messages;
    }

    /**
     * 功能：返回列表中的消息数量
     * 修改日期：2019/8/10
     *
     * @return int
     */
    public function count()
    {
        return count($this->messages);
    }

    /**
     * 功能：使用数组语法获取消息的属性
     * 修改日期：2019/8/10
     *
     * @param int $index 索引
     * @return mixed
     */
    public function offsetGet($index)
    {
        return $this->messages[$index] ?? false;
    }

    /**
     * 功能：使用数组语法设置属性
     * 修改日期：2019/8/10
     *
     * @param int $index 索引
     * @param \Snow\Validation\Validation\MessageInterface $message 消息对象
     * @throws \Snow\Validation\Validation\Exception
     * @return void
     */
    public function offsetSet($index, $message)
    {
        if (!($message instanceof MessageInterface)) {
            throw new Exception('The message must be an \Snow\Validation\Validation\MessageInterface');
        }
        $this->messages[$index] = $message;
    }

    /**
     * 功能：检查是否存在索引
     * 修改日期：2019/8/10
     *
     * @param int $index 索引
     * @return bool
     */
    public function offsetExists($index)
    {
        return isset($this->messages[$index]);
    }

    /**
     * 功能：从列表中删除一条消息
     * 修改日期：2019/8/10
     *
     * @param int $index 索引
     * @return void
     */
    public function offsetUnset($index)
    {
        if (isset($this->messages[$index])) {
            array_splice($this->messages, $index, 1);
        }
    }

    /**
     * 功能：添加消息
     * 修改日期：2019/8/11
     *
     * @param \Snow\Validation\Validation\MessageInterface $message 消息对象
     * @return void
     */
    public function appendMessage(MessageInterface $message)
    {
        $this->messages[] = $message;
    }

    /**
     * 功能：添加一个消息数组
     * 修改日期：2019/8/11
     *
     * @param \Snow\Validation\Validation\MessageInterface[] $messages 一组消息
     * @throws \Snow\Validation\Validation\Exception
     * @return void
     */
    public function appendMessages(array $messages)
    {
        foreach ($messages as $message) {
            if (!($message instanceof MessageInterface)) {
                throw new Exception('消息必须是\Snow\Validation\Validation\MessageInterface');
            }
        }
        $this->messages = array_merge($messages, $this->messages);
    }

    /**
     * 功能：按字段名筛选消息组
     * 修改日期：2019/8/13
     *
     * @param string|array $fieldName 字段名
     * @return array
     */
    public function filter($fieldName)
    {
        $messages = $this->messages;
        $filtered = [];
        foreach ($messages as $message) {
            if (method_exists($message, 'getField') && $fieldName == $message->getField()) {
                $filtered[] = $message;
            }
        }
        return $filtered;
    }

    /**
     * 功能：回滚内部迭代器
     * 修改日期：2019/8/11
     *
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * 功能：返回迭代器中的当前消息
     * 修改日期：2019/8/11
     *
     * @return \Snow\Validation\Validation\MessageInterface
     */
    public function current()
    {
        return $this->messages[$this->position];
    }

    /**
     * 功能：返回迭代器中的当前position
     * 修改日期：2019/8/11
     *
     * @return mixed
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * 功能：将内部迭代指针移动到下一个位置
     * 修改日期：2019/8/11
     *
     * @return void
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * 功能：检查迭代器中的当前消息是否有效
     * 修改日期：2019/8/11
     *
     * @return bool
     */
    public function valid()
    {
        return isset($this->messages[$this->position]);
    }
}
