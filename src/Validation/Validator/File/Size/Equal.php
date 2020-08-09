<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查文件大小
 * 作    者：zhaoy
 * 修改日期：2019/9/1
 */


namespace Snow\Validation\Validation\Validator\File\Size;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator\Exception;
use Snow\Validation\Validation\Validator\File\AbstractFile;

class Equal extends AbstractFile
{
    const TYPE = 'FileSizeEqual';

    const OPT_SIZE = 'size';

    protected $message = ':field的文件大小必须等于:size';

    /**
     * 功能：检查文件大小
     * 修改日期：2019/9/1
     *
     * @param \Snow\Validation\Validation $validation 验证类
     * @param string $field 字段
     * @throws \Snow\Validation\Validation\Validator\Exception
     * @return bool
     */
    public function validate(\Snow\Validation\Validation $validation, $field)
    {
        if ($this->checkUpload($validation, $field) === false) {
            return false;
        }
        $size = $this->prepareOption(self::OPT_SIZE, $field);
        if (is_null($size)) {
            throw new Exception(self::class . '验证器' . self::OPT_SIZE . '选项必须设置');
        }
        $bytes = round($this->getFileSizeInBytes($size), 6);
        $value = $validation->getValue($field);
        $fileSize = round(floatval($value['size']), 6);
        if (function_exists('bccomp')) {
            $result = bccomp($fileSize, $bytes, 6) === 0;
        } else {
            $result = $fileSize === $bytes;
        }
        if ($result) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $replacePairs = [
            ':field' => $label,
            ':size' => $size,
        ];
        $validation->appendMessage(new Message(strtr($message, $replacePairs), $field, self::TYPE, $code));
        return false;
    }
}
