<h1>MyLogPHP</h1>

MyLogPHP is a single PHP class to easily keep log files in CSV format.

<h2>Install the latest version</h2>

<h3>Direct download (just include this file in your script)</h3>
<a href="https://raw.githubusercontent.com/llagerlof/MyLogPHP/master/MyLogPHP.class.php">MyLogPHP.class.php</a> (1.2.16)
<br>
<h3>Or you can use Composer</h3>
Edit your project's **composer.json** and add the required package: **"llagerlof/mylogphp": "dev-master"**

Eg:

    {
        "name": "your/project",
        "require": {
            "llagerlof/mylogphp": "dev-master"
        }
    }
<h2>How to read the generated log file</h2>
Any CSV reader can be used to open the generated logs, but If you need a recomendation, try the <a href="http://www.nirsoft.net/utils/csv_file_view.html">CSVFileView</a> or <a href="http://www.log-expert.de">LogExpert</a>. Both can parse/show the CSV columns and watch realtime changes in log file (like tail on Linux).

<h2>Features</h2>

* One single file to rule them all!
* Super easy start.
* Two extra columns are automatically added in the CSV: the number of execution line and the path of host script where the log was called.
* Minimum PHP version: 5.1

<h2>Want more features? I am listening!</h2>

Create an issue in https://github.com/llagerlof/MyLogPHP/issues/new

<h2>Quick start</h2>

Include in your script the file "MyLogPHP.class.php" (not needed if you are using the composer version).

```php
include('MyLogPHP.class.php');
```

Instantiate the object. Optionally you can pass the log file name and the separator as a parameter. Default log file name is "`_`MyLogPHP-1.2.log.csv" in current folder, and default separator is comma (,).

```php
$log = new MyLogPHP('./log/debug.log.csv');
```

Make sure the directory where the log will be created is writable.

Call method "info", "warning", "error" or "debug" to write the messages.
The first parameter is the message, the optional second parameter is a free tag at your choice to help you filter the log when opened by an spreadsheet software, like `OpenOffice Calc` or `Microsoft Excel`.

```php
$log->info('This message will be logged in the file debug.log.csv','TIP');
```

That's it!

<h2>Examples</h2>

```php
$log = new MyLogPHP();

$log->info('The program starts here.');

$log->warning('This problem can affect the program logic');

$log->warning('Use this software at your own risk!');

$log->info('Lawrence Lagerlof','AUTHOR');

$log->info('Asimov rulez','FACT');

$log->error('Everything crash and burn','IE');

$log->debug("select * from table",'DB');

```

Writing a variable content to file "_OUT_MyLogPHP.txt"

```
MyLogPHP::out($data);

MyLogPHP::out($data, array('LABEL' => 'variable $data');

MyLogPHP::out($data, 'variable $data'); // same as above

MyLogPHP::out($data, array('LABEL' => 'variable $data', 'WRITE_MODE' => MyLogPHP::OVERWRITE));

MyLogPHP::out($data, array('LABEL' => 'variable $data', 'WRITE_MODE' => MyLogPHP::APPEND)); // WRITE_MODE can be OVERWRITE or APPEND (APPEND is default)

```

<h2>Changelog</h2>

**1.2.16**
* Put an optional config file (MyLogPHP.conf in the same directory of the class) with one line containing "enabled=false" to disable logging.

**1.2.15**
* Changed output format of out() method.

**1.2.14**
* Added method name to output of the out() method.

**1.2.13**
* Added the file and execution line info to output of the out() method.

**1.2.12**
* You can define a $_SESSION variable to set where the file will be created by the out() method.

**1.2.11**
* out() method is now static.

**1.2.10**
* Can pass a string to act as LABEL to the second parameter of out() method.

**1.2.9**
* Removed supressed warnings from variable $headers and date() functions.

**1.2.8**
* Add more options and verbosity to out() method.

**1.2.7**
* New method out() allow append a variable value to the end of the file _OUT_MyLogPHP.txt

**1.2.6**
* Class updated for PHP 7 (thanks [@clintre](https://github.com/clintre)).

**1.2.5**
* Solved a bug that was preventing the replacement of consecutives spaces and tabs for one space.

**1.2.4**
* All consecutives spaces and tabs are removed from output.

**1.2.3**
* PHPDOC comment style applied to the class. 
* An issue with the newer version of PHP and the date() function was solved.

**1.2.2**
* Line breaks of VALUE field are converted to spaces to prevent some CSV readers wrongly interpret line breaks as new CSV lines.
* The VALUE field now is trimmed before output.

**1.2.1**
* Disable a warning message if an internal variable is not set.

**1.2**
* Two columns added in CSV output: LINE and FILE.

**1.1**
* Added support to choose the field separator. Comma is still the default.
