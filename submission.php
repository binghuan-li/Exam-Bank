<?php

/*  EXAM BANK SEARCH ENGINE (EBSE) - submission
 *
 *  receives user submission and post processing
 *
 *  @AUTHOR:    BINGHUAN W LI <lbinghuan[at]outlook.com>
 *  @DATE:      MAY 31, 2023
 *
 *  @github:    https://github.com/binghuan-li/Exam-Bank-Search-Engine
 *
 */

include './Classes/System.php';
include './Classes/Maintenance.php';
include './Classes/Submission.php';

session_start();


if(isset($_POST["submit"]) && !empty($_POST["examName"]) && !empty($_POST["examYear"]) && !empty($_POST["examType"])) {
    $submission = new Submission($_POST["examName"], $_POST["examYear"], $_POST["examType"]);
    if ($submission -> validate()){
        if ($submission -> move()){
            echo 'Submission has been processed successfully!';
        }
    }
}
?>


<!DOCTYPE HTML>
<html lang='en'>
<head>
	<title>Submission</title>
	<link rel='shortcut icon' type='image/jpg' href='./images/book-solid.svg'>
	<link rel="stylesheet" type="text/css" href="./css/engine.css">
</head>
<body>


<form method="post" enctype="multipart/form-data">

<label for="examName">Name of paper:</label><br>
<input type="text" name="examName">
<br><br>

<label for="examYear">Year of paper:</label><br>
<select name="examYear" id="examYear">
	<option value="2022-2023">2022-2023</option>
	<option value="2021-2022">2021-2022</option>
    <option value="2020-2021">2020-2021</option>
    <option value="2019-2020">2019-2020</option>
    <option value="2018-2019">2018-2019</option>
    <option value="2017-2018">2017-2018</option>
    <option value="2016-2017">2017-2018</option>
</select>
<br><br>

<label for="examType">Type of exam:</label><br>
<select name="examType" id="examType">
	<option value="main">Main (Final) Exam</option>
	<option value="mastery">Mastery Exam</option>
    <option value="progress test">Progress Test</option>
    <option value="coursework">Coursework</option>
</select>
<br><br>

<label for="file">Select the local file to upload:</label><br>
<input type="file" name="file" id="file">
<br><br>

<input type="submit" value="submit" name="submit" class='button button5'>
</form>


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



