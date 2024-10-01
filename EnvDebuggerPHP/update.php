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

$pathFile         = '__DEBUGGING_PATHFILE__';
$selfEnvironment  = '__DEBUGGING_SELFENVIRONMENT__';
$selfEnvironments = [
	'debug'			=> '__DEBUGGING_ENVIRONMENTS_DEBUG__',
	'development'	=> '__DEBUGGING_ENVIRONMENTS_DEVELOPMENT__',
	'production'	=> '__DEBUGGING_ENVIRONMENTS_PRODUCTION__',
	'testing'		=> '__DEBUGGING_ENVIRONMENTS_TESTING__'
];

if (file_exists($pathFile) && $selfEnvironments['production'] !== $selfEnvironment) {
	$vars = readDataFileJSON($pathFile);
	if (count($vars) > 0) {
		echo json_encode($vars);
	} else {
		echo json_encode([]);
	}
} else {
	echo json_encode(null);
}

function readDataFileJSON($path) {
	if (file_exists($path)) {
		$currentData = file_get_contents($path);
		return json_decode($currentData, true);
	} else {
		return [];
	}
}