<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：验证字符串的长度范围
 * 作    者：zhaoy
 * 修改日期：2019/8/25
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Validator\StringLength\Equal as StringLengthEqual;
use Snow\Validation\Validation\Validator\StringLength\Max as StringLengthMax;
use Snow\Validation\Validation\Validator\StringLength\Min as StringLengthMin;
use Snow\Validation\Validation\ValidatorComposite;

class StringLength extends ValidatorComposite
{
    const OPT_MIN = 'min';

    const OPT_MESSAGE_MIN = 'messageMin';

    const OPT_INCLUDE_MIN = 'includedMin';

    const OPT_MAX = 'max';

    const OPT_MESSAGE_MAX = 'messageMax';

    const OPT_INCLUDE_MAX = 'includedMax';

    const OPT_EQUAL = 'equal';

    const OPT_MESSAGE_EQUAL = 'messageEqual';

    /**
     * StringLength constructor.
     * @param array $options 选项
     * @throws Exception
     */
    public function __construct(array $options = [])
    {
        if (empty($options)) {
            throw new Exception(self::class . '验证器选项不能为空');
        }
        foreach ($options as $key => $option) {
            if (strcasecmp($key, self::OPT_MIN) === 0) {
                $validator = new StringLengthMin([
                    StringLengthMin::OPT_LENGTH => $option,
                    StringLengthMin::OPT_MESSAGE => $options[self::OPT_MESSAGE_MIN] ?? null,
                    StringLengthMin::OPT_INCLUDED => $options[self::OPT_INCLUDE_MIN] ?? null,
                ]);
                unset($options[self::OPT_MIN], $options[self::OPT_MESSAGE_MIN], $options[self::OPT_INCLUDE_MIN]);
            } elseif (strcasecmp($key, self::OPT_MAX) === 0) {
                $validator = new StringLengthMax([
                    StringLengthMax::OPT_LENGTH => $option,
                    StringLengthMax::OPT_MESSAGE => $options[self::OPT_MESSAGE_MAX] ?? null,
                    StringLengthMax::OPT_INCLUDED => $options[self::OPT_INCLUDE_MAX] ?? null,
                ]);
                unset($options[self::OPT_MAX], $options[self::OPT_MESSAGE_MAX], $options[self::OPT_INCLUDE_MAX]);
            } elseif (strcasecmp($key, self::OPT_EQUAL) === 0) {
                $validator = new StringLengthEqual([
                    StringLengthEqual::OPT_LENGTH => $option,
                    StringLengthEqual::OPT_MESSAGE => $options[self::OPT_MESSAGE_EQUAL] ?? null,
                ]);
                unset($options[self::OPT_EQUAL], $options[self::OPT_MESSAGE_EQUAL]);
            } else {
                continue;
            }
            $this->validators[] = $validator;
        }
        parent::__construct($options);
    }
}
