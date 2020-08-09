<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Digit单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/7
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\Digit;
use PHPUnit\Framework\TestCase;

class DigitTest extends TestCase
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
        $validation->add('credit_card', new Digit());
        $group = $validation->validate(['credit_card' => '3654897321abc']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['credit_card' => '4716484684110789']);
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
            'amount' => '数量错误',
            'money' => '金额错误',
        ];
        $validation->add([
            'amount',
            'money',
        ], new Digit([
            'message' => $defaultMsg
        ]));

        $group = $validation->validate([
            'amount' => PHP_INT_MAX,
            'money' => 100,
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'amount' => '987654321',
            'money' => 100.0,
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['money']);

        $group = $validation->validate([
            'amount' => '321abc',
            'money' => '10.3',
        ]);
        $this->assertEquals($group->count(), 2);
        
        $messages = new Group([
            new Message($defaultMsg['amount'], 'amount', Digit::TYPE),
            new Message($defaultMsg['money'], 'money', Digit::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
