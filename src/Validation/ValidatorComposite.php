<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：组合验证器抽象类
 * 作    者：zhaoy
 * 修改日期：2019/9/1
 */


namespace Snow\Validation\Validation;

abstract class ValidatorComposite extends Validator
{
    /**
     * @var \Snow\Validation\Validation\Validator[]
     */
    protected $validators = [];

    /**
     * 功能：获取验证器
     * 修改日期：2019/9/1
     *
     * @return array
     */
    public function getValidators(): array
    {
        return $this->validators;
    }

    /**
     * 功能：调用验证器
     * 修改日期：2019/9/1
     *
     * @param \Snow\Validation\Validation $validation 验证类
     * @param array|string $field 字段
     * @throws Exception
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        $validators = $this->getValidators();
        if (!count($validators)) {
            throw new Exception(get_class($this) . '没有可用的验证器');
        }
        foreach ($validators as $validator) {
            if ($validator->validate($validation, $field) === false) {
                return false;
            }
        }
        return true;
    }
}
