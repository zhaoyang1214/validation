<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Numericality单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/8
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\Numericality;
use PHPUnit\Framework\TestCase;

class NumericalityTest extends TestCase
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
        $validation->add('amount', new Numericality());
        $group = $validation->validate(['amount' => '321abc']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['amount' => '321']);
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
            'amount' => '数量错误',
            'money' => '金额错误',
        ];
        $validation->add([
            'amount',
            'money',
        ], new Numericality([
            'message' => $defaultMsg
        ]));

        $group = $validation->validate([
            'amount' => 123,
            'money' => 100.00,
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'amount' => '98765.43.21',
            'money' => 100.0,
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['amount']);

        $group = $validation->validate([
            'amount' => '321abc',
            'money' => '.30',
        ]);
        $this->assertEquals($group->count(), 2);

        $messages = new Group([
            new Message($defaultMsg['amount'], 'amount', Numericality::TYPE),
            new Message($defaultMsg['money'], 'money', Numericality::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
