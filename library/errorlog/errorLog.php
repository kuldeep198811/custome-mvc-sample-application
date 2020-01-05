
<?php
/**********************
Error Log class:-
@description:: for capturing all PHP error
@devloper :: Mohit Singh Rawat
@use : Create object of class
**************************/
class ErrorLog {

    //constructor
    /*
        will call all handlers once object is created 
    */

    private $_logFilePath;    
    public function __construct(){

        $this->_logFilePath = $_SERVER['DOCUMENT_ROOT']."/bamko_jobs/Log/".date('M_Y');
        
        //Setting for the PHP Error Handler
        set_error_handler(array($this ,'__errorHandler'));

        //Setting for the PHP Exceptions Error Handler
        set_exception_handler(array($this,'__handle_exception'));

        //setting for the PHP Fatal Error Handlers 
        register_shutdown_function(array($this ,'__fatalErrorShutdownHandler'));
    }

    /*
        @description:: Handles Exceptions 
        @developer :: Mohit Singh Rawat
    */
    public function __handle_exception($_exception){
        
        //set the error log file folder path 
        $_logFileDir = $this->_logFilePath;

        //create a monthly folder
        if(!file_exists($_logFileDir)){
            // create directory.
            mkdir($_logFileDir, 0777, true);
        }

        if ($_logFileDir.date('d-M-Y') . '.txt') {
            
            $_logFile = $_logFileDir.'/'.date('d-M-Y') . '.txt';
            // create text file.
            fopen($_logFile,"a");
                
            $_timeOfError = date("Y/m/d H:i:s");

            file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"]." ".$_exception->getFile()." (".$_exception->getLine()."): Exception >> message = ".$_exception->getMessage()."".PHP_EOL, FILE_APPEND);
        }    
    }
    
    /*
        @description:: Handles Fatal Errors 
        @developer :: Mohit Singh Rawat
    */    
    public  function __fatalErrorShutdownHandler(){
        
        $_lastError = error_get_last();
        if ($_lastError['type'] === E_ERROR) {
            // fatal error
            $this->__errorHandler(E_ERROR, $_lastError['message'], $_lastError['file'], $_lastError['line']);
        }
    }

    /*
        @description:: Handles Common PHP Errors 
        @developer :: Mohit Singh Rawat
    */ 
    public  function __errorHandler($_errNo, $_errStr, $_errFile, $_errLine){
    
        //set the error log file folder path 
        $_logFileDir = $this->_logFilePath;

        //create a monthly folder
        if(!file_exists($_logFileDir)){
            // create directory.
            mkdir($_logFileDir, 0777, true);
        }

        //create daily error text file. If file exists already then just append errros 
        if ($_logFileDir.date('d-M-Y') . '.txt') {
            
            $_logFile = $_logFileDir.'/'.date('d-M-Y') . '.txt';
            // create text file.
            fopen($_logFile,"a");

            $_timeOfError = date("Y/m/d H:i:s");
            $_fileName = basename($_errFile);

            switch($errno) { 
                case E_ERROR: // 1 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "ERROR >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break;
                case E_WARNING: // 2 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "WARNING >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break; 
                case E_PARSE: // 4 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "PARSE >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break; 
                case E_NOTICE: // 8 // 
                   file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "NOTICE >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break;

                case E_CORE_ERROR: // 16 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "CORE_ERROR >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break; 
                case E_CORE_WARNING: // 32 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "CORE_WARNING >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break;
                case E_COMPILE_ERROR: // 64 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "COMPILE_ERROR >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break; 
                case E_COMPILE_WARNING: // 128 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "COMPILE_WARNING >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break;
                case E_USER_ERROR: // 256 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "USER_ERROR >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break;
                case E_USER_WARNING: // 512 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "USER_WARNING >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break;
                case E_USER_NOTICE: // 1024 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "USER_NOTICE >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break;
                case E_STRICT: // 2048 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "STRICT >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break;
                case E_RECOVERABLE_ERROR: // 4096 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "RECOVERABLE_ERROR >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break; 
                case E_DEPRECATED: // 8192 // 
                   file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "DEPRECATED >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break; 
                case E_USER_DEPRECATED: // 16384 // 
                    file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "USER_DEPRECATED >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break;

                default:
                file_put_contents($_logFile, $_timeOfError.gettimeofday()["usec"] . " $_fileName ($_errLine): " . "UNKNOWN >> message = $_errStr".PHP_EOL, FILE_APPEND);
                    break;
                break;    
            }

            // delete any files older than 30 days
            $_files = glob($_logFileDir . "*");
            $_now   = time();
            $_logFileDeleteDays = 30;
            foreach ($_files as $_file){
                if (is_file($_file)){
                    if ($_now - filemtime($_file) >= 60 * 60 * 24 * $_logFileDeleteDays){
                        unlink($_file);
                    }    
                }    
            }        
            return true;    // Don't execute PHP internal error handler

        } 
    }
}
?>