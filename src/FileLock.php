<?php

/**
 * Copyright (c) 2020
 * 摘    要：文件锁
 * 作    者：peteryan
 * 修改日期：2020.10.27
 */

namespace Yantp\Widget;

/**
 * Class FileLock
 * -
 */
class FileLock
{
    /**
     * @var null $fileHandle 文件句柄
     * @access private
     */
    private $fileHandle = null;

    /**
     * @var string $lockFilePath 锁文件
     * @access private
     */
    private $lockFilePath = '';

    /**
     * @var string $suffix 文件后缀
     * @access private
     */
    private $suffix = '';

    /**
     * FileLock constructor.
     * @param string $lockFilePath 锁文件
     * @param string $suffix 文件后缀
     */
    public function __construct($lockFilePath, $suffix = '.lock')
    {
        $this->lockFilePath = $lockFilePath;
        $this->suffix = $suffix;
    }

    /**
     * 功   能：加锁
     * 修改日期：2020-10-27
     * @return bool 加锁成功返回true,否则返回false
     * @access public
     */
    public function lock()
    {
        if (empty($this->fileHandle)) {
            $this->openFile();
        }
        $returnValue = flock($this->fileHandle, LOCK_EX | LOCK_NB);
        return $returnValue;
    }

    /**
     * 功   能：解锁
     * 修改日期：2020-10-27
     * @return bool 解锁成功返回true,否则返回false
     * @access public
     */
    public function unlock()
    {
        $returnValue = false;
        if (!empty($this->fileHandle) && is_resource($this->fileHandle)) {
            $returnValue = flock($this->fileHandle, LOCK_UN);
        }
        return $returnValue;
    }

    /**
     * 功   能：打开锁文件
     * 修改日期：2020-10-27
     * @return void
     * @access private
     */
    private function openFile()
    {
        if (empty($this->fileHandle)) {
            if (!empty($this->lockFilePath)) {
                $fileDir = dirname($this->lockFilePath);
                if (!is_dir($fileDir)) {
                    mkdir($fileDir, 0777, true);
                }
                $this->fileHandle = fopen($this->lockFilePath . $this->suffix, 'w+');
            }
        }
    }

    /**
     * 析构
     */
    public function __destruct()
    {
        if (!empty($this->fileHandle) && is_resource($this->fileHandle)) {
            fclose($this->fileHandle);
        }
    }
}
