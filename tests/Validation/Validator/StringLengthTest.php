<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：StringLength单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/8
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\StringLength;
use PHPUnit\Framework\TestCase;

class StringLengthTest extends TestCase
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
        $validation->add('username', new StringLength([
            StringLength::OPT_EQUAL => 8
        ]));
        $group = $validation->validate(['username' => 'Az']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['username' => 'admin123']);
        $this->assertEquals($group->count(), 0);
    }

    /**
     * 功能：测试单个字段验证，不包含最小值
     * 修改日期：2019/9/8
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleFielNotIncludedMin()
    {
        $validation = new Validation();
        $validation->add('username', new StringLength([
            StringLength::OPT_MIN => 6,
            StringLength::OPT_INCLUDE_MIN => false,
        ]));
        $group = $validation->validate(['username' => '123456']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['username' => '1234567']);
        $this->assertEquals($group->count(), 0);
    }

    /**
     * 功能：测试单个字段验证，不包含最大值
     * 修改日期：2019/9/8
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleFielNotIncludedMax()
    {
        $validation = new Validation();
        $validation->add('username', new StringLength([
            StringLength::OPT_MAX => 10,
            StringLength::OPT_INCLUDE_MAX => false,
        ]));
        $group = $validation->validate(['username' => '1234567890']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['username' => '123456789']);
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
        $validation->add([
            'username',
            'password',
        ], new StringLength([
            StringLength::OPT_MIN => 6,
            StringLength::OPT_MAX => [
                'username' => 8,
                'password' => 10,
            ],
        ]));

        $group = $validation->validate([
            'username' => 'abcABC',
            'password' => 'abcdABCD12',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'username' => 'abc',
            'password' => 'ABCDEFgh',
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), 'username长度不能少于6个字符');

        $group = $validation->validate([
            'username' => 'abc',
            'password' => 'abcdefghigoiuyt',
        ]);
        $this->assertEquals($group->count(), 2);

        $messages = new Group([
            new Message('username长度不能少于6个字符', 'username', StringLength\Min::TYPE),
            new Message('password长度不能超过10个字符', 'password', StringLength\Max::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
