<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Identical单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/8
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\Identical;
use PHPUnit\Framework\TestCase;

class IdenticalTest extends TestCase
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
        $validation->add('terms', new Identical([
            Identical::OPT_ACCEPTED => 'yes'
        ]));
        $group = $validation->validate(['terms' => 'no']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['terms' => 'yes']);
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
            'name' => '姓名必须为test',
            'username' => '用户名必须为admin',
        ];
        $validation->add([
            'name',
            'username',
        ], new Identical([
            Identical::OPT_VALUE => [
                'name' => 'test',
                'username' => 'admin',
            ],
            Identical::OPT_MESSAGE => $defaultMsg
        ]));

        $group = $validation->validate([
            'name' => 'test',
            'username' => 'admin',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'name' => 'test',
            'username' => 'admin1',
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['username']);

        $group = $validation->validate([
            'name' => 'test1',
            'username' => 'admin1',
        ]);
        $this->assertEquals($group->count(), 2);
        
        $messages = new Group([
            new Message($defaultMsg['name'], 'name', Identical::TYPE),
            new Message($defaultMsg['username'], 'username', Identical::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
