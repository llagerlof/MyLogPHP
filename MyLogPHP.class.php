<?php

/*
------------------------------
MyLogPHP 1.2.2
http://mylogphp.googlecode.com
------------------------------

Copyright (c) 2014, Lawrence Lagerlof ( @llagerlof, llagerlof@gmail.com )
All rights reserved.

-- New BSD License --

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of Lawrence Lagerlof nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL LAWRENCE LAGERLOF BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

class MyLogPHP {

	// Name of the file where the message logs will be appended.
	private $LOGFILENAME;
	
	// Define the separator for the fields. Default is comma (,).
	private $SEPARATOR;

	// headers
	private $HEADERS;
	
	// Default tag.
	const DEFAULT_TAG = '--';

		
	// CONSTRUCTOR
	function MyLogPHP($logfilename = './_MyLogPHP-1.2.log.csv', $separator = ',') {
		$this->LOGFILENAME = $logfilename;
		$this->SEPARATOR = $separator;
		$this->HEADERS =
			'DATETIME' . $this->SEPARATOR . 
			'ERRORLEVEL' . $this->SEPARATOR .
			'TAG' . $this->SEPARATOR .
			'VALUE' . $this->SEPARATOR .
			'LINE' . $this->SEPARATOR .
			'FILE';
	}
	
	
	// Private method that will write the text logs into the $LOGFILENAME.
	private function log($errorlevel = 'INFO', $value = '', $tag) {

		$datetime = date("Y-m-d H:i:s");
		if (!file_exists($this->LOGFILENAME)) {
			$headers = $this->HEADERS . "\n";
		}
		
		$fd = fopen($this->LOGFILENAME, "a");
		
		if (@$headers) {
			fwrite($fd, $headers);
		}
		
		$debugBacktrace = debug_backtrace();
		$line = $debugBacktrace[1]['line'];
		$file = $debugBacktrace[1]['file'];

		$value = str_replace(array("\r\n", "\n\r", "\r", "\n"), " ", trim($value));
		
		$entry = array($datetime,$errorlevel,$tag,$value,$line,$file);
		
		fputcsv($fd, $entry, $this->SEPARATOR);
		
		fclose($fd);
		
	}
	
	
	// Function to write not technical INFOrmation messages that will be written into $LOGFILENAME.
	function info($value = '', $tag = self::DEFAULT_TAG) {
	
		self::log('INFO', $value, $tag);
	}
	
	
	// Function to write WARNING messages that will be written into $LOGFILENAME.
	// These messages are non-fatal errors, so, the script will work properly even
	// if WARNING errors appears, but this is a thing that you must ponderate about.
	function warning($value = '', $tag = self::DEFAULT_TAG) {
	
		self::log('WARNING', $value, $tag);
	}


	// Function to write ERROR messages that will be written into $LOGFILENAME.
	// These messages are fatal errors. Your script will NOT work properly if an ERROR happens, right?
	function error($value = '', $tag = self::DEFAULT_TAG) {
	
		self::log('ERROR', $value, $tag);
	}

	// Function to write DEBUG messages that will be written into $LOGFILENAME.
	// DEBUG messages are highly technical issues, like an SQL query or result of it.
	function debug($value = '', $tag = self::DEFAULT_TAG) {
	
		self::log('DEBUG', $value, $tag);
	}
	
}


// EXAMPLES

/*
$log = new MyLogPHP('logname-1.2.csv',';'); // or MyLogPHP('logname-1.2.csv') // or MyLogPHP('logname-1.2.csv',';')

$log->info('The program starts here.');

$log->warning('This problem can affect the program logic');

$log->warning('Use this software as your own risk!');

$log->info('Lawrence Lagerlof','AUTHOR');

$log->info('Asimov rulez','FACT');

$log->error('Everything crash and burn','SOLVED');

$log->debug("select * from table",'DB');
*/

?>