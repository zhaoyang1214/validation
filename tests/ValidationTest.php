<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Validation单元测试
 * 作    者：zhaoy
 * 修改日期：2019/9/3
 */


namespace Snow\Validation\tests;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator\Alnum;
use Snow\Validation\Validation\Validator\Alpha;
use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    /**
     * 功    能：测试单字段添加单规则
     * 修改日期：2019/9/3
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testAddSingleField()
    {
        $validation = new Validation();
        $validation->add('username', new Alnum());
        $group = $validation->validate(['username' => 'saas2;:']);
        $this->assertEquals($group->count(), 1);
    }

    /**
     * 功    能：测试多字段添加单规则
     * 修改日期：2019/9/3
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testAddMultipleField()
    {
        $validation = new Validation();
        $validation->add(['username', 'name'], new Alnum());
        $group = $validation->validate([
            'username' => 'saas2;:',
            'name' => 's[paa',
        ]);
        $this->assertEquals($group->count(), 2);
    }

    /**
     * 功    能：测试单字段添加多规则
     * 修改日期：2019/9/3
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testRulesSingleField()
    {
        $validation = new Validation();
        $validation->rules('username', [
            new Alnum(),
            new Alpha(),
        ]);
        $group = $validation->validate(['username' => 'saas2;:s--']);
        $this->assertEquals($group->count(), 2);
    }

    /**
     * 功    能：测试多字段添加多规则
     * 修改日期：2019/9/3
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testRulesMultipleField()
    {
        $validation = new Validation();
        $validation->rules(['username', 'name'], [new Alnum(), new Alpha()]);
        $group = $validation->validate(['username' => 'saas2;:s--']);
        $this->assertEquals($group->count(), 4);
    }

    /**
     * 功    能：测试设置默认消息提示
     * 修改日期：2019/9/3
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSetDefaultMessages()
    {
        $validation = new Validation();
        $validation->setDefaultMessages([
            Alnum::TYPE => ':field只能为字母与数字',
            Alpha::TYPE => ':field只能为字母',
        ]);
        $validation->rules('username', [new Alnum(), new Alpha()]);
        $group = $validation->validate(['username' => 'saas2;:s--']);

        $messages = new Group([
            new Message('username只能为字母与数字', 'username', Alnum::TYPE),
            new Message('username只能为字母', 'username', Alpha::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }

    /**
     * 功    能：测试设置标签
     * 修改日期：2019/9/3
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSetLabels()
    {
        $validation = new Validation();
        $validation->setLabels(['username' => '用户名']);
        $validation->add('username', new Alnum([
            'message' => ':field只能为字母与数字'
        ]));
        $validation->add('username', new Alpha());
        $group = $validation->validate(['username' => 'saas2;:s--']);

        $messages = new Group([
            new Message('用户名只能为字母与数字', 'username', Alnum::TYPE),
            new Message('用户名必须只包含字母', 'username', Alpha::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }

    /**
     * 功    能：测试空值
     * 修改日期：2019/9/3
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testAllowEmptyOption()
    {
        $validation = new Validation();
        $validation->add('username', new Alnum(['allowEmpty' => true]));
        $validation->add('name', new Alnum(['allowEmpty' => [0, false]]));
        $validation->add('email', new Alnum());

        $group = $validation->validate([
            'username' => null, // true
            'name' => null, // false
            'email' => '', // false
        ]);
        $this->assertEquals($group->count(), 2);

        $group = $validation->validate([
            'username' => false,
            'name' => 0,
            'email' => '111',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'username' => 0, // true
            'name' => false, // true
            'email' => null, // false
        ]);
        $this->assertEquals($group->count(), 1);
    }

    /**
     * 功    能：测试失败后停止验证
     * 修改日期：2019/9/3
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testCancelOnFailOption()
    {
        $validation = new Validation();
        $validation->add(['username', 'name', 'email'], new Alnum([
            Alnum::OPT_CANCEL_ON_FAIL => true
        ]));
        $group = $validation->validate([
            'username' => 'aaaa', // true
            'name' => 'bb;bb', // false
            'email' => 'cc，cc',
        ]);

        $messages = new Group([
            new Message('name必须只包含字母和数字', 'name', Alnum::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }

    /**
     * 功    能：测试消息提示、标签、code、选项
     * 修改日期：2019/9/3
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testMessageAndLabelAndCodeOption()
    {
        $validation = new Validation();
        $validation->add('username', new Alnum([
            'message' => ':field必须为数字和字母',
            'label' => '用户名',
            'code' => 1,
        ]));
        $group = $validation->validate(['username' => 'aa;;;aa']);

        $messages = new Group([
            new Message('用户名必须为数字和字母', 'username', Alnum::TYPE, 1),
        ]);
        $this->assertEquals($group, $messages);
    }

    /**
     * 功能：测试获取值
     * 修改日期：2020/3/16
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testGetValue()
    {
        $validation = new Validation();
        $data = [
            'username' => 'saas2;:',
            'extra' => [
                'start_hour' => 0,
                'end_hour' => 23,
            ],
            'statistics' => [
                'send' => [
                    'total_clicks' => 'aaa',
                    'total_exposure' => 'bbb'
                ]
            ],
        ];
        $validation->setData($data);
        $this->assertEquals($validation->getValue('username'), $data['username']);
        $this->assertEquals($validation->getValue('extra.start_hour'), $data['extra']['start_hour']);
        $this->assertEquals($validation->getValue('extra.end_hour'), $data['extra']['end_hour']);
        $this->assertEquals($validation->getValue('statistics.send.total_clicks'), $data['statistics']['send']['total_clicks']);
        $this->assertEquals($validation->getValue('statistics.send.total_exposure'), $data['statistics']['send']['total_exposure']);
    }
}
