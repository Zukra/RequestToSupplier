<?php

namespace Zkr\Exceptions;

use Exception;
use Bitrix\Main\Application;

class FileSaveException extends Exception
{

    protected $logfile;

    public function setLogFile()
    {
        $this->logfile = Application::getDocumentRoot() . '/bitrix/modules/exceptions.txt';
    }

    public function saveMessage()
    {
        $this->setLogFile();

        $log = fopen($this->logfile, 'a+');
        fwrite($log, PHP_EOL . '[' . date("Y-m-d H:i:s") . '] Catch exception: ' . PHP_EOL . $this->getMessage() . PHP_EOL . $this->getTraceAsString());
        fclose($log);
    }

    public function savePostParams()
    {
        $this->setLogFile();

        $log = fopen($this->logfile, 'a+');
        fwrite($log, PHP_EOL . '[' . date("Y-m-d H:i:s") . ']' . PHP_EOL . ' $_POST =  ' . PHP_EOL . var_export($_POST, true));
        fclose($log);
    }

    public function saveParams()
    {
        $this->setLogFile();

        $log = fopen($this->logfile, 'a+');
        fwrite($log, PHP_EOL . '[' . date("Y-m-d H:i:s") . ']' . PHP_EOL . ' $_REQUEST = ' . PHP_EOL . var_export($_REQUEST, true));
        fclose($log);
    }

}
