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
  
/*********************************** MESSAGE **********************************/

document.addEventListener("DOMContentLoaded", function() {
	EnvDebuggerPHP.initialize().then(() => {
		const line	 = '__DEBUGGING_MESSAGE_LINE__'; 
		const file	 = '__DEBUGGING_MESSAGE_FILE__'; 
		const message  = '__DEBUGGING_MESSAGE_MESSAGE__'; 
		const path	 = '__DEBUGGING_MESSAGE_PATH__'; 
		const existing = document.querySelectorAll(`[id^='${EnvDebuggerPHP.MESSAGE_ID}']`);
		const id	   = EnvDebuggerPHP.MESSAGE_ID + (existing.length + 1);
		let colors	 = EnvDebuggerPHP.getEnvironmentColors(EnvDebuggerPHP.environment);		
		if (document.getElementById(EnvDebuggerPHP.MESSAGES_BODY_ID)) {
			const tableBody = document.querySelector(`#${EnvDebuggerPHP.MESSAGES_TABLE_ID} tbody`);			
			if (tableBody) {
				const row = document.createElement("tr");
				EnvDebuggerPHP.createTableColumn(row, message, {textAlign:'start'});
				EnvDebuggerPHP.createTableColumn(row, line);
				EnvDebuggerPHP.createTableColumn(row, file);
				EnvDebuggerPHP.createTableColumn(row, path, {textAlign:'start'});
				tableBody.appendChild(row);
			}
		}
	}).catch(error => {
		console.error("Error initializing EnvDebuggerPHP:", error);
	});
});

