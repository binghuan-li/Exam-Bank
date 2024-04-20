<?php
/*  EXAM BANK SEARCH ENGINE (EBSE) - logout
 *
 *  @AUTHOR:    BINGHUAN W LI <lbinghuan[at]outlook.com>
 *  @DATE:      SEPT 01, 2022
 *
 *  @github:    https://github.com/binghuan-li/Exam-Bank-Search-Engine
 *
 */
session_start();
session_unset();
session_destroy();

echo "<h2>You have been logged out successfully.</h2>"
?>
