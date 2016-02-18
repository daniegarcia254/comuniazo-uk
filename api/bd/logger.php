<?php
class Logger
{
    //constants declaration
    const FILE_BASE = '../log/comunio-uk-log-';

    // property declaration
    public $file_log = '';
    public $file_log_error = '';

    // Constructor
    function __construct() {
        $date = getdate();
        $file_log_name = self::FILE_BASE.$date["mday"]."_".$date["mon"]."_".$date["year"].".log";
        $file_log_error_name = self::FILE_BASE.$date["mday"]."_".$date["mon"]."_".$date["year"].".log.error";
        $this->setFileLog($file_log_name);
        $this->setFileLogError($file_log_error_name);

        if (!file_exists($this->getFileLog()))
            $this->file_log = fopen($this->file_log, 'a');

        if (!file_exists($this->getFileLogError()))
            $this->file_log_error = fopen($this->file_log_error, 'a');
    }


    public function write_log($message, $logfile) {

        // Get time of request
        if( ($time = $_SERVER['REQUEST_TIME']) == '') {
            $time = time();
        }

        // Get IP address
        if( ($remote_addr = $_SERVER['REMOTE_ADDR']) == '') {
            $remote_addr = "REMOTE_ADDR_UNKNOWN";
        }

        // Get requested script
        if( ($request_uri = $_SERVER['REQUEST_URI']) == '') {
            $request_uri = "REQUEST_URI_UNKNOWN";
        }

        // Format the date and time
        $date = date("Y-m-d H:i:s", $time);

        // Append to the log file
        if($fd = @fopen($logfile, "a")) {
            $result = fputcsv($fd, array($date, $remote_addr, $request_uri, $message));
            fclose($fd);

            if($result > 0)
                return array("status" => true);
            else
                return array("status" => false, "message" => 'Unable to write to '.$logfile.'!');
        }
        else {
            return array("status" => false, "message" => 'Unable to open log '.$logfile.'!');
        }
    }

    /**
     * @return string
     */
    public function getFileLogError()
    {
        return $this->file_log_error;
    }

    /**
     * @return string
     */
    public function getFileLog()
    {
        return $this->file_log;
    }

    /**
     * @param string $file_log
     */
    public function setFileLog($file_log)
    {
        $this->file_log = $file_log;
    }

    /**
     * @param string $file_log_error
     */
    public function setFileLogError($file_log_error)
    {
        $this->file_log_error = $file_log_error;
    }
}