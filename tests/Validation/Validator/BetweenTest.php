<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Between单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/4
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Between;
use PHPUnit\Framework\TestCase;

class BetweenTest extends TestCase
{

    /**
     * 功能：测试单个字段验证
     * 修改日期：2019/9/4
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleField()
    {
        $validation = new Validation();
        $validation->add('age', new Between([
            Between::OPT_MINIMUM => 20,
            Between::OPT_MAXIMUM => 30,
        ]));
        $group = $validation->validate([
            'age' => 37
        ]);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate([
            'age' => 27
        ]);
        $this->assertEquals($group->count(), 0);
    }

    /**
     * 功能：测试多个字段验证
     * 修改日期：2019/9/4
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testMultipleField()
    {
        $validation = new Validation();
        $defaultMsg = [
            'age' => '年龄必须在20到30岁之间',
            'price' => '价格必须在11到99范围内',
        ];
        $validation->add([
            'age',
            'price',
        ], new Between([
            Between::OPT_MINIMUM => [
                'age' => 20,
                'price' => 11,
            ],
            Between::OPT_MAXIMUM => [
                'age' => 30,
                'price' => 99,
            ],
            Between::OPT_MESSAGE => $defaultMsg
        ]));

        $group = $validation->validate([
            'age' => 20,
            'price' => 99,
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'age' => 30,
            'price' => 100,
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['price']);

        $group = $validation->validate([
            'age' => 19,
            'price' => 100,
        ]);
        $this->assertEquals($group->count(), 2);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['age']);
        $this->assertEquals($group[1]->getMessage(), $defaultMsg['price']);
    }
}
