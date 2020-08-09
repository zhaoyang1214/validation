<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：PresenceOf单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/8
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\PresenceOf;
use PHPUnit\Framework\TestCase;

class PresenceOfTest extends TestCase
{

    /**
     * 功能：测试单个字段验证
     * 修改日期：2019/9/8
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleField()
    {
        $validation = new Validation();
        $validation->add('name', new PresenceOf());
        $group = $validation->validate(['name' => '']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['name' => '0']);
        $this->assertEquals($group->count(), 0);
    }

    /**
     * 功能：测试多个字段验证
     * 修改日期：2019/9/8
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testMultipleField()
    {
        $validation = new Validation();
        $defaultMsg = [
            'name' => 'name不允许为空',
            'username' => 'username不允许为空',
        ];
        $validation->add([
            'name',
            'username',
        ], new PresenceOf([
            PresenceOf::OPT_MESSAGE => $defaultMsg
        ]));

        $group = $validation->validate([
            'name' => 'test',
            'username' => 'test',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'name' => null,
            'username' => 'tttt',
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['name']);

        $group = $validation->validate([
            'name' => '',
            'username' => null,
        ]);
        $this->assertEquals($group->count(), 2);

        $messages = new Group([
            new Message($defaultMsg['name'], 'name', PresenceOf::TYPE),
            new Message($defaultMsg['username'], 'username', PresenceOf::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
