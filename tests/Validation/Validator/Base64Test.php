<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Base64单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/4
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator\Base64;
use PHPUnit\Framework\TestCase;

class Base64Test extends TestCase
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
        $validation->add('base64_str', new Base64());
        $group = $validation->validate([
            'base64_str' => 'aaasssc'
        ]);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate([
            'base64_str' => base64_encode('123456')
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
            'str1' => 'str1必须是Base64格式串',
            'str2' => 'str2不是Base64格式串',
        ];
        $validation->add([
            'str1',
            'str2',
        ], new Base64(['message' => $defaultMsg]));

        $group = $validation->validate([
            'str1' => base64_encode('123'),
            'str2' => base64_encode('456'),
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate(['str2' => base64_encode('test')]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['str1']);

        $group = $validation->validate([
            'str1' => 'saas2;:s--',
            'str2' => 'test--',
        ]);
        $this->assertEquals($group->count(), 2);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['str1']);
        $this->assertEquals($group[1]->getMessage(), $defaultMsg['str2']);
    }
}
