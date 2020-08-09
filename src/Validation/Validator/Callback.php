<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：调用用户函数进行验证
 * 作    者：zhaoy
 * 修改日期：2019/8/17
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator;

class Callback extends Validator
{
    const TYPE = 'Callback';

    /**
     * @var string 回调函数选项参数,支持call_user_func所有用法
     */
    const OPT_CALLBACK = 'callback';

    protected $message = ':field验证失败';

    /**
     * 功能：调用用户函数进行验证
     * 修改日期：2019/8/17
     *
     * @param \Snow\Validation\Validation $validation 验证调度器
     * @param string $field 字段
     * @throws \Snow\Validation\Validation\Validator\Exception
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $callback = $this->prepareOption(self::OPT_CALLBACK, $field);
        if (!is_callable($callback, true)) {
            throw  new Exception(self::class . '验证器' . self::OPT_CALLBACK . '选项必须为合法的可调用结构');
        }
        $data = $validation->getData();
        $returnedValue = call_user_func($callback, $data);
        if (is_bool($returnedValue)) {
            if ($returnedValue) {
                return true;
            }
            $label = $this->prepareLabel($validation, $field);
            $message = $this->prepareMessage($validation, $field, self::TYPE);
            $code = $this->prepareCode($field);
            $message = strtr($message, [':field' => $label]);
            $validation->appendMessage(new Message($message, $field, self::TYPE, $code));
            return false;
        } elseif ($returnedValue instanceof Validator) {
            return $returnedValue->validate($validation, $field);
        }
        throw new Exception('Callback必须返回boolean或者Snow\Validation\\Validation\\Validator对象');
    }
}
