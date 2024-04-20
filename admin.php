<?php
/*  EXAM BANK SEARCH ENGINE (EBSE) - admin
 * 
 *  this page provides adminstration tools for the administrators to control EBSE services
 *
 *  @AUTHOR:    BINGHUAN W LI <lbinghuan[at]outlook.com>
 *  @DATE:      JULY 09, 2022   - create
 *              June 01, 2023   - add submission section
 *
 *  @github:    https://github.com/binghuan-li/Exam-Bank-Search-Engine
 *
 */


require_once(realpath(dirname(__FILE__)) . './Classes/System.php');
require_once(realpath(dirname(__FILE__)) . './Classes/Administration.php');

// once passed the autentication 
$adminSession = new Administration();
?>
<!DOCTYPE HTML>
<html lang='en'>
<head>
	<link rel='shortcut icon' type='image/jpg' href='./images/book-solid.svg'>
	<link rel="stylesheet" type="text/css" href="./css/administration.css">
</head>
<title>Administration</title>
<body>
	<h2>Exam Bank Adminstration Tools</h2>
<!------------------------------------------------------------------->	
	<section class="module">
    	<h3>Exam Bank Emergency Button</h3>
    	<?php
    	$adminSession->access = $adminSession->availabilityChecker();
    	if($adminSession->access == 1){ // 1 is on
                echo "<div style='color:red;'>Current page availability: ON </div><br />";
    	    } else if ($adminSession->access  == -1){ // 0 is off
                echo "<div style='color:red;'>Current page availability: OFF </div><br />";
            } else{// conditions other than 0/1, errors!!
                echo "<div style='color:red;'>Wrong status! Reset is required.</div><br />";
            }
        ?>
    	<form method="POST">
    		<input type="radio" name="exam" value="examOn" id="examOn" <?php if($adminSession->access == 1){?>checked<?php }?> required/><label>Exam Bank Available</label> <br>
    		<input type="radio" name="exam" value="examOff" id="examOff"  <?php if($adminSession->access == -1){?>checked<?php }?> required/><label>Exam Bank Unavilable</label><br><br>
    		<textarea id="msg" name="msg"  rows="4" cols="50" placeholder="why?"></textarea><br><br>
    		<button type='submit' value='submit' name='submit' class='button'>Submit Change</button>
    	</form>
    
    	<?php  // write condition (0/1) to pageAvailability.txt
            if(isset($_POST['submit'])){
                if($_POST['exam']=="examOn"){
                    $flag = 1;
                }else if ($_POST['exam']=="examOff"){
                    $flag = 0;
                }
                if(!empty($flag)){
                    $adminSession -> updateServiceStatus($flag);
                }
                
                if (!empty($_POST['msg'])){
                    $adminSession -> writeMaintenanceMsg($_POST['msg']);
                }
                header("Refresh:0");
            }
        ?>
	</section><br />
<!------------------------------------------------------------------->
	<section class="module">
    	<h3>EBSE Download Statistics</h3> <br>
    
    	<form method='get'>
    		<label>Number of rank (defalut is 10)</label><br>
    		<input type="number" name="num" placeholder="10">
    	</form>
    	<br>
        <?php 
        $num = $_GET['num'] ?? '10';  
        echo "Number of rank to dispaly: ". $num."<br><br>";
        $adminSession -> downloadStatistics($num);
        ?>
    </section><br />
    <!------------------------------------------------------------------->
	<section class="module">
    	<h3>EBSE Contribution Control Panel</h3> <br>
        <?php 
        $adminSession -> dispStagingSummary();
        if (isset($_GET['view']) and $_SERVER['REQUEST_METHOD'] == "GET"){
            $adminSession -> viewFile($_GET['view']);
        }
        
        if (isset($_GET['confirm']) and $_SERVER['REQUEST_METHOD'] == "GET"){
            $variable = explode("_", $_GET['confirm']);
            $adminSession->confirmStaging($variable[0], $variable[1], $variable[2]);
        }
        
        if(isset($_GET['delete']) and $_SERVER['REQUEST_METHOD'] == "GET"){
            $variable = explode("_", $_GET['confirm']);
            $adminSession->dumpFile($variable[0], $variable[1], $variable[2]);
        }
        ?>
        <br>
    </section><br />
<!------------------------------------------------------------------->
	<br><HR>
	<section>
        <form action='./welcome.php' method='post'>
        	<button type='submit' name='EBSE' class='button'>EBSE</button>
        </form>
        <form action='./Authenticator/logout.php' method='post'>
        	<button type='submit' name='logout' class="button">logout</button>
        </form>
    	<b>We use cookies.</b> Fail to set the cookies would result in unsuccessful login and search.
	</section>
</body>
</html>

