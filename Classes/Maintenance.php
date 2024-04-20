<?php
/*  EXAM BANK SEARCH ENGINE (EBSE) - Maintenance
 *
 *  @AUTHOR:    BINGHUAN W LI <lbinghuan[at]outlook.com>
 *  @DATE:      JULY 09, 2022
 *
 *  @github:    https://github.com/binghuan-li/Exam-Bank-Search-Engine
 *
 */

require_once(realpath(dirname(__FILE__)) . '/System.php');

class Maintenance extends System
{

    public function readMaintenanceMsg(){
        $fileLoc = './txt/Maintenance_Info.txt';
        $file = fopen($fileLoc, 'r') or die("unable to open file!");
        $info = fread($file, filesize($fileLoc));
        fclose($file);
        echo $info;
    }
    
    public function writeEmergencyStatus($flag){
        $fileLoc = './txt/Page_Availability.txt';
        $file = fopen($fileLoc, "wa+") or die("Unable to open file!");
        fwrite($file, $flag);
        fclose($myfile);
    }
    
    public function displayMaintenancePage(){
        echo "
            <!DOCTYPE html>
            <html>
                <head>
                <meta charset='ISO-8859-1'>
                <link rel='stylesheet' type='text/css' href='./css/maintenance.css'>
                <title>Welcome</title>
                </head>
            
                <body>
                    <h3>Limited Access - System Maintenance in Progress</h3>
                    <b>You have now loggin in.</b> <br><br>
                    However, the following page is temporarily unavailable.<br><br>
                    The following information is retrieved from the maintenance log:<br><br>
                    <div class='info'>";
        try{
            $this -> readMaintenanceMsg();
        }catch(Exception $e){    
        }
        echo "
                    </div>
                    <br><br>Please use the 'logout' button to logout.
                    <form action='../../../include/logout' method='post'>
                        <button type='submit' name='logout' class='button button5'>logout</button>
                    </form>
                </body>
            </html>";
    }      
    
    public function displayNoAccessPage(){
        echo "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='ISO-8859-1'>
                <link rel='stylesheet' type='text/css' href='./css/maintenance.css'>
                <title>Welcome</title>
            </head>
                <body>
                    <h3>Limited Access</h3>
                    <b>You have now loggin in.</b> <br><br>
                    However, you have no access privilege to this page.<br><br>
                    Please use the 'logout' button to logout. If you believe you should have access to this page, please try login again.<br><br>
                    <form action='../logout' method='post'>
                        <button type='submit' name='logout' class='button button5'>logout</button>
                    </form>
                </body>
            </html>";    
    }
    

}


