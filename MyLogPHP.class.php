<?php
/**
 * MyLogPHP 1.2.10
 *
 * MyLogPHP is a single PHP class to easily keep log files in CSV format.
 *
 * @package    MyLogPHP
 * @subpackage Common
 * @author     Lawrence Lagerlof <llagerlof@gmail.com>
 * @copyright  2014 Lawrence Lagerlof
 * @link       http://github.com/llagerlof/MyLogPHP
 * @license    http://opensource.org/licenses/BSD-3-Clause New BSD License
 */

class MyLogPHP {

	/**
	 * Name of the file where the message logs will be appended.
	 * @access private
	 */
	private $LOGFILENAME;

	/**
	 * Define the separator for the fields. Default is comma (,).
	 * @access private
	 */
	private $SEPARATOR;

	/**
	 * The first line of the log file.
	 * @access private
	 */
	private $HEADERS;

	/* @const Default tag. */
	const DEFAULT_TAG = '--';

	/* @const overwrite file in out() method. */
	const OVERWRITE = null;

	/* @const append to file in out() method. */
	const APPEND = FILE_APPEND;

	/**
	 * Constructor
	 * @param string $logfilename Path and name of the file log.
	 * @param string $separator Character used for separate the field values.
	 */
	public function __construct($logfilename = './_MyLogPHP-1.2.log.csv', $separator = ',') {

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

	/**
	 * Private method that will write the text messages into the log file.
	 *
	 * @param string $errorlevel There are 4 possible levels: INFO, WARNING, DEBUG, ERROR
	 * @param string $value The value that will be recorded on log file.
	 * @param string $tag Any possible tag to help the developer to find specific log messages.
	 */
	private function log($errorlevel = 'INFO', $value = '', $tag) {

		$datetime = date("Y-m-d H:i:s");
		if (!file_exists($this->LOGFILENAME)) {
			$headers = $this->HEADERS . "\n";
		}

		$fd = fopen($this->LOGFILENAME, "a");

		if (!empty($headers)) {
			fwrite($fd, $headers);
		}

		$debugBacktrace = debug_backtrace();
		$line = $debugBacktrace[1]['line'];
		$file = $debugBacktrace[1]['file'];

		$value = preg_replace('/\s+/', ' ', trim($value));

		$entry = array($datetime,$errorlevel,$tag,$value,$line,$file);

		fputcsv($fd, $entry, $this->SEPARATOR);

		fclose($fd);
	}

	/**
	 * Function to write non INFOrmation messages that will be written into $LOGFILENAME.
	 *
	 * @param string $value
	 * @param string $tag
	 */
	public function info($value = '', $tag = self::DEFAULT_TAG) {

		self::log('INFO', $value, $tag);
	}

	/**
	 * Function to write WARNING messages that will be written into $LOGFILENAME.
	 *
	 * Warning messages are for non-fatal errors, so, the script will work properly even
	 * if WARNING errors appears, but this is a thing that you must ponderate about.
	 *
	 * @param string $value
	 * @param string $tag
	 */
	public function warning($value = '', $tag = self::DEFAULT_TAG) {

		self::log('WARNING', $value, $tag);
	}

	/**
	 * Function to write ERROR messages that will be written into $LOGFILENAME.
	 *
	 * These messages are for fatal errors. Your script will NOT work properly if an ERROR happens, right?
	 *
	 * @param string $value
	 * @param string $tag
	 */
	public function error($value = '', $tag = self::DEFAULT_TAG) {

		self::log('ERROR', $value, $tag);
	}

	/**
	 * Function to write DEBUG messages that will be written into $LOGFILENAME.
	 *
	 * DEBUG messages are for variable values and other technical issues.
	 *
	 * @param string $value
	 * @param string $tag
	 */
	public function debug($value = '', $tag = self::DEFAULT_TAG) {

		self::log('DEBUG', $value, $tag);
	}

	/**
	 * Function to write the print_r return value to a file.
	 *
	 * Allow to append a variable value to the end of the file _OUT_MyLogPHP.txt
	 *
	 * @param mixed $variable_to_output
	 * @param mixed $options
	 */
	public static function out($variable_to_output, $options = null) {
		// $options = array('OUTPUT_PATH' => '', 'WRITE_MODE' => APPEND, 'LABEL' => 'Label');
		if (empty($options)) {
			$write_mode = FILE_APPEND;
			$output_path = '';
			$label = null;
		} elseif (is_string($options)) {
			$write_mode = FILE_APPEND;
			$output_path = '';
			$label = $options;
		} elseif (is_array($options)) {
			$output_path = array_key_exists('OUTPUT_PATH', $options) ? $options['OUTPUT_PATH'] : '';
			$write_mode = array_key_exists('WRITE_MODE', $options) ? $options['WRITE_MODE'] : FILE_APPEND;
			if ($write_mode !== FILE_APPEND) {
				$write_mode = null;
			}
			$label = array_key_exists('LABEL', $options) ? $options['LABEL'] : null;
		}

		$print_r_variable_to_output = print_r($variable_to_output, true) . "\n\n" . str_repeat('-', 80) . "\n";

		$variable_type = gettype($variable_to_output);

		$datetime = date("H:i:s Y-m-d");
		if (!empty($label)) {
			$datetime_span_position = 80 - (strlen('[' . $label . '] ' . $variable_type) + strlen($datetime));
			$print_r_variable_to_output = '[' . $label . '] ' . $variable_type . str_repeat(' ', $datetime_span_position) . $datetime . "\n\n" . $print_r_variable_to_output;
		} else {
			$datetime_span_position = 80 - (strlen($variable_type) + strlen($datetime));
			$print_r_variable_to_output = $variable_type . str_repeat(' ', $datetime_span_position) . $datetime . "\n\n" . $print_r_variable_to_output;
		}

		file_put_contents($output_path . '_OUT_MyLogPHP.txt', $print_r_variable_to_output, $write_mode);
	}

}
?>