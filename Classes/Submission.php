<?php
/*  EXAM BANK SEARCH ENGINE (EBSE) - Submission
 *  class receives and process user submissions
 *
 *  @AUTHOR:    BINGHUAN W LI <lbinghuan[at]outlook.com>
 *  @DATE:      MAY 31, 2023
 *
 *  @github:    https://github.com/binghuan-li/Exam-Bank-Search-Engine
 *
 */

require_once(realpath(dirname(__FILE__)) . '/System.php');

class Submission extends System
{   
    public function __construct($examName, $examYear, $examType){
        parent::__construct();
        $this -> examModule = filter_var($examName, FILTER_SANITIZE_STRING);
        $this -> examYear = filter_var($examYear, FILTER_SANITIZE_STRING);
        $this -> examType = filter_var($examType, FILTER_SANITIZE_STRING);
    }
    
    public function validate(){
        $fileType = $_FILES["file"]["type"];
        $fileSize = $_FILES["file"]["size"];
        
        if ($fileType!="application/pdf"){ // check file type
            echo 'Sorry, only PDF files will be accpeted.';
            return false;
        }
        
        if ($fileSize >= 50000000){ // check file size
            echo 'Sorry, the file size should not be larger than 50Mb.';
            return false;
        }
        return true;
    }
    
    public function move(){
        // rename the file
        $paperName = $this->examYear . $this->examModule . $this->examType . '.pdf';
        $uploadPath = dirname('C:/Users/lbing/Desktop/PINN/'). $paperName;
        $tempName = $_FILES['file']['tmp_name'];
        if(move_uploaded_file($tempName, $uploadPath)){
            // prepare to insert into sql
            $date = date('y-m-j');
            $time = date("G:i:s");
            $query = "INSERT INTO `" . STAGING_TABLE_NAME . "` (`Date`, `Time`, `Year`, `Module`, `Sub-Module`, `Exam-Type`)";
            $query .= "VALUES ('$date', '$time', '$this->examYear', '$this->examModule', '$this->examModule', '$this->examType')";
            if (mysqli_query($this->conn, $query)){
                return true;
            }
        } else{
            return false;
        }
    }

}
