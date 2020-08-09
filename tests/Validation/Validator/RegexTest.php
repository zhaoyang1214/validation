<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Regex单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/8
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\Regex;
use PHPUnit\Framework\TestCase;

class RegexTest extends TestCase
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
        $validation->add('username', new Regex([
            Regex::OPT_PATTERN => '/^[a-z]+$/'
        ]));
        $group = $validation->validate(['username' => 'Az']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['username' => 'zhao']);
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
            'username' => '用户名格式错误',
            'password' => '密码格式错误',
        ];
        $validation->add([
            'username',
            'password',
        ], new Regex([
            Regex::OPT_PATTERN => [
                'username' => '/^[a-zA-Z]{6,16}$/',
                'password' => '/^[a-zA-Z\d]{8,18}$/',
            ],
            Regex::OPT_MESSAGE => $defaultMsg
        ]));

        $group = $validation->validate([
            'username' => 'abcABC',
            'password' => 'abcdABCD123',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'username' => 'abcX123',
            'password' => 'ABCDEFgh123',
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['username']);

        $group = $validation->validate([
            'username' => 'abc',
            'password' => '456',
        ]);
        $this->assertEquals($group->count(), 2);
        
        $messages = new Group([
            new Message($defaultMsg['username'], 'username', Regex::TYPE),
            new Message($defaultMsg['password'], 'password', Regex::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
