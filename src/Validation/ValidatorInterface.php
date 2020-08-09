<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：验证器接口
 * 作    者：zhaoy
 * 修改日期：2019/8/13
 */


namespace Snow\Validation\Validation;

interface ValidatorInterface
{

    /**
     * 功能：检查是否定义了选项
     * 修改日期：2019/8/10
     *
     * @param string $key 选项key
     * @return bool
     */
    public function hasOption(string $key);

    /**
     * 功能：获取选项值
     * 修改日期：2019/8/10
     *
     * @param string $key 选项key
     * @param mixed $defaultValue 默认值
     * @return mixed
     */
    public function getOption(string $key, $defaultValue = null);

    /**
     * 功能：执行验证
     * 修改日期：2019/8/10
     *
     * @param \Snow\Validation\Validation $validation 验证类
     * @param array|string $attribute 字段
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $attribute);
}
