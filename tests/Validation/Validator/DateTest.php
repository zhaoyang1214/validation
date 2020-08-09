<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Date单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/7
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{

    /**
     * 功能：测试单个字段验证
     * 修改日期：2019/9/7
     *
     * @throws Validation\Exception
     * @return void
     */
//    public function testSingleField()
//    {
//        $validation = new Validation();
//        $validation->add('birthday', new Date([
//            'format' => 'Ymd'
//        ]));
//        $group = $validation->validate(['birthday' => '2019907']);
//        $this->assertEquals($group->count(), 1);
//        $group = $validation->validate(['birthday' => '20190907']);
//        $this->assertEquals($group->count(), 0);
//        $group = $validation->validate(['birthday' => 20190907]);
//        $this->assertEquals($group->count(), 0);
//    }

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
            'birthday' => '生日格式错误',
            'start_time' => '起始时间错误',
        ];
        $validation->add([
            'birthday',
            'start_time',
        ], new Date([
            'format' => [
                'birthday' => 'Y-n-j',
                'start_time' => 'Y-m-d H:i:s',
            ],
            'message' => $defaultMsg,
        ]));

        $group = $validation->validate([
            'birthday' => '2019-9-7',
            'start_time' => '2019-09-07 15:00:00',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'birthday' => '2011-12-14',
            'start_time' => '2019-09-07 25:00:00',
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['start_time']);

        $group = $validation->validate([
            'birthday' => '2019-9-07',
            'start_time' => '2019-09-07 23:59:60',
        ]);
        $this->assertEquals($group->count(), 2);

        $messages = new Group([
            new Message($defaultMsg['birthday'], 'birthday', Date::TYPE),
            new Message($defaultMsg['start_time'], 'start_time', Date::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
