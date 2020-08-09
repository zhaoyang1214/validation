<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：IDNumber单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/8
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\IDNumber;
use PHPUnit\Framework\TestCase;

class IDNumberTest extends TestCase
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
        $validation->add('idcard', new IDNumber());
        $group = $validation->validate(['idcard' => '110101199003654321']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['idcard' => '34130219920119936X']);
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
            'idcard1' => 'idcard1错误',
            'idcard2' => 'idcard2错误',
        ];
        $validation->add([
            'idcard1',
            'idcard2',
        ], new IDNumber([
            'message' => $defaultMsg
        ]));

        $group = $validation->validate([
            'idcard1' => '11010119900307627X',
            'idcard2' => '110101199303077784',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'idcard1' => '110101199003076277',
            'idcard2' => '341302199201191229',
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['idcard1']);

        $group = $validation->validate([
            'idcard1' => '341302199201198249',
            'idcard2' => '341302199201198501',
        ]);
        $this->assertEquals($group->count(), 2);

        $messages = new Group([
            new Message($defaultMsg['idcard1'], 'idcard1', IDNumber::TYPE),
            new Message($defaultMsg['idcard2'], 'idcard2', IDNumber::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
