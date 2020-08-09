<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Alpha单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/4
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Alpha;
use PHPUnit\Framework\TestCase;

class AlphaTest extends TestCase
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
        $validation->add('username', new Alpha());
        $group = $validation->validate([
            'username' => 'saas2;:sssaa'
        ]);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate([
            'username' => 'admin'
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
            'username' => '用户名必须只包含字母',
            'name' => '姓名必须只包含字母',
        ];
        $validation->add([
            'username',
            'name',
        ], new Alpha(['message' => $defaultMsg]));

        $group = $validation->validate([
            'username' => 'admin',
            'name' => 'zhaoyang',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate(['username' => 'test']);
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
}
