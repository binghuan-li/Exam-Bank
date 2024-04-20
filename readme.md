## EXAM BANK SEARCH ENGINE (EBSE)


### The lastest version was developed and tested with PHP version 7.4 and above

### Your PHP instance must also have the following extensions enabled:
  * MySQLi
  * Sessions
  * Mail
  
### Classes:
  * `System` - this class provides basic functions as a parent class, including database connection (based on MySQLi), system log, service availability checker.
  * `ExamSearch` - this class is a child class of `System`, providing basic functions for searching, retrieveing and download paper.
  * `Maintenance` - this class is a child class of `System`, providing basic functions for system maintenance information display.
  * `Adminstration` - this calss is a child class of `System`, providing administrtive functions including download statistics and time settings.
  * `Submission` - receive and sanitise user submissions. Currently under developement.

### Authenticator
We use `Sessions` to hold user authentication information. Functions are currently under developement.
  
### MySQL Database (v5.6+) Table Structure

|ID|Year|Module|Sub-Module|Exam-Type|Note|
|---|---|---|---|---|---|
| Primart int(5) UNSIGNED AI | varchar(20)	latin1_swedish_ci |varchar(100)	latin1_swedish_ci|varchar(100)	latin1_swedish_ci|varchar(20)	latin1_swedish_ci|varchar(10)	latin1_swedish_ci|

### Configurations
Configurations have now all been migrated to `config.php` under the directory `Classes`.


