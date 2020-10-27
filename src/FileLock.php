<?php

/**
 * Copyright (c) 2020
 * ժ    Ҫ���ļ���
 * ��    �ߣ�peteryan
 * �޸����ڣ�2020.10.27
 */

namespace Yantp\Widget;

/**
 * Class FileLock
 * -
 */
class FileLock
{
    /**
     * @var null $fileHandle �ļ����
     * @access private
     */
    private $fileHandle = null;

    /**
     * @var string $lockFilePath ���ļ�
     * @access private
     */
    private $lockFilePath = '';

    /**
     * @var string $suffix �ļ���׺
     * @access private
     */
    private $suffix = '';

    /**
     * FileLock constructor.
     * @param string $lockFilePath ���ļ�
     * @param string $suffix �ļ���׺
     */
    public function __construct($lockFilePath, $suffix = '.lock')
    {
        $this->lockFilePath = $lockFilePath;
        $this->suffix = $suffix;
    }

    /**
     * ��   �ܣ�����
     * �޸����ڣ�2020-10-27
     * @return bool �����ɹ�����true,���򷵻�false
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
     * ��   �ܣ�����
     * �޸����ڣ�2020-10-27
     * @return bool �����ɹ�����true,���򷵻�false
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
     * ��   �ܣ������ļ�
     * �޸����ڣ�2020-10-27
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
     * ����
     */
    public function __destruct()
    {
        if (!empty($this->fileHandle) && is_resource($this->fileHandle)) {
            fclose($this->fileHandle);
        }
    }
}
