<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Confirmation单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/5
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\Confirmation;
use PHPUnit\Framework\TestCase;

class ConfirmationTest extends TestCase
{

    /**
     * 功能：测试单个字段验证
     * 修改日期：2019/9/5
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleField()
    {
        $validation = new Validation();
        $validation->add('password', new Confirmation([
            Confirmation::OPT_WITH => 'confirm_password',
        ]));
        $group = $validation->validate([
            'password' => '123456',
            'confirm_password' => '654321',
        ]);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate([
            'password' => '123456',
            'confirm_password' => '123456',
        ]);
        $this->assertEquals($group->count(), 0);
    }

    /**
     * 功能：测试多个字段验证
     * 修改日期：2019/9/5
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testMultipleField()
    {
        $validation = new Validation();
        $defaultMsg = [
            'password' => '两次密码不一致',
            'idcard' => '两次身份证号不一致',
        ];
        $validation->add([
            'password',
            'idcard',
        ], new Confirmation([
            Confirmation::OPT_WITH => [
                'password' => 'password2',
                'idcard' => 'idcard2',
            ],
            Confirmation::OPT_MESSAGE => $defaultMsg
        ]));

        $group = $validation->validate([
            'password' => 'abcdef',
            'password2' => 'abcdef',
            'idcard' => '987654332190',
            'idcard2' => '987654332190',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'password' => 'abcdef',
            'password2' => 'abcdef',
            'idcard' => '987654332190',
            'idcard2' => null,
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['idcard']);

        $group = $validation->validate([
            'password' => 'abcdef',
            'password2' => '32154',
            'idcard' => '987654332190',
            'idcard2' => null,
        ]);
        $this->assertEquals($group->count(), 2);

        $messages = new Group([
            new Message($defaultMsg['password'], 'password', Confirmation::TYPE),
            new Message($defaultMsg['idcard'], 'idcard', Confirmation::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
