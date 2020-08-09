<?php

/**
 * Copyright (c) 2019,2345
 * 摘    要：检查值是否具有正确的文件
 * 作    者：zhaoy
 * 修改日期：2019/9/1
 */


namespace Snow\Validation\Validation\Validator\File;

use Snow\Validation\Validation;
use Snow\Validation\Validation\Validator;

abstract class AbstractFile extends Validator
{
    const TYPE = 'FILE';

    /**
     * @var string 空文件消息提示
     */
    protected $messageFileEmpty = ':field不能为空';

    /**
     * @var string 文件大小超过php.ini配置大小时提示消息
     */
    protected $messageIniSize = ':field超过最大文件大小';

    /**
     * @var string 文件无效提示消息
     */
    protected $messageValid = ':field无效';

    /**
     * 功能：获取$messageFileEmpty
     * 修改日期：2019/9/1
     *
     * @return string
     */
    public function getMessageFileEmpty()
    {
        return $this->messageFileEmpty;
    }

    /**
     * 功能：设置$messageFileEmpty
     * 修改日期：2019/9/1
     *
     * @param string $messageFileEmpty 消息
     * @return $this
     */
    public function setMessageFileEmpty(string $messageFileEmpty)
    {
        $this->messageFileEmpty = $messageFileEmpty;
        return $this;
    }

    /**
     * 功能：获取$messageIniSize
     * 修改日期：2019/9/1
     *
     * @return string
     */
    public function getMessageIniSize()
    {
        return $this->messageIniSize;
    }

    /**
     * 功能：设置$messageIniSize
     * 修改日期：2019/9/1
     *
     * @param string $messageIniSize 消息
     * @return $this
     */
    public function setMessageIniSize(string $messageIniSize)
    {
        $this->messageIniSize = $messageIniSize;
        return $this;
    }

    /**
     * 功能：获取$messageIniSize
     * 修改日期：2019/9/1
     *
     * @return string
     */
    public function getMessageValid()
    {
        return $this->messageValid;
    }

    /**
     * 功能：设置$messageValid
     * 修改日期：2019/9/1
     *
     * @param string $messageValid 消息
     * @return $this
     */
    public function setMessageValid(string $messageValid)
    {
        $this->messageValid = $messageValid;
        return $this;
    }

    /**
     * 功能：判断是否是空值
     * 修改日期：2019/9/1
     *
     * @param Validation $validation 验证类
     * @param string $field 字段
     * @return bool
     */
    public function isAllowEmpty(Validation $validation, $field)
    {
        $value = $validation->getValue($field);
        return empty($value) || isset($value['error']) && $value['error'] === UPLOAD_ERR_NO_FILE;
    }

    /**
     * 功能：检查上传
     * 修改日期：2019/9/1
     *
     * @param Validation $validation 验证类
     * @param string $field 字段
     * @return bool
     */
    protected function checkUpload(Validation $validation, $field)
    {
        return $this->checkUploadMaxSize($validation, $field) &&
                $this->checkUploadIsEmpty($validation, $field) &&
                $this->checkUploadIsValid($validation, $field);
    }

    /**
     * 功能：检查上传的文件是否大于PHP允许的大小
     * 修改日期：2019/9/1
     *
     * @param Validation $validation 验证类
     * @param string $field 字段
     * @return bool
     */
    protected function checkUploadMaxSize(Validation $validation, $field)
    {
        $value = $validation->getValue($field);
        if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
            empty($_POST) &&
            empty($_FILES) &&
            $_SERVER['CONTENT_LENGTH'] > 0 ||
            isset($value['error']) && $value['error'] === UPLOAD_ERR_INI_SIZE
        ) {
            $label = $this->prepareLabel($validation, $field);
            $message = $this->getMessageIniSize();
            $code = $this->prepareCode($field);
            $message = new Validation\Message(strtr($message, [':field' => $label]), $field, self::TYPE, $code);
            $validation->appendMessage($message);
            return false;
        }
        return true;
    }

    /**
     * 功能：检查上传的文件是否为空
     * 修改日期：2019/9/1
     *
     * @param Validation $validation 验证类
     * @param string $field 字段
     * @return bool
     */
    protected function checkUploadIsEmpty(Validation $validation, $field)
    {
        $value = $validation->getValue($field);
        if (!isset($value['error']) ||
            !isset($value['tmp_name']) ||
            $value['error'] !== UPLOAD_ERR_OK ||
            !is_uploaded_file($value['tmp_name'])
        ) {
            $label = $this->prepareLabel($validation, $field);
            $message = $this->getMessageFileEmpty();
            $code = $this->prepareCode($field);
            $message = new Validation\Message(strtr($message, [':field' => $label]), $field, self::TYPE, $code);
            $validation->appendMessage($message);
            return false;
        }
        return true;
    }

    /**
     * 功能：检查上传的文件是否有效
     * 修改日期：2019/9/1
     *
     * @param Validation $validation 验证类
     * @param string $field 字段
     * @return bool
     */
    protected function checkUploadIsValid(Validation $validation, $field)
    {
        $value = $validation->getValue($field);
        if (!isset($value['name']) || !isset($value['type']) || !isset($value['size'])) {
            $label = $this->prepareLabel($validation, $field);
            $message = $this->getMessageValid();
            $code = $this->prepareCode($field);
            $message = new Validation\Message(strtr($message, [':field' => $label]), $field, self::TYPE, $code);
            $validation->appendMessage($message);
            return false;
        }
        return true;
    }

    /**
     * 功能：解析文件大小字符串
     * 修改日期：2019/9/1
     *
     * @param string $size 文件大小
     * @return float|int
     */
    public function getFileSizeInBytes(string $size)
    {
        $byteUnits = [
            'B' => 0,
            'K' => 10,
            'M' => 20,
            'G' => 30,
            'T' => 40,
            'KB' => 10,
            'MB' => 20,
            'GB' => 30,
            'TB' => 40
        ];
        $unit = 'B';
        preg_match('/^([0-9]+(?:\\.[0-9]+)?)(' . implode('|', array_keys($byteUnits)) . ')?$/Di', strtoupper($size), $matches);
        if (isset($matches[2])) {
            $unit = $matches[2];
        }
        return floatval($matches[1]) * pow(2, $byteUnits[$unit]);
    }
}
