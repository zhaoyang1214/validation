<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查文件分辨率
 * 作    者：zhaoy
 * 修改日期：2019/9/1
 */


namespace Snow\Validation\Validation\Validator\File\Resolution;

use Snow\Validation\Validation\Message;
use Snow\Validation\Validation\Validator\Exception;
use Snow\Validation\Validation\Validator\File\AbstractFile;

class Equal extends AbstractFile
{
    const TYPE = 'FileResolutionEqual';

    const OPT_RESOLUTION = 'resolution';

    protected $message = ':field的图像分辨率必须是:resolution';

    /**
     * 功能：检查文件分辨率
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
        $resolution = $this->prepareOption(self::OPT_RESOLUTION, $field);
        if (is_null($resolution)) {
            throw new Exception(self::class . '验证器' . self::OPT_RESOLUTION . '选项必须设置');
        }
        $resolutionArr = explode('x', strtolower($resolution));
        if (!isset($resolutionArr[1])) {
            throw new Exception(self::class . '验证器' . self::OPT_RESOLUTION . '选项格式错误（宽x高）');
        }
        $minWidth = trim($resolutionArr[0]);
        $minHeight = trim($resolutionArr[1]);
        $value = $validation->getValue($field);
        $tmp = getimagesize($value['tmp_name']);
        if ($tmp) {
            $width  = $tmp[0];
            $height = $tmp[1];
            if ($width == $minWidth && $height == $minHeight) {
                return true;
            }
        }
        $label = $this->prepareLabel($validation, $field);
        $message = $this->prepareMessage($validation, $field, self::TYPE);
        $code = $this->prepareCode($field);
        $replacePairs = [
            ':field' => $label,
            ':resolution' => $resolution,
        ];
        $validation->appendMessage(new Message(strtr($message, $replacePairs), $field, self::TYPE, $code));
        return false;
    }
}
