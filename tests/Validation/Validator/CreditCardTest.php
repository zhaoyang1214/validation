<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：CreditCard单元测试
 * 作    者：赵阳
 * 修改日期：2019/9/5
 */


namespace Snow\Validation\tests\Validation\Validator;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Group;
use Snow\Validation\Validation\Validator\CreditCard;
use PHPUnit\Framework\TestCase;

class CreditCardTest extends TestCase
{

    /**
     * 功能：测试单个字段验证
     * 修改日期：2019/9/5
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testSingleField()
    {
        $validation = new Validation();
        $validation->add('credit_card', new CreditCard());
        $group = $validation->validate(['credit_card' => '3654897321']);
        $this->assertEquals($group->count(), 1);
        $group = $validation->validate(['credit_card' => '4716484684110789']);
        $this->assertEquals($group->count(), 0);
    }

    /**
     * 功能：测试多个字段验证
     * 修改日期：2019/9/5
     *
     * @throws Validation\Exception
     * @return void
     */
    public function testMultipleField()
    {
        $validation = new Validation();
        $defaultMsg = [
            'visa' => '信用卡号不合法',
            'mastercard' => '万事达信用卡卡号不合法',
        ];
        $validation->add([
            'visa',
            'mastercard',
        ], new CreditCard([
            'message' => $defaultMsg
        ]));

        $group = $validation->validate([
            'visa' => '4539450486511644',
            'mastercard' => '5199005501975758',
        ]);
        $this->assertEquals($group->count(), 0);

        $group = $validation->validate([
            'visa' => '4539450486511644',
            'mastercard' => '5199005501975750',
        ]);
        $this->assertEquals($group->count(), 1);
        $this->assertEquals($group[0]->getMessage(), $defaultMsg['mastercard']);

        $group = $validation->validate([
            'visa' => '4539450486511640',
            'mastercard' => '5199005501975750',
        ]);
        $this->assertEquals($group->count(), 2);
        
        $messages = new Group([
            new Message($defaultMsg['visa'], 'visa', CreditCard::TYPE),
            new Message($defaultMsg['mastercard'], 'mastercard', CreditCard::TYPE),
        ]);
        $this->assertEquals($group, $messages);
    }
}
