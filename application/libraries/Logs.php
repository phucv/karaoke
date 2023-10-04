<?php

class Logs {

    protected $_ci;
    protected $_log_path;

    public function __construct() {
        $this->_ci = &get_instance();
        $this->_log_path = APPPATH . "logs/";
    }

    public function write_log ($content) {
        $folderLog = $this->_log_path;
        if (!is_dir($folderLog)) {
            $old = @umask(0);
            @mkdir($folderLog, 0755, true);
            @umask($old);
        }
        $myFile = $folderLog . "log-" . date('Y-m-d') . ".txt";
        $fh = @fopen($myFile, 'a');
        @fwrite($fh, date('Y-m-d H:i:s') . ": " . $content . PHP_EOL);
        @fclose($fh);
    }

}