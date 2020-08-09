<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Callback单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/5
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\Alnum;
use Snow\Validation\Validation\Validator\Callback;
use Snow\Validation\Validation\Validator\StringLength;
use PHPUnit\Framework\TestCase;

class CallbackTest extends TestCase
{

    /**
     * 功能：测试单个字段验证,callback返回boolean
     * 修改日期：2019/9/5
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleFieldReturnBoolean()
    {
        $validation = new Validation();
        $validation->add('username', new Callback([
            Callback::OPT_CALLBACK => function ($data) {
                if ($data['username'] == 'admin') {
                    return false;
                }
                return true;
            }
        ]));
        $group = $validation->validate([
            'username' => 'admin'
        ]);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate([
            'username' => 'testAdmin'
        ]);
        $this->assertEquals($group->count(), 0);
    }

    /**
     * 功能：测试单个字段验证,callback返回Validator
     * 修改日期：2019/9/5
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleFieldReturnValidator()
    {
        $validation = new Validation();
        $validation->add('username', new Callback([
            Callback::OPT_CALLBACK => function ($data) {
                if ($data['username'] == 'admin') {
                    return false;
                }
                return new Alnum();
            }
        ]));
        $group = $validation->validate([
            'username' => 'admin--'
        ]);
        $this->assertEquals($group->count(), 1);
        $messages = new Group([
            new Message('username必须只包含字母和数字', 'username', Alnum::TYPE),
        ]);
        $this->assertEquals($group, $messages);
        $group = $validation->validate([
            'username' => 'testAdmin123'
        ]);
        $this->assertEquals($group->count(), 0);
    }

    /**
     * 功能：测试多个字段验证,callback返回Boolean
     * 修改日期：2019/9/5
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testMultipleFieldReturnBoolean()
    {
        $validation = new Validation();
        $defaultMsg = [
            'name' => '姓名验证失败',
            'username' => '用户名验证失败',
        ];
        $validation->add([
            'name',
            'username',
        ], new Callback([
            Callback::OPT_CALLBACK => function ($data) {
                if ($data['username'] == $data['name']) {
                    return false;
                }
                return true;
            }
        ]));

        $group = $validation->validate([
            'name' => 'admin',
            'username' => 'admin111',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'name' => 'admin',
            'username' => 'admin',
        ]);
        $this->assertEquals($group->count(), 2);
        $messages = new Group([
            new Message($defaultMsg['name'], 'name', Callback::TYPE),
            new Message($defaultMsg['username'], 'username', Callback::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }

    /**
     * 功能：测试多个字段验证,callback返回Validator
     * 修改日期：2019/9/5
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testMultipleFieldReturnValidator()
    {
        $validation = new Validation();
        $validation->add([
            'name',
            'username',
        ], new Callback([
            Callback::OPT_CALLBACK => function ($data) {
                if ($data['username'] == $data['name']) {
                    return false;
                }
                return new StringLength([
                    'min' => 5,
                    'max' => [
                        'name' => 8,
                        'username' => 12,
                    ],
                ]);
            }
        ]));

        $group = $validation->validate([
            'name' => 'abcde',
            'username' => 'qwertyuio',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'name' => 'mnbvcxzlkjhg',
            'username' => 'test',
        ]);
        $this->assertEquals($group->count(), 2);
        $messages = new Group([
            new Message('name长度不能超过8个字符', 'name', StringLength\MAX::TYPE),
            new Message('username长度不能少于5个字符', 'username', StringLength\Min::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
