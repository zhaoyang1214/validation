<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Email单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/7
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{

    /**
     * 功能：测试单个字段验证
     * 修改日期：2019/9/7
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleField()
    {
        $validation = new Validation();
        $validation->add('email', new Email());
        $group = $validation->validate(['email' => 'zhaoy.com']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['email' => 'zhaoy@2345.com']);
        $this->assertEquals($group->count(), 0);
    }

    /**
     * 功能：测试多个字段验证
     * 修改日期：2019/9/7
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testMultipleField()
    {
        $validation = new Validation();
        $defaultMsg = [
            'email1' => '第一个邮箱错误',
            'email2' => '第二个邮箱错误',
        ];
        $validation->add([
            'email1',
            'email2',
        ], new Email([
            'message' => $defaultMsg
        ]));

        $group = $validation->validate([
            'email1' => 'zhaoy@2345.com',
            'email2' => 'z.y@gmail.com',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'email1' => 'test@',
            'email2' => 'test@test.com',
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['email1']);

        $group = $validation->validate([
            'email1' => 'test@.com',
            'email2' => 'test@test.',
        ]);
        $this->assertEquals($group->count(), 2);
        
        $messages = new Group([
            new Message($defaultMsg['email1'], 'email1', Email::TYPE),
            new Message($defaultMsg['email2'], 'email2', Email::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
