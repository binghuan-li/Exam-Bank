<?php
/*  EXAM BANK SEARCH ENGINE (EBSE) - config
 *  EBSE configurations
 *
 *  @AUTHOR:    BINGHUAN W LI <lbinghuan[at]outlook.com>
 *  @DATE:      MAY 31, 2023
 *
 *  @github:    https://github.com/binghuan-li/Exam-Bank-Search-Engine
 *
 */

// timezone calibration
date_default_timezone_set('Europe/London');

// servive open and close hour
define('OPEN_HOUR', '1');
define('CLOSE_HOUR', '24');

// main database connection
define ('DB_SERVER', '');
define ('DB_USERNAME', '');
define ('DB_PASSWORD', '');
define ('DB_NAME', '');

// database table names
define('DUE_DATE', '2022-06-17');
define('PAPER_TABLE_NAME','EXAM_BANK');
define('PAPER_BACKUP_TABLE_NAME','EXAM_BANK');
define('LOG_TABLE_NAME', 'system_log');

// path to the paper stroage directory
define('FILE_DIR', '/Notes/Archive/papers/');

// paper submission staging directory and db table
define('TARGET_DIR', '/staging/');
define('STAGING_TABLE_NAME', 'staging_bank');
