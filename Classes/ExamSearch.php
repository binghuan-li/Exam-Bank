<?php
/*  EXAM BANK SEARCH ENGINE (EBSE) - ExamSearch
 *
 *  @AUTHOR:    BINGHUAN W LI <lbinghuan[at]outlook.com>
 *  @DATE:      JULY 09, 2022
 *
 *  @github:    https://github.com/binghuan-li/Exam-Bank-Search-Engine
 *
 */

require_once(realpath(dirname(__FILE__)) . '/config.php');
require_once(realpath(dirname(__FILE__)) . '/System.php');

class ExamSearch extends System
{
    protected $table_name;
    
    public function __construct(){
        parent::__construct();
        $this->conn = $this->makeConnection();
        $this->defineTable();
    }
    
    
    protected function defineTable(){
        if(strtotime(DUE_DATE)<time()){
            $this->table_name = PAPER_TABLE_NAME;
        } else {
            $this->table_name = PAPER_BACKUP_TABLE_NAME;
        }
    }
     
    public function getYear(){
        $sql = "SELECT distinct Year FROM ".$this->table_name;
        if ($yearResult = mysqli_query($this->conn, $sql)){ // sql search good
            if (mysqli_num_rows($yearResult) > 0){
                echo "<select name='year' id='year'>";
                echo "<option value='' disabled selected>Year option</option>";
                while($availableYear = mysqli_fetch_array($yearResult)){
                    echo "<option value='". $availableYear['Year']."'>". $availableYear['Year']. "</option>";
                }echo "</select><br /><br />";
            }else{
                echo "No records available";
            }
        }
        mysqli_free_result($yearResult);
    }
    
    public function getModule($module_selected=null){
        $sql = "SELECT distinct `Sub-Module` FROM ".$this->table_name;
        if ($moduleResult = mysqli_query($this->conn, $sql)){
            if (mysqli_num_rows($moduleResult) > 0){
                echo "<select name='module' id='module' required>";
                if  ($module_selected==null){
                    echo "<option value='' disabled selected>Module option</option>";
                }else{
                    echo "<option value='".$module_selected ."' selected>". $module_selected . "</option>";
                }
                while($availableModule = mysqli_fetch_array($moduleResult)){
                    echo "<option value='".$availableModule['Sub-Module']."'>". $availableModule['Sub-Module']. "</option>";
                }echo "</select><br /><br />";
            }
        }else{
                echo "No records available";
            }
        mysqli_free_result($moduleResult);
    }
    
    public function getType($type_selected=null){  
        $sql = "SELECT distinct `Exam-Type` FROM ".$this->table_name;
        if ($typeResult = mysqli_query($this->conn, $sql)){
            if (mysqli_num_rows($typeResult) > 0){
                echo "<select name='type' id='type' required>";
                if ($type_selected==null){
                    echo "<option value='' disabled selected>Type option</option>";
                }else{
                    echo "<option value='".$type_selected ."' selected hidden>". $type_selected. "</option>";
                }
                while($availableType = mysqli_fetch_array($typeResult)){
                    echo "<option value='". $availableType['Exam-Type']."'>". $availableType['Exam-Type']. "</option>";
                }echo "</select><br /><br />";
            }
        }else{
            echo "No records available";
        }
        mysqli_free_result($typeResult);
    }
    
    public function downloadFile($file){
        
        // exam paper dir
        $dir = dirname(FILE_DIR); 

        if(strpos($file, "Programming")!== false){// prog 1 exams are .zip file, this is an examption
            $extension = '.zip';
            $fullpath = $dir.$file.$extension;
            header("Pragma: public");
            header("Content-disposition: attachment;  filename=".$file.$extension); // rename the file
            header('Content-Transfer-Encoding: binary');
            ob_clean();
            flush();
            readfile($fullpath);
            $this -> downloadLog($file.$extension, $_SESSION["username"]); 
        }else{
            $extension = '.pdf';
            $fullpath = $dir.$file.$extension; // full path
            header("Pragma: public");
            header("Content-disposition: inline;  filename=".$file.$extension); // rename the file
            header("Content-type: application/pdf");
            header('Content-Transfer-Encoding: binary');
            ob_clean();
            flush();
            readfile($fullpath);
            $this -> downloadLog($file.$extension, $_SESSION["username"]);
        }
    }
 
    private function downloadLog($paperName,$user){
        $date = date('y-m-j');
        $time = date("G:i:s");
        
        $sql = "INSERT INTO `downloadRecord`(`date`,`time`, `paper`, `user_action`)";
        $sql .= "VALUES ('$date', '$time', '$paperName', '$user')";
        
        if (mysqli_query($this->conn, $sql)){// do sth
        }
    }
    
    
    public function search($sqlSearch){
        if ($result = mysqli_query($this->conn, $sqlSearch)){ // sql search good
            if (mysqli_num_rows($result) > 0){ // result is not 0
                echo "<table>";
                echo "<tr>";
                echo "<th>Year</th><th>Module</th><th>Sub-module</th><th>Exam-type</th><th>Notes</th><th>Download link</th>";
                echo "</tr>";
                while($row = mysqli_fetch_array($result)){
                    echo "<tr>";
                    echo "<td>" . $row['Year'] . "</td>";       // year: eg 2019-2020
                    echo "<td>" . $row['Module'] . "</td>";     // module info: eg bioeng sci 1
                    echo "<td>" . $row['Sub-Module'] . "</td>"; // sub module info: eg tdk
                    echo "<td>" . $row['Exam-Type'] . "</td>";  // exam type: main? mastery? cw?
                    // begin interpret paper's code
                    switch($row['Note']){
                        // main paper code
                        case '0':
                            echo "<td> Main Exam Paper </td>"; // eg main
                            break;
                        case 'ans':
                            echo "<td> Answer </td>"; // main ans
                            break;
                            // mastery paper code
                        case '1':
                            echo "<td> #1 </td>"; // attempt 1
                            break;
                        case '1ans':
                            echo "<td> #1 Answer </td>"; // attempt 1 ans
                            break;
                        case '2':
                            echo "<td> #2 </td>"; // attempt 2
                            break;
                        case '2ans':
                            echo "<td> #2 Answer </td>"; // attempt 2 ans
                            break;
                        case '3':
                            echo "<td> #3 </td>"; // attempt 3
                            break;
                        case '3ans':
                            echo "<td> #3 Answer </td>"; // attempt 3 ans
                            break;
                            // sqt paper code
                        case '4':
                            echo "<td> Resit #1 </td>"; // summer sqt
                            break;
                        case '4ans':
                            echo "<td> Resit #1 Answer </td>"; // summer sqt ans
                            break;
                        case '5':
                            echo "<td> Resit #2 </td>"; // summer sqt attempt 2
                            break;
                        case '5ans':
                            echo "<td> Resit #2 Answer </td>"; // summer sqt attempt 2 ans
                            break;
                            // special paper code
                        case 'ab2':
                            echo "<td> Alpha/Beta #2 </td>"; // Math 2 alpha beta #2
                            break;
                        case 'ab2ans':
                            echo "<td> Alpha/Beta #2 Answer </td>"; // Math 1 alpha beta #2 ans
                            break;
                        case 'ab3':
                            echo "<td> Alpha/Beta #3 </td>"; // Math 3 alpha beta #3
                            break;
                        case 'ab3ans':
                            echo "<td> Alpha/Beta #3 Answer </td>"; // Math 1 alpha beta #3 ans
                            break;
                        case 'gd1':
                            echo "<td> Gamma/Delta #1 </td>"; // Math 1 Gamma/Delta #1
                            break;
                        case 'gd1ans':
                            echo "<td> Gamma/Delta #1 Answer </td>"; // Math 1 Gamma/Delta #1 ans
                            break;
                        case 'gd2':
                            echo "<td> Gamma/Delta #2 </td>"; // Math 1 Gamma/Delta #2
                            break;
                        case 'gd2ans':
                            echo "<td> Gamma/Delta #2 Answer </td>"; // Math 1 Gamma/Delta #2 ans
                            break;
                        default:
                            echo "<td>". $row['Note'] ."</td>";
                    }
                    // concatenate paper's name for submission
                    echo "<td> <form method = 'GET'><button type='submit' name='download' class='button button5' value='".$row['Year']."_".$row['Sub-Module']."_".$row['Exam-Type']."_".$row['Note']."'>Download</button><form></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }else{ // nothing found
                echo 'No records found!';
            }
        }else { // sql dead
            echo 'Search failed.';
            exit;
        }   
    }
    
    
}

