<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查值是否具有正确的文件
 * 作    者：zhaoy
 * 修改日期：2019/9/1
 */


namespace Snow\Validation\Validation\Validator;

use Snow\Validation\Validation\Validator\File\MimeType;
use Snow\Validation\Validation\Validator\File\Resolution\Equal as FileResolutionEqual;
use Snow\Validation\Validation\Validator\File\Resolution\Max as FileResolutionMax;
use Snow\Validation\Validation\Validator\File\Resolution\Min as FileResolutionMin;
use Snow\Validation\Validation\Validator\File\Size\Equal as FileSizeEqual;
use Snow\Validation\Validation\Validator\File\Size\Max as FileSizeMax;
use Snow\Validation\Validation\Validator\File\Size\Min as FileSizeMin;
use Snow\Validation\Validation\ValidatorComposite;

class File extends ValidatorComposite
{
    const OPT_ALLOW_TYPES = 'allowedTypes';

    const OPT_MESSAGE_TYPE = 'messageType';

    const OPT_MIN_SIZE = 'minSize';

    const OPT_MESSAGE_MIN_SIZE = 'messageMinSize';

    const OPT_INCLUDE_MIN_SIZE = 'includedMinSize';

    const OPT_MAX_SIZE = 'maxSize';

    const OPT_MESSAGE_MAX_SIZE = 'messageMaxSize';

    const OPT_INCLUDE_MAX_SIZE = 'includedMaxSize';

    const OPT_EQUAL_SIZE = 'equalSize';

    const OPT_MESSAGE_EQUAL_SIZE = 'messageEqualSize';

    const OPT_MIN_RESOLUTION = 'minResolution';

    const OPT_MESSAGE_MIN_RESOLUTION = 'messageMinResolution';

    const OPT_INCLUDE_MIN_RESOLUTION = 'includedMinResolution';

    const OPT_MAX_RESOLUTION = 'maxResolution';

    const OPT_MESSAGE_MAX_RESOLUTION = 'messageMaxResolution';

    const OPT_INCLUDE_MAX_RESOLUTION = 'includedMaxResolution';

    const OPT_EQUAL_RESOLUTION = 'equalResolution';

    const OPT_MESSAGE_EQUAL_RESOLUTION = 'messageEqualResolution';

    /**
     * File constructor.
     * @param array $options 选项
     * @throws \Snow\Validation\Validation\Validator\Exception
     */
    public function __construct(array $options = [])
    {
        if (empty($options)) {
            throw new Exception(self::class . '验证器选项不能为空');
        }
        foreach ($options as $key => $option) {
            if (strcasecmp($key, self::OPT_ALLOW_TYPES) === 0) {
                if (!is_array($option)) {
                    throw new Exception(self::class . '验证器' . self::OPT_ALLOW_TYPES . '选项必须为数组');
                }
                $message = $options[self::OPT_MESSAGE_TYPE] ?? null;
                $validator = new MimeType([
                    MimeType::OPT_TYPES => $option,
                    MimeType::OPT_MESSAGE => $message,
                ]);
                unset($options[self::OPT_ALLOW_TYPES], $options[self::OPT_MESSAGE_TYPE]);
            } elseif (strcasecmp($key, self::OPT_MIN_SIZE) === 0) {
                $validator = new FileSizeMin([
                    FileSizeMin::OPT_SIZE => $option,
                    FileSizeMin::OPT_MESSAGE => $options[self::OPT_MESSAGE_MIN_SIZE] ?? null,
                    FileSizeMin::OPT_INCLUDED => $options[self::OPT_INCLUDE_MIN_SIZE] ?? null,
                ]);
                unset($options[self::OPT_MIN_SIZE], $options[self::OPT_MESSAGE_MIN_SIZE]);
                unset($options[self::OPT_INCLUDE_MIN_SIZE]);
            } elseif (strcasecmp($key, self::OPT_MAX_SIZE) === 0) {
                $validator = new FileSizeMax([
                    FileSizeMax::OPT_SIZE => $option,
                    FileSizeMax::OPT_MESSAGE => $options[self::OPT_MESSAGE_MAX_SIZE] ?? null,
                    FileSizeMax::OPT_INCLUDED => $options[self::OPT_INCLUDE_MAX_SIZE] ?? null,
                ]);
                unset($options[self::OPT_MAX_SIZE], $options[self::OPT_MESSAGE_MAX_SIZE]);
                unset($options[self::OPT_INCLUDE_MAX_SIZE]);
            } elseif (strcasecmp($key, self::OPT_EQUAL_SIZE) === 0) {
                $validator = new FileSizeEqual([
                    FileSizeEqual::OPT_SIZE => $option,
                    FileSizeEqual::OPT_MESSAGE => $options[self::OPT_MESSAGE_EQUAL_SIZE] ?? null,
                ]);
                unset($options[self::OPT_EQUAL_SIZE], $options[self::OPT_MESSAGE_EQUAL_SIZE]);
            } elseif (strcasecmp($key, self::OPT_MIN_RESOLUTION) === 0) {
                $validator = new FileResolutionMin([
                    FileResolutionMin::OPT_RESOLUTION => $option,
                    FileResolutionMin::OPT_MESSAGE => $options[self::OPT_MESSAGE_MIN_RESOLUTION] ?? null,
                    FileResolutionMin::OPT_INCLUDED => $options[self::OPT_INCLUDE_MIN_RESOLUTION] ?? null,
                ]);
                unset($options[self::OPT_MIN_RESOLUTION], $options[self::OPT_MESSAGE_MIN_RESOLUTION]);
                unset($options[self::OPT_INCLUDE_MIN_RESOLUTION]);
            } elseif (strcasecmp($key, self::OPT_MAX_RESOLUTION) === 0) {
                $validator = new FileResolutionMax([
                    FileResolutionMax::OPT_RESOLUTION => $option,
                    FileResolutionMax::OPT_MESSAGE => $options[self::OPT_MESSAGE_MAX_RESOLUTION] ?? null,
                    FileResolutionMax::OPT_INCLUDED => $options[self::OPT_INCLUDE_MAX_RESOLUTION] ?? null,
                ]);
                unset($options[self::OPT_MAX_RESOLUTION], $options[self::OPT_MESSAGE_MAX_RESOLUTION]);
                unset($options[self::OPT_INCLUDE_MAX_RESOLUTION]);
            } elseif (strcasecmp($key, self::OPT_EQUAL_RESOLUTION) === 0) {
                $validator = new FileResolutionEqual([
                    FileResolutionEqual::OPT_RESOLUTION => $option,
                    FileResolutionEqual::OPT_MESSAGE => $options[self::OPT_MESSAGE_EQUAL_RESOLUTION] ?? null,
                ]);
                unset($options[self::OPT_EQUAL_RESOLUTION], $options[self::OPT_MESSAGE_EQUAL_RESOLUTION]);
            } else {
                continue;
            }
            $this->validators[] = $validator;
        }
        parent::__construct($options);
    }
}
