<?php
/*  EXAM BANK SEARCH ENGINE (EBSE) - welcome
 * 
 *  this page provides EBSE services for the authenticated users
 *
 *  @AUTHOR:    BINGHUAN W LI <lbinghuan[at]outlook.com>
 *  @DATE:      JULY 09, 2022
 *  
 *  @github:    https://github.com/binghuan-li/Exam-Bank-Search-Engine
 *
 */


// require_once(realpath(dirname(__FILE__)) . './Classes/System.php');
// require_once(realpath(dirname(__FILE__)) . './Classes/ExamSearch.php');
// require_once(realpath(dirname(__FILE__)) . './Classes/Maintenance.php');
include './Classes/System.php';
include './Classes/ExamSearch.php';
include './Classes/Maintenance.php';


// if passed authentication, perform page availability checker
$session = new System();
$session->access = $session -> availabilityChecker();

if($session->access != 1){
    if ($session->access == 0 || $session->access == -1){
        $session = new Maintenance();
        $session -> displayMaintenancePage();
    }else{
        $session = new Maintenance();
        $session -> displayNoAccessPage();
    }
}else{
    $session = new ExamSearch();
    // ok, call systemLog function 
    session_start();
    
    function dispList($session){
        $session -> getYear();
        $session -> getModule($_SESSION['module_selected']);
        $session -> getType($_SESSION['type_selected']);
    }
    function dispEmptyList($session){
        $session -> getYear();
        $session -> getModule();
        $session -> getType();
    }
    
    
    if(isset($_GET['download']) and $_SERVER['REQUEST_METHOD'] == "GET"){ // set here to aviod being affected by header
        $download_file = $_GET['download'];
        $session -> downloadFile($download_file);
        //$session -> downloadRecord($conn, $download_file, $_SESSION["username"]); // add a download record to db
    }

?>
<!DOCTYPE HTML>
<html lang='en'>
<head>	
<title>EBSE</title>
	<link rel='shortcut icon' type='image/jpg' href='./images/book-solid.svg'>
	<link rel='stylesheet' type='text/css' href='./css/engine.css'>
</head>
<body>
            
	<div class='container2'>
		<img src='./images/exam.jpg' width=100% alt='ExamBank'/>
        <div class='top-right'><h3><i><b>Exam Bank Search Engine (EBSE)</b></i></h3></div>
        <div class='bottom-right'><i><b>2022 Beta release - v 1.4.6 </b></i></div>
    </div>
    <br><br>
    
    <?php 
    if (isset($_GET['submit'])&& isset($_GET['module']) && isset($_GET['type'])){
        $_SESSION['module_selected'] = $_GET['module'];
        $_SESSION['type_selected'] = $_GET['type'];?>
        <div class="search"> 
        	<form method="GET">
    			<?php dispList($session);?>
    			<button type="submit" name="submit" value="submit" class='button button5'>Search!</button>
    		</form>
    	</div>
        <?php
        if(isset($_GET['year'])){
            $year_selected = $_GET['year'];
            $sqlSearch = "SELECT Year, Module, `Sub-Module`, `Exam-Type`, `Note` FROM ". TABLE_NAME. " WHERE (`Year`='$year_selected' AND `Sub-Module`='".$_SESSION['module_selected']."' AND `Exam-Type`='".$_SESSION['type_selected'].")";
        }else{
            $sqlSearch = "SELECT Year, Module, `Sub-Module`, `Exam-Type`, `Note` FROM ". TABLE_NAME. " WHERE (`Sub-Module`='".$_SESSION['module_selected']."' AND `Exam-Type`='".$_SESSION['type_selected']."')";
        }      
        $session -> search($sqlSearch);
    }else{
    ?>
    <div class="search"> 
        <form method="GET">
    		<?php dispEmptyList($session);?>
    		<button type="submit" name="submit" value="submit" class='button button5'>Search!</button>
    	</form>
    </div>
    <?php
    }
    ?>
    
    <br><HR>    
    <form action='./Authenticator/logout.php'>
        <button type='submit' name='logout' class='button button5'>logout</button>
    </form>
    <b>We use cookies.</b> Fail to set the cookies would result in unsuccessful login and search.<br><br>
    Report an issue <a href='mailto:ebse@binghuan.li'>here</a>.
</body>
</html>
<?php }?>