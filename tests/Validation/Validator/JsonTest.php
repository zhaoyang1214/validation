<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Json单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/8
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\Json;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
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
        $validation->add('json', new Json());
        $group = $validation->validate(['json' => '{aa:bb:cc}']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['json' => '{"a":"aaa","b":"bbb"}']);
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
            'json1' => 'json1格式错误',
            'json2' => 'json2格式错误',
        ];
        $validation->add([
            'json1',
            'json2',
        ], new Json([
            Json::OPT_MESSAGE => $defaultMsg
        ]));

        $group = $validation->validate([
            'json1' => '[{"a":"aaa"}]',
            'json2' => '{"b":"bb"}',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'json1' => '[]',
            'json2' => '{{}}',
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['json2']);

        $group = $validation->validate([
            'json1' => '{},{}',
            'json2' => '[],[]',
        ]);
        $this->assertEquals($group->count(), 2);

        $messages = new Group([
            new Message($defaultMsg['json1'], 'json1', Json::TYPE),
            new Message($defaultMsg['json2'], 'json2', Json::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
