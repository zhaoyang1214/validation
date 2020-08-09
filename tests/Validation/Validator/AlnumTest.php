<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Alnum单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/2
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Alnum;
use PHPUnit\Framework\TestCase;

class AlnumTest extends TestCase
{

    /**
     * 功能：测试单个字段验证
     * 修改日期：2019/9/2
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleField()
    {
        $validation = new Validation();
        $validation->add('username', new Alnum());
        $group = $validation->validate([
            'username' => 'saas2;:sssaa'
        ]);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate([
            'username' => 'test123'
        ]);
        $this->assertEquals($group->count(), 0);
    }

    /**
     * 功能：测试多个字段验证
     * 修改日期：2019/9/2
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testMultipleField()
    {
        $validation = new Validation();
        $defaultMsg = [
            'username' => '用户名必须只包含字母和数字',
            'name' => '姓名必须只包含字母和数字',
        ];
        $validation->add([
            'username',
            'name',
        ], new Alnum(['message' => $defaultMsg]));

        $group = $validation->validate([
            'username' => 'test1',
            'name' => 'test2',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate(['username' => 'test1']);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['name']);

        $group = $validation->validate([
            'username' => 'saas2;:s--',
            'name' => 'test--',
        ]);
        $this->assertEquals($group->count(), 2);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['username']);
        $this->assertEquals($group[1]->getMessage(), $defaultMsg['name']);
    }

    /**
     * 功能：
     * 修改日期：2020/3/17
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testValidateMultipleArray()
    {
        $validation = new Validation();
        $validation->add([
            'statistics.send.total_clicks',
            'statistics.send.total_exposure',
        ], new Alnum([
            Alnum::OPT_MESSAGE => [
                'statistics.send.total_clicks' => '统计点击数必须只包含字母和数字',
                'statistics.send.total_exposure' => '统计曝光必须只包含字母和数字',
            ]
        ]));
        $group = $validation->validate([
            'statistics' => [
                'send' => [
                    'total_clicks' => 'aaa',
                    'total_exposure' => 'bbb--'
                ]
            ],
        ]);
        $this->assertEquals($group->count(), 1);
    }
}
