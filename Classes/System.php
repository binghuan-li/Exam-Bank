<?php
/*  EXAM BANK SEARCH ENGINE (EBSE) - System
 *
 *  @AUTHOR:    BINGHUAN W LI <lbinghuan[at]outlook.com>
 *  @DATE:      JULY 09, 2022
 *
 *  @github:    https://github.com/binghuan-li/Exam-Bank-Search-Engine
 *
 */

require_once(realpath(dirname(__FILE__)) . '/config.php');

class System
{
    private $openHour, $closeHour;
    
    public function __construct(){
        date_default_timezone_set('Europe/London');
        $this->openHour = OPEN_HOUR;
        $this->closeHour = CLOSE_HOUR;
        $this->conn = $this->makeConnection();
    }
    
    protected function makeConnection(){
        $this -> conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if (! ($this->conn)){
            $failureMsg = mysqli_connect_error();
            $this -> emailFailure($failureMsg);
            die("Unable to connect to the given database ". mysqli_connect_error());     
        }else{
            return $this->conn;
        }
    }
    
    private function emailFailure($failureMsg){
        $to = 'lbinghuan@outlook.com';
        $email_subject = "Database Connection Failure";
        $email_body = 'Could not connect to the database \r \n';
        $email_body .= $failureMsg;
  
        // set a HIGH priority!
        $headers = "From: no-reply@binghuan.li";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
        $headers .= "X-Priority: 1 (Highest)\n";
        $headers .= "X-MSMail-Priority: High\n";
        $headers .= "Importance: High\n";
        
        if(mail($to,$email_subject,$email_body,$headers)){
            // not yet implemented - call systemLog function
        }
    }

    public function availabilityChecker(){
        $fileLoc = "../txt/Page_Availability.txt";    // location
        
        $file = fopen($fileLoc, 'r') or die("unable to open file!");
        $pageAvailability  = fread($file, filesize($fileLoc));
        fclose($file);
        
        if ($pageAvailability==1){ // no emergency
            $currTime = date("G");
            if($currTime < $this->openHour || $currTime >= $this->closeHour){ // shut down the service outside the defined time
                return $this->access = 0;
            }else{ // ok, push a record to db
                return $this->access = 1;
//                 visitorRecord($this->conn, "exam");
            }
        }else if ($pageAvailability==0){ // under emergency, close the site
            return $this->access = -1;
        }else { // wrong code (!= 1/0), need to reset availability
            return $this->access = -2;
        }
    }
    
    
    public function systemLog($user, $site, $logmsg, $ip){ 
        $date = date('y-m-j');
        $time = date("G:i:s");
        
        $sql = "INSERT INTO `system_log` (`DATE`, `TIME`, `USER`, `SITE`, `LOG_MESSAGE`, `IP`)";
        $sql .= "VALUES ('$date', '$time', '$user', '$site', '$logmsg', '$ip')";
        
        if (mysqli_query($this->conn, $sql)){
        }
        
    }
    

    
}

