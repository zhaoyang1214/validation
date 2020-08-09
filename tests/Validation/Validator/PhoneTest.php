<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Phone单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/8
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\Phone;
use PHPUnit\Framework\TestCase;

class PhoneTest extends TestCase
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
        $validation->add('phone', new Phone());
        $group = $validation->validate(['phone' => '12956539654']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['phone' => '15956503644']);
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
            'phone1' => 'phone1错误',
            'phone2' => 'phone2错误',
        ];
        $validation->add([
            'phone1',
            'phone2',
        ], new Phone([
            Phone::OPT_MESSAGE => $defaultMsg
        ]));

        $group = $validation->validate([
            'phone1' => '13456789321',
            'phone2' => '19945679876',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'phone1' => '10365498765',
            'phone2' => '13045678999',
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['phone1']);

        $group = $validation->validate([
            'phone1' => '10032224566',
            'phone2' => '11000000000',
        ]);
        $this->assertEquals($group->count(), 2);
        
        $messages = new Group([
            new Message($defaultMsg['phone1'], 'phone1', Phone::TYPE),
            new Message($defaultMsg['phone2'], 'phone2', Phone::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
