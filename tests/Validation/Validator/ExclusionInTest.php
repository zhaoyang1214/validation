<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：ExclusionIn单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/7
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\ExclusionIn;
use PHPUnit\Framework\TestCase;

class ExclusionInTest extends TestCase
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
        $validation->add('status', new ExclusionIn([
            ExclusionIn::OPT_DOMAIN => [0, 1, 2]
        ]));
        $group = $validation->validate(['status' => 1]);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['status' => 3]);
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
            'status' => '状态错误',
            'type' => '类型错误',
        ];
        $validation->add([
            'status',
            'type',
        ], new ExclusionIn([
            ExclusionIn::OPT_DOMAIN => [0, 1],
//            ExclusionIn::OPT_DOMAIN => [
//                'status' => [0, 1],
//                'type' => [0, 1],
//            ],
            ExclusionIn::OPT_STRICT => [
                'status' => true
            ],
            ExclusionIn::OPT_ALLOW_EMPTY => [null],
            ExclusionIn::OPT_MESSAGE => $defaultMsg,
        ]));

        $group = $validation->validate([
            'status' => 2,
            'type' => 3,
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'status' => false,
            'type' => false,
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['type']);

        $group = $validation->validate([
            'status' => 0,
            'type' => '0',
        ]);
        $this->assertEquals($group->count(), 2);
        
        $messages = new Group([
            new Message($defaultMsg['status'], 'status', ExclusionIn::TYPE),
            new Message($defaultMsg['type'], 'type', ExclusionIn::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
