<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：Ip单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/8
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\Ip;
use PHPUnit\Framework\TestCase;

class IpTest extends TestCase
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
        $validation->add('ip', new Ip());
        $group = $validation->validate(['ip' => '3..115.201.2']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['ip' => '120.27.241.14']);
        $this->assertEquals($group->count(), 0);
        $group = $validation->validate(['ip' => 'AD80:0000:0000:0000:ABAA:0000:00C2:0002']);
        $this->assertEquals($group->count(), 0);
    }

    /**
     * 功能：测试单个字段验证，IPv4
     * 修改日期：2019/9/8
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleFieldIPv4()
    {
        $validation = new Validation();
        $validation->add('ip', new Ip([
            Ip::OPT_VERSION => Ip::VERSION_4
        ]));
        $group = $validation->validate(['ip' => '120.27.241.14']);
        $this->assertEquals($group->count(), 0);
        $group = $validation->validate(['ip' => 'AD80:0000:0000:0000:ABAA:0000:00C2:0002']);
        $this->assertEquals($group->count(), 1);
    }

    /**
     * 功能：测试单个字段验证，IPv6
     * 修改日期：2019/9/8
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleFieldIPv6()
    {
        $validation = new Validation();
        $validation->add('ip', new Ip([
            Ip::OPT_VERSION => Ip::VERSION_6
        ]));
        $group = $validation->validate(['ip' => '120.27.241.14']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['ip' => 'AD80:0000:0000:0000:ABAA:0000:00C2:0002']);
        $this->assertEquals($group->count(), 0);
    }

    /**
     * 功能：测试单个字段验证，私有地址
     * 修改日期：2019/9/8
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleFieldAllowPrivate()
    {
        $validation = new Validation();
        $validation->add('ip1', new Ip());
        $validation->add('ip2', new Ip([
            Ip::OPT_ALLOW_PRIVATE => true
        ]));
        $group = $validation->validate([
            'ip1' => '192.168.10.10',
            'ip2' => '192.168.10.10',
        ]);
        $this->assertEquals($group->count(), 1);
    }

    /**
     * 功能：测试单个字段验证，保留的地址
     * 修改日期：2019/9/8
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleFieldAllowReserved()
    {
        $validation = new Validation();
        $validation->add('ip1', new Ip());
        $validation->add('ip2', new Ip([
            Ip::OPT_ALLOW_RESERVED => true
        ]));
        $group = $validation->validate([
            'ip1' => '127.0.0.1',
            'ip2' => '127.0.0.1',
        ]);
        $this->assertEquals($group->count(), 1);
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
            'ip1' => 'ip1错误',
            'ip2' => 'ip2错误',
        ];
        $validation->add([
            'ip1',
            'ip2',
        ], new Ip([
            Ip::OPT_MESSAGE => $defaultMsg
        ]));

        $group = $validation->validate([
            'ip1' => '120.27.241.14',
            'ip2' => '120.27.241.15',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'ip1' => '120.27.241.14',
            'ip2' => '127.0.0.1',
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['ip2']);

        $group = $validation->validate([
            'ip1' => '0.0.0.0',
            'ip2' => '169.254.0.0',
        ]);
        $this->assertEquals($group->count(), 2);

        $messages = new Group([
            new Message($defaultMsg['ip1'], 'ip1', Ip::TYPE),
            new Message($defaultMsg['ip2'], 'ip2', Ip::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
