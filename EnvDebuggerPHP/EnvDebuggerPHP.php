<?php

/**
 *
 *
 * This file is part of the EnvDebuggerPHP library.
 *
 * Copyright (c) 2024, Geovanny Castelo
 * All rights reserved.
 *
 * Licensed under the MIT License. See the LICENSE file 
 * in the project root for more information.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the "Software"), to deal 
 * in the Software without restriction, including without limitation the rights 
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
 * copies of the Software, and to permit persons to whom the Software is 
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in 
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS 
 * IN THE SOFTWARE.
 *
 * @package	EnvDebuggerPHP
 * @author	Geovanny Castelo
 * @license	MIT
 * @version	1.0.0
 *
 *
 *                                 www.geocys.com
 *
 */

set_error_handler("exceptionErrorHandler");
set_exception_handler("exceptionHandler");
register_shutdown_function("shutdownHandler");

function exceptionErrorHandler($severity, $message, $file, $line){
	EnvDebuggerPHP::exceptionErrorHandler($severity, $message, $file, $line);
}

function exceptionHandler($exception){
	EnvDebuggerPHP::exceptionHandler($exception);
}

function shutdownHandler(){
	EnvDebuggerPHP::shutdownHandler();
}

class EnvDebuggerPHP{
	private const FILE_HTML_HELP		= 'help.html';
	private const FILE_JS_BAR			= 'bar.js';
	private const FILE_JS_ERROR			= 'error.js';
	private const FILE_JS_MAIN			= 'main.js';
	private const FILE_JS_MESSAGE		= 'message.js';
	private const FILE_JS_TITLE			= 'title.js';
	private const FILE_JS_STATEMENTS	= 'statements.js';
	private const FILE_JS_UPDATE		= 'update.js';
	private const FILE_JSON_CONFIG		= 'config.json';
	private const FILE_JSON_VARLIST		= 'vars.json'; 
	private const FILE_PHP_UPDATE_VARS	= 'update.php';
	private const FILE_TXT_LOG			= 'log.txt';
	private const FILE_TXT_ERROR_LOG	= 'error_log.txt';
	
	private const INPUT_ENVIRONMENT_NAME = 'EnvDebuggerPHP-environment';
	
	private const WORK_ENVIRONMENT_MODE		= 'debug eco';

	private static $pathMain;
	private static $pathDebugging;
	private static $pathDebug;
	private static $pathDev;
	private static $pathTest;
	private static $title;
	private static $variableList;

	private static $fileFolders = [
		'debugging' => 'debugging',
		'debug'  => 'debug',
		'dev'	=> 'dev',
		'test'	=> 'test'
	];

	private static $environment;
	private static $environments = [
		'debug'			=> 'debug',
		'development'	=> 'development',
		'testing'		=> 'testing',
		'production'	=> 'production'
	];

	protected static $correctStart = false;

	private function __construct(){
	}

	private static function checkPath($path){
		self::method(__METHOD__);
		return str_replace('\\', '/', $path);
	}

	private static function correctStart(){
		self::method(__METHOD__);
		self::configureEnvironment();
		self::createFiles();
		return  self::loadFiles() && self::updateConfig() &&
				file_exists(self::$pathDebugging) && 
				file_exists(self::$pathDebug) && 
				file_exists(self::$pathDev) && 
				file_exists(self::$pathTest);
	}

	private static function configureEnvironment(){
		self::method(__METHOD__);
		$result = in_array(self::$environment, self::$environments) ? self::$environment : self::$environments['production'];
		switch ($result){
			case self::$environments['debug']:
				ini_set('display_errors', 1);
				ini_set('display_startup_errors', 0);
				error_reporting(E_ALL|E_NOTICE);
			break;
			case self::$environments['development']:
				ini_set('display_errors', 1);
				ini_set('display_startup_errors', 0);
				error_reporting(E_ALL);
			break;
			case self::$environments['testing']:
				ini_set('display_errors', 1);
				ini_set('display_startup_errors', 0);
				error_reporting(E_ERROR | E_WARNING);
			break;
			case self::$environments['production']:
			default:
				ini_set('display_errors', 0);
				ini_set('display_startup_errors', 0);
				error_reporting(0);
			break;
		}
		return $result;
	}
	
	private static function convertToHtml($data){
		self::method(__METHOD__);
		if (is_array($data) || is_object($data)) {
			$html = '<pre>' . print_r($data, true) . '</pre>';
		} else {
			$html = (string)$data;
			$html = nl2br($html);
		}
		return $html;
	}

	private static function convertToString($data){
		self::method(__METHOD__);
		if(is_array($data) || is_object($data)){
			return '<pre>' . htmlspecialchars(print_r($data, true)) . '</pre>';
		} else{
			return htmlspecialchars((string)$data);
		}
	}

	private static function createFiles(){
		self::method(__METHOD__);
		self::$pathMain = dirname(__FILE__).DIRECTORY_SEPARATOR;
		if(self::$pathDebugging === null){
			$root = dirname(dirname(__FILE__)); 
			$pathDebugging = $root . DIRECTORY_SEPARATOR . self::$fileFolders['debugging'];
			self::$pathDebugging = $pathDebugging . DIRECTORY_SEPARATOR;
			self::$pathDebug = $pathDebugging . DIRECTORY_SEPARATOR . self::$fileFolders['debug']. DIRECTORY_SEPARATOR;
			self::$pathDev	= $pathDebugging . DIRECTORY_SEPARATOR . self::$fileFolders['dev']. DIRECTORY_SEPARATOR;
			self::$pathTest  = $pathDebugging . DIRECTORY_SEPARATOR . self::$fileFolders['test']. DIRECTORY_SEPARATOR;
			if(!file_exists(self::$pathDebugging)){
				mkdir(self::$pathDebugging, 0755, true);
			}
			if(!file_exists(self::$pathDebug)){
				mkdir(self::$pathDebug, 0755, true);
			}
			if(!file_exists(self::$pathDev)){
				mkdir(self::$pathDev, 0755, true);
			}
			if(!file_exists(self::$pathTest)){
				mkdir(self::$pathTest, 0755, true);
			}
		}
	}

	private static function echoReplaceFileJS($search, $replace, $fileName){
		self::method(__METHOD__);
		$content = file_get_contents($fileName);
		if($content === false){
			self::errorLog('Could not load the JavaScript file: ' . $fileName);
			return false;
		}
		$newContent = str_replace($search, $replace, $content);
		return self::echoJS($newContent);
	}

	private static function echoFileJS($fileName){
		self::method(__METHOD__);
		$content = file_get_contents($fileName);
		if($content === false){
			self::errorLog('Could not load the JavaScript file: ' . $fileName);
			return false;
		}
		return self::echoJS($content);
	}

	private static function echoJS($content){
		self::method(__METHOD__);
		$newContent = self::removeCommentBlock($content);
		echo "<script>".$newContent."</script>";
		return true;
	}

	private static function errorLog($message, $trace=null){
		self::method(__METHOD__);
		if (strpos(self::WORK_ENVIRONMENT_MODE, 'debug') !== false) {
			if ($trace === null) {
				$trace = debug_backtrace();
			}
			$time = date('Y-m-d H:i:s');
			$file = $trace[0]['file'];
			$line = $trace[0]['line'];
			$path = self::$pathMain.self::FILE_TXT_LOG;
			error_log("  Time:		 $time" . PHP_EOL, 3, $path);
			error_log("  File:		 $file" . PHP_EOL, 3, $path);
			error_log("  Line:		 $line" . PHP_EOL, 3, $path);
			error_log("  $message" . PHP_EOL,	3, $path);
			error_log("---------------------------------------------------------------------" . PHP_EOL, 3, $path);
		}
	}

	private static function existsFile($environment=null){
		self::method(__METHOD__);
		if($environment===null){
			return file_exists(self::$pathDebugging);
		}
		switch($environment){
			case self::$environments['debug']: 
				return file_exists(self::$pathDebug);
			break;
			case self::$environments['development']: 
				return file_exists(self::$pathDev);
			break;
			case self::$environments['testing']: 
				return file_exists(self::$pathTest);
			break;
		}
		return false;
	}

	private static function getDirectory($directory){
		self::method(__METHOD__);
		if (file_exists($directory)) {
			$directory = rtrim($directory, DIRECTORY_SEPARATOR . '/\\');
			$pathParts = explode(DIRECTORY_SEPARATOR, $directory);
			$lastPart = end($pathParts);
			return $lastPart;
		}
		return '';
	}

	private static function getFileName($path){
		return basename($path);
	}

	private static function getNameDirectory(){
		self::method(__METHOD__);
		$currentDir = __DIR__;
		$pathParts = explode(DIRECTORY_SEPARATOR, $currentDir);
		return end($pathParts);
	}

	private static function getPath($environment=null){
		self::method(__METHOD__);
		if($environment===null){
			return self::$pathDebugging;
		}
		switch($environment){
			case self::$environments['development']: 
				return self::$pathDev;
			break;
			case self::$environments['testing']: 
				return self::$pathTest;
			break;
			case self::$environments['debug']: 
			default:
				return self::$pathDebug;
			break;
		}
		return false;
	}

	private static function getURL(){
		self::method(__METHOD__);
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$host = $_SERVER['HTTP_HOST'];
		$path = dirname($_SERVER['SCRIPT_NAME']);
		$currentUrl = rtrim($protocol . $host . $path, '/') . '/';
		return $currentUrl;
	}

	private static function getVariableValue($filePath, $variableName){
		self::method(__METHOD__);
		if (!file_exists($filePath)) {
			return self::isCorrectEnvironment();
		}
		$fileInfo = pathinfo($filePath);
		$extension = strtolower($fileInfo['extension']);
		switch ($extension) {
			case 'php':  return self::getVariableValueFromPHPFile($filePath, $variableName);
			case 'json': return self::getVariableValueFromJSONFile($filePath, $variableName);
			case 'txt':  return self::getVariableValueFromTXTFile($filePath, $variableName);
			case 'xml':  return self::getVariableValueFromXMLFile($filePath, $variableName);
			default:
				self::errorLog('Unsupported file extension for ' . $filePath);
				return self::isCorrectEnvironment();
		}
	}

	private static function getVariableValueFromPHPFile($filePath, $variableName){
		self::method(__METHOD__);
		if (!file_exists($filePath)) {
			return self::isCorrectEnvironment();
		}
		$fileContents = file_get_contents($filePath);
		$pattern = '/\$' . preg_quote($variableName) . '\s*=\s*[\'"]([^\'"]*)[\'"];/';
		if (preg_match($pattern, $fileContents, $matches)) {
			return self::isCorrectEnvironment($matches[1]);
		}
		return self::isCorrectEnvironment();
	}

	private static function getVariableValueFromJSONFile($filePath, $key){
		self::method(__METHOD__);
		if (!file_exists($filePath)) {
			return self::isCorrectEnvironment();
		}
		$fileContents = file_get_contents($filePath);
		$data = json_decode($fileContents, true);
		if (json_last_error() === JSON_ERROR_NONE && isset($data[$key])) {
			return self::isCorrectEnvironment($data[$key]);
		}
		return self::isCorrectEnvironment();
	}

	private static function getVariableValueFromTXTFile($filePath, $searchString){
		self::method(__METHOD__);
		if (!file_exists($filePath)) {
			return self::isCorrectEnvironment();
		}
		$fileContents = file_get_contents($filePath);
		$lines = explode(PHP_EOL, $fileContents);
		foreach ($lines as $line) {
			if (strpos($line, $searchString) !== false) {
				return self::isCorrectEnvironment(trim($line));
			}
		}
		return self::isCorrectEnvironment();
	}

	private static function getVariableValueFromXMLFile($filePath, $elementName){
		self::method(__METHOD__);
		if (!file_exists($filePath)) {
			return self::isCorrectEnvironment();
		}
		$fileContents = file_get_contents($filePath);
		$xml = simplexml_load_string($fileContents);
		if ($xml && isset($xml->$elementName)) {
			return self::isCorrectEnvironment((string) $xml->$elementName);
		}
		return self::isCorrectEnvironment();
	}

	private static function init(){
		self::method(__METHOD__);
		if(!self::$correctStart){
			self::start(self::$environments['production']); 
		}
	}

	private static function isCorrectEnvironment($environment = null){
		self::method(__METHOD__);
		$defaultEnvironment = self::$environments['production'];
		if($environment !== null && in_array($environment, self::$environments,)){
			return $environment;
		}
		return $defaultEnvironment;
	}

	private static function loadFiles(){
		self::method(__METHOD__);
		return (self::$correctStart?self::$correctStart:(self::loadHtml() && self::loadJS() && self::loadJson()));
	}

	private static function loadHtml(){
		self::method(__METHOD__);
		$origin = self::$pathMain . self::FILE_HTML_HELP;
		$destin = self::$pathDebugging . self::FILE_HTML_HELP;
		if(file_exists($origin) && pathinfo($origin, PATHINFO_EXTENSION) === 'html'){
			$destinDir = dirname($destin);
			if(!file_exists($destinDir)){
				self::errorLog('The destination directory does not exist: ' . $destinDir);
				return false;
			}
			if(copy($origin, $destin)){
				return true;
			} else{
				self::errorLog('Could not copy the file from ' . $origin . ' to ' . $destin);
				return false;
			}
		}
		return false;
	}

	private static function loadJS() {
		self::method(__METHOD__);
		if (self::$environments['production'] !== self::$environment) {
			if (self::loadMainJS() && self::loadStatementsJS()  && self::loadTitleJS() && self::loadBarJS() &&  self::loadUpdateJS()) {
				return true;
			}
			self::errorLog('Failed to load JS files');
		}
		return false;
	}

	private static function loadJson(){
		self::method(__METHOD__);
		$origin = self::$pathMain . self::FILE_JSON_CONFIG;
		$destin = self::$pathDebugging . self::FILE_JSON_CONFIG;
		if(file_exists($origin) && pathinfo($origin, PATHINFO_EXTENSION) === 'json'){
			$destinDir = dirname($destin);
			if(!file_exists($destinDir)){
				self::errorLog('The destination directory does not exist: ' . $destinDir);
				return false;
			}
			if(copy($origin, $destin)){
				return true;
			} else{
				self::errorLog('Could not copy the file from ' . $origin . ' to ' . $destin);
				return false;
			}
		}
		return false;
	}


	private static function loadBarJS(){
		self::method(__METHOD__);
		if(self::$environments['production'] !== self::$environment){
			$filePath  = self::$pathMain.self::FILE_JS_BAR;			
			return self::echoFileJS($filePath);
		}
		return false;
	}
	
	private static function loadMainJS(){
		self::method(__METHOD__);
		if(self::$environments['production'] !== self::$environment){
			$filePath  = self::$pathMain.self::FILE_JS_MAIN;			
			return self::echoFileJS($filePath);
		}
		return false;
	}
	
	private static function loadStatementsJS(){
		self::method(__METHOD__);
		if(self::$environments['production'] !== self::$environment){
			$pathConfigFile = self::getURL().self::getDirectory(self::$pathDebugging).'/'.self::FILE_JSON_CONFIG;
	 	 	$pathHelpFile   = self::getURL().self::getDirectory(self::$pathDebugging).'/'.self::FILE_HTML_HELP;
			$pathVarsFile   = self::getURL().self::getDirectory(self::$pathDebugging).'/'.self::FILE_PHP_UPDATE_VARS;
			$filePath  = self::$pathMain.self::FILE_JS_STATEMENTS;
			$searchReplace = [
				'__DEBUGGING_ENVIRONMENTS__'	=> 'ENVIRONMENTS',
				'__DEBUGGING_ENVIRONMENT_NAME__'=> self::INPUT_ENVIRONMENT_NAME,
				'__DEBUGGING_ERRORS__'			=> 'ERRORS',
				'__DEBUGGING_MESSAGES__'		=> 'MESSAGES',
				'__PATH_CONFIG_FILE__'			=> $pathConfigFile,
				'__PATH_HELP_FILE__'			=> $pathHelpFile,
				'__PATH_VARS_FILE__'			=> $pathVarsFile,
				'__DEBUGGING_VARIABLES__'		 => 'VARIABLES',
				'__DEBUGGING_TITLE__'			 => self::$title
			];
			return self::echoReplaceFileJS(array_keys($searchReplace), array_values($searchReplace), $filePath);
		}
		return false;
	}
	
	private static function loadTitleJS(){
		self::method(__METHOD__);
		if(self::$environments['production'] !== self::$environment){
			$filePath  = self::$pathMain.self::FILE_JS_TITLE;			
			return self::echoFileJS($filePath);
		}
		return false;
	}
	
	private static function loadUpdateJS(){
		self::method(__METHOD__);
		if(self::$environments['production'] !== self::$environment){
			$filePath  = self::$pathMain.self::FILE_JS_UPDATE;			
			return self::echoFileJS($filePath);
		}
		return false;
	}
	
	private static function loadUpdateVarsPHP( ){
		self::method(__METHOD__);
		$origin = self::$pathMain . self::FILE_PHP_UPDATE_VARS;
		$destin = self::$pathDebugging . self::FILE_PHP_UPDATE_VARS;
		if(file_exists($origin) && pathinfo($origin, PATHINFO_EXTENSION) === 'php') {
			$destinDir = dirname($destin);
			
			if (!file_exists($destinDir)) {
				self::errorLog('The destination directory does not exist: ' . $destinDir);
				return false;
			}
			
			if (copy($origin, $destin)) {
				return true;
			} else {
				self::errorLog('Could not copy the file from ' . $origin . ' to ' . $destin);
				return false;
			}
		}
		return false;
	}

	private static function readDataFileJSON($path){
		self::method(__METHOD__);
		if (file_exists($path)) {
			$currentData = file_get_contents($path);
			return json_decode($currentData, true);
		} else {
			return [];
		}
	}

	private static function replaceFilePHP($search, $replace, $filename){
		self::method(__METHOD__);
		$content = file_get_contents($filename);
		if ($content === false) {
			self::errorLog('Could not load the PHP file: ' . $filename);
			return false;
		}
		$newContent = str_replace($search, $replace, $content);
		if (file_put_contents($filename, $newContent) === false) {
			self::errorLog('Could not write to the PHP file: ' . $filename);
			return false;
		}
		return true;
	}
	
	private static function removeCommentBlock($input) {
		$pattern = '/\/\*\*(.*?)\*\//s';
		$output = preg_replace($pattern, '', $input);
		return trim($output);
	}


	private static function saveDataFileJSON($path, $data){
		self::method(__METHOD__);
		return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
	}


	private static function setTitle($title = null){
		self::method(__METHOD__);
		$environment = self::$environment;
		if($title !== null){
			self::$title = mb_strtoupper(self::convertToString($title), 'UTF-8');
		}else{
			self::$title = mb_strtoupper(self::$environment, 'UTF-8').' ENVIRONMENT';
		}		
	}

	private static function updateConfig(){
		self::method(__METHOD__);
		$fileConfigPath = self::$pathDebugging . self::FILE_JSON_CONFIG;
		$jsonData = file_get_contents($fileConfigPath);
		if($jsonData === false){
			self::errorLog("Error: Could not read the configuration file.");
			return false;
		}
		$config = json_decode($jsonData, true);
		if($config === null){
			self::errorLog("Error: Could not decode the JSON file.");
			return false;
		}
		$config['environment'] = self::$environment;
		$config['environments'] = self::$environments;
		$newJsonData = json_encode($config, JSON_PRETTY_PRINT);
		if($newJsonData === false){
			self::errorLog("Error: Could not encode the data in JSON format.");
			return false;
		}
		if(file_put_contents($fileConfigPath, $newJsonData) === false){
			self::errorLog("Error: Could not write to the configuration file.");
			return false;
		}
		return true;
	}

	private static function updateFilePHP(){
		self::method(__METHOD__);
		if(self::$environments['production'] !== self::$environment && self::loadUpdateVarsPHP()){
			$filePath  = self::$pathDebugging.self::FILE_PHP_UPDATE_VARS;
			$searchReplace = [
				'__DEBUGGING_PATHFILE__' => self::getPath().self::FILE_JSON_VARLIST,
				'__DEBUGGING_SELFENVIRONMENT__' => self::$environment,
				'__DEBUGGING_ENVIRONMENTS_DEBUG__' => self::$environments['debug'],
				'__DEBUGGING_ENVIRONMENTS_DEVELOPMENT__' => self::$environments['development'],
				'__DEBUGGING_ENVIRONMENTS_PRODUCTION__' => self::$environments['production'],
				'__DEBUGGING_ENVIRONMENTS_TESTING__' => self::$environments['testing']
			];
			return self::replaceFilePHP(array_keys($searchReplace), array_values($searchReplace),$filePath);
		}
		return false;
	}

// PROTECTED

	protected static function defineDebug($environment){
		self::method(__METHOD__);
		self::$environments['debug']=$environment;
		self::init();
	}

	protected static function defineDevelopment($environment){
		self::method(__METHOD__);
		self::$environments['development']=$environment;
		self::init();
	}

	protected static function defineTesting($environment){
		self::method(__METHOD__);
		self::$environments['testing']=$environment;
		self::init();
	}

	protected static function method($method, $trace=null){
		if (self::$environment !== self::$environments['production'] && strpos(self::WORK_ENVIRONMENT_MODE, 'debug') !== false && strpos(self::WORK_ENVIRONMENT_MODE, 'method') !== false) {
			if ($trace === null) {
				$trace = debug_backtrace();
			}
			if (is_array($trace) && isset($trace[0]) && isset($trace[0]['file']) && isset($trace[0]['line'])) {
				$file = $trace[0]['file'];
				$line = $trace[0]['line'];
			} else {
				$file = null;
				$line = null;
			}?>
			<div>
				<div><b><?php echo "$method "; ?></b></div><?php
				if($file!==null && $line!==null){?>
				<div style="padding-left:30px;"><b>Line: </b><?php echo $line; ?> <b>File: </b><?php echo $file; ?></div><?php
				}?>
				<div style="padding-left:30px;"><b>Enviroment: </b><?php echo self::$environment; ?></div>
			</div><br><?php
		}
	}

	protected static function setError($message, $environment, $trace=null){
		self::method(__METHOD__);
		if ($trace === null) { $trace = debug_backtrace(); }
		self::init();
		$time= date('Y-m-d H:i:s');
		$file = $trace[0]['file'];
		$line = $trace[0]['line'];
		$path = self::getPath($environment).self::FILE_TXT_ERROR_LOG;
		error_log("$environment" . PHP_EOL, 3, $path);
		error_log("	Time:		 $time" . PHP_EOL, 3, $path);
		error_log("	File:		 $file" . PHP_EOL, 3, $path);
		error_log("	Line:		 $line" . PHP_EOL, 3, $path);
		error_log("	$message" . PHP_EOL, 3, $path);
		error_log("---------------------------------------------------------------------" . PHP_EOL, 3, $path);
	}

	protected static function setMessage($message, $environment = null, $trace){
		self::method(__METHOD__);
		self::init();
		$file = '';
		$line = '';
		$path = '';
		if (is_array($trace) && isset($trace[0]) && isset($trace[0]['file']) && isset($trace[0]['line'])) {
			$path = $trace[0]['file'];
			$line = $trace[0]['line'];
			$file = self::getFileName($path);
		}
		if($environment === null){
			$environment = self::$environments['debug'];
		}
		if((self::$environment === self::$environments['debug'] || self::$environment === $environment) && self::$environments['production'] !== $environment){
			$message = self::convertToHtml($message);
			$fileMessagePath  = self::$pathMain.self::FILE_JS_MESSAGE;
			$searchReplace = [
				'__DEBUGGING_MESSAGE_ENVIRONMENT__'	  => $environment,
				'__DEBUGGING_MESSAGE_FILE__' => $file,
				'__DEBUGGING_MESSAGE_LINE__' => $line,
				'__DEBUGGING_MESSAGE_MESSAGE__'=> $message,
				'__DEBUGGING_MESSAGE_PATH__' => self::checkPath($path),
			];
			return self::echoReplaceFileJS(array_keys($searchReplace), array_values($searchReplace),$fileMessagePath);
		}
		return false;
	}

	protected static function setRun(callable $function, $environment = null, $trace=null){
		self::method(__METHOD__);
		if($environment === null){
			$environment = self::$environments['debug'];
		}
		self::init();
		if((self::$environment === self::$environments['debug'] || self::$environment === $environment) && self::$environments['production'] !== $environment){
			try {
				$function();
			} catch (Exception $e) {
				self::errorLog("Error while executing the function: " . $e->getMessage());
			}
		}
	}

	protected static function setSave($content, $file, $environment = null, $trace=null){
		self::method(__METHOD__);
		if($environment === null){
			$environment = self::$environments['debug'];
		}
		self::init();
		if((self::$environment === self::$environments['debug'] || self::$environment === $environment) && self::existsFile($environment) && self::$environments['production'] !== $environment){
			$filePath = self::$pathDebugging . $file;

			try {
				$fileHandle = fopen($filePath, 'w');
				if($fileHandle){
					fwrite($fileHandle, is_array($content) || is_object($content) ? print_r($content, true) : $content);
					fclose($fileHandle);
				} else {
					throw new Exception("Could not open the file for writing");
				}
			} catch (Exception $e) {
				self::errorLog("Error writing to file: " . $filePath . ". " . $e->getMessage());
			}
		}
	}

	protected static function setVariable($name, $value, $trace){
		self::method(__METHOD__);
		$file = '';
		$line = '';
		$path = '';
		if (is_array($trace) && isset($trace[0]) && isset($trace[0]['file']) && isset($trace[0]['line'])) {
			$path = $trace[0]['file'];
			$line = $trace[0]['line'];
			$file = self::getFileName($path);
		}
		self::init();
		$type = gettype($value);
		self::$variableList[] = [
			'name'  => $name,
			'value' => $value,
			'type'  => $type,
			'file'  => $file,
			'line'  => $line,
			'path'  => self::checkPath($path)
		];
		if (self::$environment !== self::$environments['production'] && self::existsFile() ) {
			$pathFile = self::getPath() . self::FILE_JSON_VARLIST;
			self::saveDataFileJSON($pathFile, self::$variableList);
			self::updateFilePHP();
		}
		return $value;
	}

// PUBLIC

	public static function eco($message, $details = false) {
		if( self::$environment !== self::$environments['production'] ) {
			$file = $line = '';
			if ($details) {
				$trace = debug_backtrace();
				if (is_array($trace) && isset($trace[0]['file']) && isset($trace[0]['line'])) {
					$file = basename($trace[0]['file']);
					$line = $trace[0]['line'];
				}
			}
			$lineBreak = (php_sapi_name() === 'cli') ? PHP_EOL : '<br>';
			if (is_scalar($message)) {
				echo ($details ? "File: $file  Line: $line  message: " : '') . $message .$lineBreak;
			} else {
				echo ($details ? "File: $file  Line: $line  message: " : '');
				var_dump($message);
				echo $lineBreak;
			}
		}
	}

	public static function exceptionErrorHandler($severity, $message, $file, $line){
		self::method(__METHOD__);
		self::init();
		if(!(error_reporting() & $severity)){
			return;
		}
		switch ($severity) {
			case E_USER_ERROR:
			case E_ERROR:
			case E_COMPILE_ERROR:
			case E_CORE_ERROR:
			case E_RECOVERABLE_ERROR:
				$title = 'FATAL ERROR';
				break;
			case E_USER_WARNING:
			case E_WARNING:
			case E_COMPILE_WARNING:
			case E_CORE_WARNING:
				$title = 'WARNING';
				break;
			case E_USER_NOTICE:
			case E_NOTICE:
			case E_STRICT:
				$title = 'NOTICE';
				break;
			case E_DEPRECATED:
			case E_USER_DEPRECATED:
				$title = 'DEPRECATED';
				break;
			default:
				$title = 'UNKNOWN ERROR';
				break;
		}
		if(self::$environments['production'] !== self::$environment){
			$fileErrorPath  = self::$pathMain.self::FILE_JS_ERROR;
			$searchReplace = [
				'__DEBUGGING_ERROR_CODE__' => $severity,
				'__DEBUGGING_ERROR_FILE__' => self::getFileName(self::checkPath($file)),
				'__DEBUGGING_ERROR_LINE__' => $line,
				'__DEBUGGING_ERROR_MESSAGE__'=> $message,
				'__DEBUGGING_ERROR_PATH__' => self::checkPath($file),
				'__DEBUGGING_ERROR_TITLE__' => $title
			];
			return self::echoReplaceFileJS(array_keys($searchReplace), array_values($searchReplace), $fileErrorPath);
		}
		self::errorLog("[$title] $message in $file on line $line");
		if(in_array($severity, [E_USER_ERROR, E_ERROR, E_COMPILE_ERROR, E_CORE_ERROR, E_RECOVERABLE_ERROR])){
			throw new ErrorException($message, 0, $severity, $file, $line);
		}
	}

	public static function exceptionHandler($exception){
		self::method(__METHOD__);
		self::init();
		if(self::$environments['production'] !== self::$environment){ 
			$severity = $exception instanceof ErrorException ? $exception->getSeverity() : E_ERROR;
			$fileErrorPath  = self::$pathMain.self::FILE_JS_ERROR;
			$title = 'GENERATED ERROR';
			$searchReplace = [
				'__DEBUGGING_ERROR_CODE__' => $severity,
				'__DEBUGGING_ERROR_FILE__' => self::getFileName(self::checkPath($exception->getFile())),
				'__DEBUGGING_ERROR_LINE__' => $exception->getLine(),
				'__DEBUGGING_ERROR_MESSAGE__'=> $exception->getMessage(),
				'__DEBUGGING_ERROR_PATH__' => self::checkPath($exception->getFile()),
				'__DEBUGGING_ERROR_TITLE__' => $title
			];
			return self::echoReplaceFileJS(array_keys($searchReplace), array_values($searchReplace), $fileErrorPath);
		}
		self::errorLog($title . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
	}

	public static function getEnvironment(){
		self::method(__METHOD__);
		self::init();
		return self::$environment;
	}

	public static function getEnvironments($includeDebug=false){
		self::method(__METHOD__);
		self::init();
		$environments = [
			'development' => self::$environments['development'],
			'testing'	 => self::$environments['testing'],
			'production'  => self::$environments['production']
		];
		if($includeDebug){
			$environments['debug'] = self::$environments['debug'];
		}
		return $environments;
	}

	public static function setEnvironments($development,  $testing, $production){
		self::method(__METHOD__);
		self::$environments['development'] = $development;
		self::$environments['production']  = $production;
		self::$environments['testing']	 = $testing;
		self::init();
	}

	public static function shutdownHandler(){
		self::method(__METHOD__);
		self::init();
		$error = error_get_last();
		if($error && ($error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR))){
			self::exceptionErrorHandler($error['type'], $error['message'], $error['file'], $error['line']);
		}
	}

	public static function start(...$args){
		self::method(__METHOD__);
		$inputName = self::INPUT_ENVIRONMENT_NAME;
		if (isset($_POST[$inputName])) {
			$environment = $_POST[$inputName];
			self::$environment = self::isCorrectEnvironment($environment);
		}
		else{
			switch (func_num_args()){
				case 1:
					self::$environment = self::isCorrectEnvironment($args[0]);
				break;
				case 2:
					self::$environment = self::isCorrectEnvironment(self::getVariableValue($args[0], $args[1]));
				break;
				case 0:
				default:
					self::$environment = self::isCorrectEnvironment();
				break;
			}
		}
		self::$variableList = array();
		self::setTitle();
		self::$correctStart = self::correctStart();
	}

}

// DEBUG

class DEBUG extends EnvDebuggerPHP{
	private static $environment;
	private static $environments;

	private static function init(){
		parent::method(__METHOD__);
		self::$environments = parent::getEnvironments(true);
		self::$environment = self::$environments['debug'];
		if(!parent::$correctStart){
			parent::start(); 
		}
	}

	public static function error($message){
		parent::method(__METHOD__);
		self::init();	
		$trace = debug_backtrace();
		parent::setError($message, self::$environment, $trace);
	}

	public static function msg($message){
		parent::method(__METHOD__);
		self::init();	
		$trace = debug_backtrace();
		parent::setMessage($message, self::$environment, $trace);
	}

	public static function run(callable $function){
		parent::method(__METHOD__);
		self::init();	
		$trace = debug_backtrace();
		parent::setRun($function, self::$environment, $trace);
	}

	public static function save($content, $file){
		parent::method(__METHOD__);
		self::init();
		$trace = debug_backtrace();
		parent::setSave($content, $file, self::$environment, $trace);
	}

}

// DEV

class DEV extends EnvDebuggerPHP{
	private static $environment;
	private static $environments;
	
	private static function init(){
		parent::method(__METHOD__);
		self::$environments = parent::getEnvironments();
		self::$environment = self::$environments['development'];
		if(!parent::$correctStart){
			parent::start(); 
		}
	}

	public static function error($message){
		parent::method(__METHOD__);
		self::init();
		$trace = debug_backtrace();
		parent::setError($message, self::$environment, $trace);
	}

	public static function msg($message){
		parent::method(__METHOD__);
		self::init();	
		$trace = debug_backtrace();
		parent::setMessage($message, self::$environment, $trace);
	}

	public static function run(callable $function){
		parent::method(__METHOD__);
		self::init();
		$trace = debug_backtrace();
		parent::setRun($function, self::$environment, $trace);
	}

	public static function save($content, $file){
		parent::method(__METHOD__);
		self::init();
		$trace = debug_backtrace();
		parent::setSave($content, $file, self::$environment, $trace);
	}

	public static function set($environment){
		parent::method(__METHOD__);
		parent::defineDevelopment($environment);
		self::init();
	}
}

// TEST

class TEST extends EnvDebuggerPHP{
	private static $environment;
	private static $environments;

	private static function init(){
		parent::method(__METHOD__);
		self::$environments = parent::getEnvironments();
		self::$environment = self::$environments['testing'];
		if(!parent::$correctStart){
			parent::start(); 
		}
	}

	public static function error($message){
		parent::method(__METHOD__);
		self::init();
		$trace = debug_backtrace();
		parent::setError($message, self::$environment, $trace);
	}

	public static function msg($message){
		parent::method(__METHOD__);
		self::init();
		$trace = debug_backtrace();
		parent::setMessage($message, self::$environment, $trace);
	}

	public static function run(callable $function){
		parent::method(__METHOD__);
		self::init();
		$trace = debug_backtrace();
		parent::setRun($function, self::$environment, $trace);
	}

	public static function save($content, $file){
		parent::method(__METHOD__);
		self::init();
		$trace = debug_backtrace();
		parent::setSave($content, $file, self::$environment, $trace);
	}

	public static function set($environment){
		parent::method(__METHOD__);
		parent::defineTesting($environment);
		self::init();
	}
}

class VARS extends EnvDebuggerPHP{
	private static $variableList=array();

	private static function init(){
	}

	private static function getVar($name) {
		self::method(__METHOD__);
		self::init();
		if (isset(self::$variableList[$name])) {
			return self::$variableList[$name];
		}
		return null;
	}

	private static function setVar($name, $value, $trace) {
		self::method(__METHOD__);
		self::init();
		self::$variableList[$name] = $value;
		parent::setVariable($name, $value, $trace);
		return $value;
	}

	public static function inc($name){
		parent::method(__METHOD__);
		self::init();
		$value = self::toInt($name) + 1;
		$trace = debug_backtrace();
		return self::setVar($name, $value, $trace);
	}

	public static function set($name, $value=null){
		parent::method(__METHOD__);
		self::init();
		$trace = debug_backtrace();
		return self::setVar($name, $value, $trace);
	}

	public static function toArray($name) {
		parent::method(__METHOD__); 
		self::init(); 
		$value = self::getVar($name); 
		if (is_null($value)) { 
			$trace = debug_backtrace(); 
			$value = self::setVar($name, array(), $trace); 
		} 
		return (array) $value;
	}

	public static function toBool($name) {
		parent::method(__METHOD__); 
		self::init(); 
		$value = self::getVar($name); 
		if (is_null($value)) { 
			$trace = debug_backtrace(); 
			$value = self::setVar($name, false, $trace); 
		} 
		return (bool)$value;
	}

	public static function toFloat($name) {
		parent::method(__METHOD__); 
		self::init(); 
		$value = self::getVar($name);
		if (is_null($value)) { 
			$trace = debug_backtrace(); 
			$value = self::setVar($name, 0, $trace); 
		} 
		return (float)$value;
	}

	public static function toInt($name){
		parent::method(__METHOD__); 
		self::init(); 
		$value = self::getVar($name);
		if (is_null($value)) { 
			$trace = debug_backtrace(); 
			$value = self::setVar($name, 0, $trace); 
		} 
		return (int)$value;
	}

	public static function toObject($name) {
		parent::method(__METHOD__); 
		self::init(); 
		$value = self::getVar($name); 
		if (is_null($value)) { 
			$trace = debug_backtrace(); 
			$value = self::setVar($name, new stdClass(), $trace); 
		} 
		return (object)$value;
	}

	public static function toString($name) {
		parent::method(__METHOD__); 
		self::init(); 
		$value = self::getVar($name);
		if (is_null($value)) { 
			$trace = debug_backtrace(); 
			$value = self::setVar($name, '', $trace); 
		} 
		return (string)$value;
	}
	
}