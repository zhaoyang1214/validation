<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Url单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/8
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
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
        $validation->add('url', new Url());
        $group = $validation->validate(['url' => 'www.2345.com']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['url' => 'http://www.2345.com']);
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
            'url1' => 'url1格式错误',
            'url2' => 'url2格式错误',
        ];
        $validation->add([
            'url1',
            'url2',
        ], new Url([
            Url::OPT_MESSAGE => $defaultMsg
        ]));

        $group = $validation->validate([
            'url1' => 'http://www.2345.com',
            'url2' => 'https://www.2345.com',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'url1' => 'http://',
            'url2' => 'http://2345.com',
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['url1']);

        $group = $validation->validate([
            'url1' => '//www.2345.com',
            'url2' => 'https://',
        ]);
        $this->assertEquals($group->count(), 2);

        $messages = new Group([
            new Message($defaultMsg['url1'], 'url1', Url::TYPE),
            new Message($defaultMsg['url2'], 'url2', Url::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
