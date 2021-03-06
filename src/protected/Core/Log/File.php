<?php

/**
 * @author QiangYu
 *
 * 输出日志到 File
 *
 * */

namespace Core\Log;

use Core\Helper\Utility\Time;

class File extends Base
{

    private $file = 'log.log';

    /**
     *
     * @param string $file 日志文件名
     */
    function __construct($file = null)
    {
        global $f3;
        if (empty($file)) {
            $file = Time::localTimeStr('Y-m-d') . 'log';
        }
        $this->file = $f3->get('LOGS') . $file;

        if (!is_file($this->file)) {

            // 创建目录
            $pathInfoArray = pathinfo($this->file);
            if (!is_dir($pathInfoArray['dirname'])) {
                @mkdir($pathInfoArray['dirname'], 0755, true);
            }

            // 创建文件
            $fp = fopen($this->file, 'w');
            if (!$fp) {
                throw new \InvalidArgumentException('can not create file : ' . $this->file);
            }
            fclose($fp);
        }
    }

    /**
     * 加入一条日志信息
     *
     * @param string $level   日志等级
     * @param string $source  日志的来源，比如 'SQL'
     * @param string $msg     日志消息
     * */
    public function addLogInfo($level, $source, $msg)
    {
        global $f3;
        if (!is_file($this->file)) {
            return;
        }
        $msg = '[' . Time::localTimeStr('Y-m-d H:i:s') . '][' . $level . '][' . $source . '][' . trim($msg) . ']'
            . PHP_EOL;
        $f3->write($this->file, $msg, true);
    }

}