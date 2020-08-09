<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查文件类型
 * 作    者：zhaoy
 * 修改日期：2019/9/1
 */


namespace Snow\Validation\Validation\Validator\File;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator\Exception;

class MimeType extends AbstractFile
{
    const TYPE = 'FileMimeType';

    const OPT_TYPES = 'types';

    protected $message = ':field的文件类型必须为:types';

    /**
     * 功能：检查文件类型
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
        $types = $this->prepareOption(self::OPT_TYPES, $field);
        if (!is_array($types)) {
            throw new Exception(self::class . '验证器' . self::OPT_TYPES . '选项必须是数组');
        }
        $value = $validation->getValue($field);
        if (function_exists('finfo_open')) {
            $tmp = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($tmp, $value['tmp_name']);
            finfo_close($tmp);
        } else {
            $mime = $value['type'];
        }
        if (in_array($mime, $types)) {
            return true;
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $replacePairs = [
            ':field' => $label,
            ':types' => implode(', ', $types),
        ];
        $validation->appendMessage(new Message(strtr($message, $replacePairs), $field, self::TYPE, $code));
        return false;
    }
}
