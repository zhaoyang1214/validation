<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：消息组单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/2
 */


namespace Snow\Validation\tests\Validation;

use Snow\Validation\Validation\Exception;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Message;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    /**
     * 功能：测试count
     * 修改日期：2019/9/2
     *
     * @return void
     */
    public function testCountable()
    {
        $group = new Group();
        $this->assertEquals($group->count(), 0);
        $group = new Group([
            new Message('这是第1条错误消息', '字段1', 'type1', 1),
            new Message('这是第2条错误消息', '字段2', 'type2', 2),
        ]);
        $this->assertEquals($group->count(), 2);
        $this->assertEquals(count($group), 2);
    }

    /**
     * 功能：测试array操作
     * 修改日期：2019/9/2
     *
     * @throws Exception
     * @return void
     */
    public function testArrayAccess()
    {
        $group = new Group();
        $this->assertFalse($group->offsetGet(0));
        $group = new Group([
            new Message('这是第1条错误消息', '字段1', 'type1', 1),
            new Message('这是第2条错误消息', '字段2', 'type2', 2),
        ]);
        $this->assertEquals($group->offsetGet(1)->getMessage(), '这是第2条错误消息');
        $this->assertEquals($group[1]->getField(), '字段2');
        $group->offsetSet(2, new Message('这是3条错误消息', '字段3', 'type3'));
        $this->assertEquals($group->count(), 3);
        $this->assertTrue($group->offsetExists(0));
        $this->assertTrue(isset($group[0]));
        $this->assertFalse($group->offsetExists(3));
        $group->offsetUnset(1);
        $this->assertEquals($group->count(), 2);
    }

    /**
     * 功能：测试迭代器
     * 修改日期：2019/9/2
     *
     * @return void
     */
    public function testIterator()
    {
        $group = new Group([
            new Message('这是第1条错误消息', '字段1', 'type1', 1),
            new Message('这是第2条错误消息', '字段2', 'type2', 2),
        ]);
        $this->assertEquals($group->count(), 2);
        $this->assertTrue(isset($group[0]));
        $this->assertTrue(isset($group[1]));
        foreach ($group as $index => $message) {
            $this->assertEquals($group[$index]->getMessage(), $message->getMessage());
        }
    }

    /**
     * 功能：测试添加错误信息
     * 修改日期：2019/9/2
     *
     * @return void
     */
    public function testAppendMessage()
    {
        $group = new Group();
        $group->appendMessage(new Message('这是第1条错误消息', '字段1', 'type1', 1));
        $this->assertEquals($group->count(), 1);
    }

    /**
     * 功能：测试批量添加错误信息
     * 修改日期：2019/9/2
     *
     * @throws Exception
     * @return void
     */
    public function testAppendMessages()
    {
        $group = new Group();
        $group->appendMessages([
            new Message('这是第1条错误消息', '字段1', 'type1', 1),
            new Message('这是第2条错误消息', '字段2', 'type2', 2),
        ]);
        $this->assertEquals($group->count(), 2);
    }

    /**
     * 功能：测试根据字段过滤错误信息
     * 修改日期：2019/9/2
     *
     * @throws Exception
     * @return void
     */
    public function testFilter()
    {
        $group = new Group();
        $messages = [
            new Message('这是第1条错误消息', '字段1', 'type1', 1),
            new Message('这是第2条错误消息', '字段2', 'type2', 2),
        ];
        $group->appendMessages($messages);
        $message = $group->filter('字段2');
        $this->assertEquals(count($message), 1);
        $this->assertEquals($message[0], $messages[1]);
    }
}
