<?php
/*  EXAM BANK SEARCH ENGINE (EBSE) - Administration
 *
 *  @AUTHOR:    BINGHUAN W LI <lbinghuan[at]outlook.com>
 *  @DATE:      JULY 09, 2022   - create
 *              MAY 31, 2023    - add methods relate to submission
 *              June 1, 2023    - add methods relate to submission
 *
 *  @github:    https://github.com/binghuan-li/Exam-Bank-Search-Engine
 *
 */

require_once(realpath(dirname(__FILE__)) . '/System.php');

class Administration extends System
{
    public function __construct(){
        parent::__construct();
        $this -> extension = '.pdf'; 
    }
    
    public function downloadStatistics($number = 10){
        $sql= 'SELECT `paper` AS `paper`, COUNT(*) AS `count` FROM `downloadRecord` GROUP BY `paper` ORDER BY COUNT(*) DESC LIMIT '. $number;
        
        if ($downloadResult = mysqli_query($this->conn, $sql)){ // sql search good
            if (mysqli_num_rows($downloadResult) > 0){
                $datapts = array();
                echo "<table>";
                echo "<tr>";
                echo "<td>Paper Title</td>";
                echo "<td>Download Times</td>";
                echo "</tr>";
                while($row = mysqli_fetch_array($downloadResult)){
                    echo "<tr>";
                    echo "<td>" . $row['paper'] . "</td>";   // year: eg 2019-2020
                    echo "<td>" . $row['count'] . "</td>"; // module info: eg bioeng sci 1
                    echo "</tr>";  
                    array_push($datapts, array("label"=> strval($row['paper']), "y"=> $row['count']));     
                }echo "</table>";
            }
        }
        echo "
            <script>
            window.onload = function () {
             
            var chart = new CanvasJS.Chart('chartContainer', {
            	animationEnabled: true,
            	theme: 'light2',
            	title: {
            		//text: 'CMS Market Share - 2017'
            	},
            	axisY: {
            		 scaleBreaks: {
            			autoCalculate: true
            		}
            	},
            	data: [{
            		type: 'column',
            		//yValueFormatString: '#,##0\'%\'',
            		indexLabel: '{y}',
            		indexLabelPlacement: 'inside',
            		indexLabelFontColor: 'white',
            		dataPoints:".json_encode($datapts, JSON_NUMERIC_CHECK)."
            	}]
            });
            chart.render();
            }
            </script>
            <div id='chartContainer' style='height: 370px; width: 80%;'></div>
            <script src='https://canvasjs.com/assets/script/canvasjs.min.js'></script>";
    }

    public function writeMaintenanceMsg($msg){
        $fileLoc = './txt/Maintenance_Info.txt';
        $message = date('Y-m-d h:i:s').' - '.$msg;
        $file = fopen($fileLoc, "wa+") or die("Unable to open file!");
        fwrite($file, $message);
        fclose($file);
    }
    
    public function updateServiceStatus($flag){
        if($flag==1 || $flag==0){
            $fileLoc = './txt/Page_Availability.txt';
            $file = fopen($fileLoc, "wa+") or die("Unable to open file!");
            fwrite($file, $flag);
            fclose($file);
        }else{
            echo "Bad input received, cannot update service status.";
        }
    }
    
    
    public function dispStagingSummary(){
        $query = "SELECT * FROM ".STAGING_TABLE_NAME;
        if ($result = mysqli_query($this->conn, $query)){
            if (mysqli_num_rows($result) > 0){
                echo "<table>";
                echo "<tr>";
                echo "<th>Date</th><th>Time</th><th>Year</th><th>Module</th><th>Sub-module</th><th>Exam-type</th><th>Option 1</th><th>Option 2</th><th>Option 3</th>";
                echo "</tr>";
                while($row = mysqli_fetch_array($result)){
                    echo "<tr>";
                    echo "<td>" . $row['Date'] . "</td>";
                    echo "<td>" . $row['Time'] . "</td>";
                    echo "<td>" . $row['Year'] . "</td>";       // year: eg 2019-2020
                    echo "<td>" . $row['Module'] . "</td>";     // module info: eg bioeng sci 1
                    echo "<td>" . $row['Sub-Module'] . "</td>"; // sub module info: eg tdk
                    echo "<td>" . $row['Exam-Type'] . "</td>";  // exam type: main? mastery? cw?
                    echo "<td> <form method = 'GET'><button type='submit' name='view' class='button button5' value='".$row['Year']."_".$row['Sub-Module']."_".$row['Exam-Type']."_".$row['Note']."'>View</button><form></td>";
                    echo "<td> <form method = 'POST'><button type='submit' name='confirm' class='button button5' value='".$row['Year']."_".$row['Sub-Module']."_".$row['Exam-Type']."_".$row['Note']."'>Confirm</button><form></td>";
                    echo "<td> <form method = 'POST'><button type='submit' name='delete' class='button button5' value='".$row['Year']."_".$row['Sub-Module']."_".$row['Exam-Type']."_".$row['Note']."'>Delete</button><form></td>";
                }
                echo "</tr>";
                echo "</table>";
            } else{
                echo "No result to show.";
            }
        } else{
            echo "Failed to execute the query.";
        }
    }
    
    public function viewFile($file){
        /* select and open the staged pdf file in the staging folder */
        $fullpath = dirname(TARGET_DIR).$file.$this->extension;
        header("Pragma: public");
        header("Content-disposition: inline;  filename=".$file.$this->extension); // rename the file
        header("Content-type: application/pdf");
        header('Content-Transfer-Encoding: binary');
        ob_clean();
        flush();
        readfile($fullpath);  
    }
    
    public function confirmStaging($year, $module, $type){
        /* move the staged file into the main folder, add a record to the main exam record table */
        $file = $year . $type . $module . $this -> extension;
        $curr_dest = dirname(TARGET_DIR) . "/" . $file;
        $move_dest = dirname(FILE_DIR) . "/" . $file;
        if(rename($curr_dest, $move_dest)){
            $query = "INSERT INTO " . PAPER_TABLE_NAME . " (`Year`, `Module`, `Sub-Module`, `Exam-Type`)";
            $query .= " SELECT `Year`, `Module`, `Sub-Module`, `Exam-Type` FROM ". STAGING_TABLE_NAME;
            $query .= " WHERE (`Year`='$year' AND `Sub-Module`='$module' AND `Exam-Type`='$type')";
            if(mysqli_query($this->conn, $query)){
                echo "Stage Confirmed.";
            }
        }
    }
    
    public function dumpFile($year, $module, $type){
        $curr_dest = dirname(TARGET_DIR) . "/" . $year . $type . $module . $this -> extension;
        // to develop
    }
}