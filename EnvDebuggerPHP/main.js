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
 
/***********************************  MAIN **********************************/

class TEnvDebuggerPHP {
	
	constructor(value) {
		this.environment  = '';
		this.environments = {};
		this.settings	 = {};
		
		this.CONTAINER_ID	   = 'DEBUGGING-CONTAINER';
		this.ENVIRONMENTS_ID	= 'DEBUGGING-ENVIRONMENTS';
		this.ERRORS_BODY_ID	 = 'DEBUGGING-ERRORS-BODY'; 
		this.ERROR_ID		   = 'DEBUGGING-ERROR-'; 
		this.ERRORS_ID		  = 'DEBUGGING-ERRORS'; 
		this.ERRORS_TABLE_ID	= 'DEBUGGING-ERRORS-TABLE';
		this.MESSAGES_BODY_ID   = 'DEBUGGING-MESSAGES-BODY'; 
		this.MESSAGE_ID		 = 'DEBUGGING-MESSAGE-'; 
		this.MESSAGES_ID		= 'DEBUGGING-MESSAGES'; 
		this.MESSAGES_TABLE_ID  = 'DEBUGGING-MESSAGES-TABLE';
		this.VARIABLES_BODY_ID  = 'DEBUGGING-VARIABLES-BODY';
		this.VARIABLES_ID	   = 'DEBUGGING-VARIABLES';
		this.VARIABLES_TABLE_ID = 'DEBUGGING-VARIABLES-TABLE';
		this.TITLE_ID		   = 'DEBUGGING-TITLE';
		
		this.ENVIRONMENTS_TITLE = value.ENVIRONMENTS_TITLE;
		this.ENVIRONMENT_NAME   = value.ENVIRONMENT_NAME;
		this.ERRORS_TITLE		= value.ERRORS_TITLE;
		this.MESSAGES_TITLE		= value.MESSAGES_TITLE;
		this.PATH_FILE_CONFIG   = value.PATH_FILE_CONFIG;
		this.PATH_FILE_HELP	 = value.PATH_FILE_HELP;
		this.PATH_FILE_VARS	 = value.PATH_FILE_VARS;
		this.VARIABLES_TITLE	= value.VARIABLES_TITLE;
		this.TITLE_TITLE		= value.TITLE_TITLE;

		this.screenWidth = 800;
		
		this.MAX_BAR_HEIGHT = 270; 
		this.MIN_BAR_HEIGHT = 100; 
		this.MAX_BAR_WIDTH = 300; 
		this.MIN_BAR_WIDTH = 200; 

		this.DEFAULT_ERROR_COLORS = {
			"color-title": "rgba(0, 0, 0, 1)"
		};

		this.DEFAULT_ENVIRONMENT_COLORS = {
			"background": "#ffffff",
			"border": "rgba(255, 0, 0, 1)",
			"color": "#000000",
			"message-background": "#ffffff",
			"message-border": "rgba(255, 0, 0, 1)",
			"message-color": "#000000",
			"title-background": "rgba(255, 0, 0, 0.8)",
			"title-border": "rgba(255, 0, 0, 1)",
			"title-color": "#ffffff"
		};
		
		this.titleStyle = '';
		this.titleWidth = '';
	}

	adjustChildrenHeights(container, environments, errors, messages, variables, totalHeight = null) {
		const environmentsHeight = environments.offsetHeight;
		const availableHeight = totalHeight ? (environmentsHeight * 3) : (container.offsetHeight - environmentsHeight - 146);
		const childHeight = availableHeight / 3;
		errors.style.height = childHeight + "px";
		messages.style.height = childHeight + "px";
		variables.style.height = childHeight + "px";
	}

	adjustChildrenMargins(container, left, right) {
		container.style.marginLeft = left;
		container.style.marginRight = right;
	}

	adjustContainerDimensions(container, top, right, bottom, width, height = 'fit-content', overflowY = 'hidden') {
		container.style.position = "fixed";
		container.style.top = top;
		container.style.right = right;
		container.style.bottom = bottom;
		container.style.width = width;
		container.style.height = height;
		container.style.overflowY = overflowY;
	}

	adjustContainerPosition() {
		const errorsBodyContainer = document.getElementById(this.ERRORS_BODY_ID);
		const mainContainer = document.getElementById(this.CONTAINER_ID);
		const messagesBodyContainer = document.getElementById(this.MESSAGES_BODY_ID);
		const variablesBodyContainer = document.getElementById(this.VARIABLES_BODY_ID);
		let environmentsContainer = document.getElementById(this.ENVIRONMENTS_ID);
		let errorsContainer = document.getElementById(this.ERRORS_ID);
		let messagesContainer = document.getElementById(this.MESSAGES_ID);
		let variablesContainer = document.getElementById(this.VARIABLES_ID);
		if (!mainContainer || !environmentsContainer || !messagesBodyContainer || !variablesBodyContainer || !errorsBodyContainer) return;
		environmentsContainer.style.height = 'auto';
		if (window.innerWidth > this.screenWidth) {
			this.applyStylesForWideScreens(mainContainer);
			const width = this.getContainerWidth();
			mainContainer.style.width = width + "px";
			this.adjustChildrenMargins(environmentsContainer, "0", "0");
			this.adjustChildrenMargins(errorsContainer, "0", "0");
			this.adjustChildrenMargins(messagesContainer, "0", "0");
			this.adjustChildrenMargins(variablesContainer, "0", "0");
			this.adjustChildrenHeights(mainContainer, environmentsContainer, errorsBodyContainer, messagesBodyContainer, variablesBodyContainer);
		} else {
			this.adjustChildrenMargins(environmentsContainer, "6px", "6px");
			this.adjustChildrenMargins(errorsContainer, "6px", "6px");
			this.adjustChildrenMargins(messagesContainer, "6px", "6px");
			this.adjustChildrenMargins(variablesContainer, "6px", "6px");
			this.resetStylesForNarrowScreens(mainContainer);
			this.adjustChildrenHeights(mainContainer, environmentsContainer, errorsBodyContainer, messagesBodyContainer, variablesBodyContainer, this.MAX_BAR_HEIGHT);
		}
	}
	
	adjustMargins() {
		const title = document.getElementById(this.TITLE_ID);
		if (title) {
			const height = title.offsetHeight;
			const allElements = document.querySelectorAll('body > *');
			allElements.forEach(element => {
				if (element.id !== this.TITLE_ID && element.id !== this.CONTAINER_ID) {
					const marginTop = window.getComputedStyle(element).marginTop;
					element.style.marginTop = (parseInt(marginTop) + height) + 'px';
				}
			});
		}
	}
	
	adjustTitle(click=false) {
		const title = document.getElementById(this.TITLE_ID);
		const container = document.getElementById(this.CONTAINER_ID);
		if (title && container) {
			const currentWidth = title.style.width;
			const containerWidth = container.offsetWidth + "px";
			if (window.innerWidth > this.screenWidth) {
				if(click){									
					const style = currentWidth === containerWidth?'max':'min';
					const width = currentWidth === containerWidth?"100%":containerWidth;
					this.titleStyle = style;					
					this.titleWidth = width;
				}
				this.titleStyles(this.titleStyle,title,container,this.titleWidth);								
			}else{
				this.titleStyles('bottom',title, container,'auto');
			}			
		}
	}

	applyCustomScrollBar() {
		const style = document.createElement('style');
		style.type = 'text/css';
		const colors = this.getEnvironmentColors(this.environment);
		style.innerHTML = `
			::-webkit-scrollbar {
				width: 8px;
				height: 8px;
				background: transparent;
			}

			::-webkit-scrollbar-button {
				background-color: ${colors["border"]};
				border: none;
			}

			::-webkit-scrollbar-track {
				background: transparent;
				border: 1px solid ${colors["message-border"]};
			}

			::-webkit-scrollbar-thumb {
				background-color: ${colors["border"]};
				border-radius: 10px;
				border: thin solid ${colors["border"]};
			}

			::-webkit-scrollbar-thumb:hover {
				background-color: ${colors["border"]};
			}

			::-webkit-scrollbar-thumb:window-inactive {
				background-color: ${colors["border"]};
			}

			* {
				scrollbar-width: thin;
				scrollbar-color: ${colors["border"]} transparent;
			}
		`;
		document.head.appendChild(style);
	}

	applyStylesForWideScreens(container) {
		this.adjustContainerDimensions(container, "50px", "6px", "6px", this.getContainerWidth() + "px");
	}

	clearVariableTable() {
		const tableBody = document.querySelector(`#${this.VARIABLES_TABLE_ID} tbody`);
		if (tableBody) {
			while (tableBody.firstChild) {
				tableBody.removeChild(tableBody.firstChild);
			}
		}
	}

	completeUrl() {
		return window.location.href;
	}

	convertTo(value, type) {
		let convertedValue;
		switch (type.toLowerCase()) {
			case 'string':
				convertedValue = String(value);
				break;
			case 'integer':
			case 'int':
			case 'number':
			case 'float':
				convertedValue = Number(value);
				if (isNaN(convertedValue)) {
					return "NaN";
				}
				break;
			case 'boolean':
			case 'bool':
				convertedValue = Boolean(value === 'true' || value === true || value == 1);
				convertedValue = convertedValue ? 'true' : 'false';
				break;
			case 'array':
				try {
					convertedValue = Array.isArray(value) ? value : JSON.parse(value);
					convertedValue = JSON.stringify(convertedValue);
				} catch (e) {
					return "[]";
				}
				break;
			case 'object':
				try {
					convertedValue = typeof value === 'object' ? value : JSON.parse(value);
					convertedValue = JSON.stringify(convertedValue);
				} catch (e) {
					return "{}";
				}
				break;
			default:
				return `${value}`;
		}
		return String(convertedValue);
	}

	createBars() {
		const container = document.createElement("div");
		container.id = this.CONTAINER_ID;
		container.style.position = "fixed";
		container.style.top = "50px";
		container.style.right = "6px";
		container.style.bottom = "6px";
		container.style.width = "20%";
		container.style.minWidth = this.MIN_BAR_WIDTH;
		container.style.minHeight = this.MIN_BAR_HEIGHT;
		container.style.backgroundColor = "transparent";
		container.style.border = "none";
		container.style.padding = "0";
		container.style.zIndex = "2147483647";
		container.style.fontSize = "12px";
		container.style.fontFamily = "Arial, sans-serif";
		container.style.overflow = "hidden";
		container.style.display = "flex";
		container.style.flexDirection = "column";
		container.style.gap = "10px";
		const environmentsContainer = this.createSection(this.ENVIRONMENTS_ID, "Environments");
		const variablesContainer = this.createSection(this.VARIABLES_ID, "Variables");
		const messagesContainer = this.createSection(this.MESSAGES_ID, "Messages");
		const errorsContainer = this.createSection(this.ERRORS_ID, "Errors");
		container.appendChild(environmentsContainer);
		container.appendChild(variablesContainer);
		container.appendChild(messagesContainer);
		container.appendChild(errorsContainer);
		document.body.appendChild(container);
	}

	createSection(id, title) {
		const container = document.createElement("div");
		container.id = id;
		container.style.marginBottom = "2px";
		container.style.marginTop = "2px";
		const colors = this.getEnvironmentColors(this.environment);
		const sectionTitle = this.createSectionTitle(id, title, colors);
		const sectionBody = this.createSectionBody(id, colors);
		container.appendChild(sectionTitle);
		container.appendChild(sectionBody);
		container.style.minWidth = this.MIN_BAR_WIDTH;
		switch (id) {
			case this.ENVIRONMENTS_ID:
				this.createSectionEnvironment(sectionBody);
				break;
			case this.ERRORS_ID:
				this.createSectionTable(sectionBody, colors, this.ERRORS_TABLE_ID, ["Error", "Message", "Line", "File", "Path"]);
				break;
			case this.MESSAGES_ID:
				this.createSectionTable(sectionBody, colors, this.MESSAGES_TABLE_ID, ["Message", "Line", "File", "Path"]);
				break;
			case this.VARIABLES_ID:
				this.createSectionTable(sectionBody, colors, this.VARIABLES_TABLE_ID, ["Name", "Value", "Type", "Line", "File", "Path"]);
				break;
		}
		setTimeout(() => {
			this.createSectionCloseButton(`${id}-TITLE`, `${id}-BODY`, colors, id==this.ENVIRONMENTS_ID?'block':'none',id==this.ENVIRONMENTS_ID);
		}, 0);
		return container;
	}

	createSectionBody(id, colors) {
		const container = document.createElement("div");
		container.id = `${id}-BODY`;
		container.style.fontWeight = "bold";
		container.style.fontSize = "12px";
		container.style.textAlign = "start";
		container.style.color = colors["body-color"];
		container.style.backgroundColor = colors["body-background"];
		container.style.border = `1px solid ${colors["border"]}`;
		container.style.padding = "0";
		container.style.marginBottom = "0";
		container.style.marginTop = "0";
		container.style.boxShadow = "inset 0 0 3px rgba(0, 0, 0, 0.1)";
		container.style.minHeight = 'fit-content';
		container.style.minWidth  = 'auto';		
		container.style.overflowX = id === this.ERRORS_ID || id === this.MESSAGES_ID || id === this.VARIABLES_ID ? 'auto' : 'hidden';
		container.style.overflowY = id === this.ERRORS_ID || id === this.MESSAGES_ID || id === this.VARIABLES_ID ? 'auto' : 'hidden';
		container.style.height = id === this.ERRORS_ID || id === this.MESSAGES_ID || id === this.VARIABLES_ID ? '150px' : 'auto';
		if (id === this.ENVIRONMENTS_ID) {
			container.style.paddingBottom = "8px";
		}
		return container;
	}

	createSectionCloseButton(idTitle, idBody, colors, display, active) {
		const button = document.createElement("button");
		const body = document.getElementById(idBody);
		const title = document.getElementById(idTitle);
		button.textContent = active?"-":"+";
		button.style.fontSize = "18px";
		button.style.height = "20px";
		button.style.width = "20px";
		button.style.border = `1px solid ${colors["border"]}`;
		button.style.backgroundColor = colors["title-background"];
		button.style.cursor = "pointer";
		button.style.color = colors["border"];
		button.style.position = "absolute";
		button.style.right = "6px";
		button.style.top = "50%";
		button.style.transform = "translateY(-50%)";
		button.style.display = "flex";
		button.style.alignItems = "center";
		button.style.justifyContent = "center";
		if (title) {
			title.appendChild(button);
			if (body) {
				body.style.display = display;
				button.addEventListener("click", function () {
					if (body.style.display === "none") {
						body.style.display = "block";
						button.textContent = "-";
					} else {
						body.style.display = "none";
						button.textContent = "+";
					}
				});
			}
		}
	}

	createSectionEnvironment(section) {
		const form = document.createElement("form");
		form.method = "POST";
		form.action = window.location.href;
		const input = document.createElement("input");
		input.type = "hidden";
		input.name = this.ENVIRONMENT_NAME;
		form.appendChild(input);
		section.appendChild(form);
		let environments = {};
		if(this.environment==='debug'){
			environments['debug'] = this.environments['debug'];
		}else{
			environments['development'] = this.environments['development'];
			environments['testing']	 = this.environments['testing'];
		}
		environments['production'] = this.environments['production'];
		for (const [key, value] of Object.entries(environments)) {
			let colors = this.getEnvironmentColors(value);
			const item = document.createElement("div");
			item.style.display = "flex";
			item.style.alignItems = "center";
			item.style.padding = "2px 0 2px 12px";
			item.style.cursor = "pointer";
			item.addEventListener("click", function () {
				input.value = value;
				if(key == 'production'){
					alert('EnvDebuggerPHP does not work in production environment.');
				}
				form.submit();
			});
			const led = document.createElement("div");
			led.style.width = "12px";
			led.style.height = "12px";
			led.style.backgroundColor = colors["background"];
			led.style.borderRadius = "50%";
			led.style.marginRight = "10px";
			led.style.boxShadow = `0 0 5px ${colors["border"]}`;
			item.appendChild(led);
			item.appendChild(document.createTextNode(`${value}`));
			section.appendChild(item);
		}
	}

	createSectionTable(section, colors, tableId, columns) {
		const table = document.createElement("table");
		table.id = tableId;
		table.style.marginBottom = "2px";
		table.style.marginTop = "2px";
		table.style.backgroundColor = colors["body-background"];
		table.style.borderBottom = `1px solid ${colors["body-border"]}`;
		table.style.color = colors["body-color"];
		table.style.padding = "4px 8px";
		table.style.fontSize = "12px";
		table.style.fontFamily = "Arial, sans-serif";
		table.style.textAlign = "center";
		table.style.minHeight = section.offsetHeight + 'px';
		table.style.minWidth = section.offsetWidth + 'px';
		section.appendChild(table);
		const header = table.createTHead();
		const headerRow = header.insertRow();
		columns.forEach(column => {
			const th = document.createElement("th");
			th.textContent = column;
			th.style.backgroundColor = colors["title-background"];
			th.style.border = `1px solid ${colors["title-border"]}`;
			th.style.color = colors["title-color"];
			th.style.padding = "2px 12px";
			headerRow.appendChild(th);
		});
		const tbody = document.createElement("tbody");
		tbody.id = `${tableId}-BODY`;
		table.appendChild(tbody);
	}

	createSectionTitle(id, title, colors) {
		const container = document.createElement("div");
		container.id = `${id}-TITLE`;
		container.style.fontWeight = "bold";
		container.style.fontSize = "14px";
		container.style.color = colors["title-color"];
		container.style.backgroundColor = colors["title-background"];
		container.style.border = `1px solid ${colors["border"]}`;
		container.style.padding = "4px";
		container.style.marginBottom = "2px";
		container.style.marginTop = "0";
		container.style.boxShadow = `inset 0 0 3px rgba(0, 0, 0, 0.1)`;
		container.style.display = "flex";
		container.style.justifyContent = "center";
		container.style.position = "relative";
		const titleElement = document.createElement("span");
		titleElement.innerText = title;
		const titleContainer = document.createElement("div");
		titleContainer.style.flexGrow = "1";
		titleContainer.style.textAlign = "center";
		titleContainer.appendChild(titleElement);
		container.appendChild(titleContainer);
		return container;
	}

	createTableColumn(row, value, style = null) {
		const colors = this.getEnvironmentColors(this.environment);
		const cell = document.createElement("td");
		cell.textContent = value;		
		if (style && style.color) {
			cell.style.color = style.color;
		}
		if (style && style.backgroundColor) {
			cell.style.backgroundColor = style.backgroundColor;
		}
		cell.style.border = (style && style.border)? style.border:`1px solid ${colors["message-border"]}`;
		cell.style.textAlign = (style && style.textAlign)? style.textAlign:'center';
		cell.style.padding = "2px 6px";
		cell.style.whiteSpace = "nowrap";
		cell.style.width = "auto";
		cell.style.maxWidth = "none"; 
		row.appendChild(cell);
	}
	
	createTitle() {
		const colors = this.getEnvironmentColors(this.environment);
		const containerWidth = this.getContainerWidth();
		const id = this.TITLE_ID;
		if (!document.getElementById(this.TITLE_ID)) {
			const div = document.createElement("div");
			this.titleStyle = 'min';
			this.titleWidth = containerWidth<this.MIN_BAR_WIDTH?this.MIN_BAR_WIDTH:containerWidth+"px";
			div.id = id;
			div.style.position = "fixed";
			div.style.top = "0";
			div.style.left = "auto";
			div.style.right = "6px";
			div.style.width = containerWidth<this.MIN_BAR_WIDTH?this.MIN_BAR_WIDTH:containerWidth+"px";
			div.style.backgroundColor = colors["background"];
			div.style.border = `1px solid ${colors["border"]}`;
			div.style.color = colors["color"];
			div.style.textAlign = "center";
			div.style.padding = "8px";
			div.style.zIndex = "2147483647";
			div.style.fontSize = "16px";
			div.style.fontWeight = "bold";
			div.style.fontFamily = "Arial, sans-serif";
			div.style.overflowX = "hidden";
			div.style.overflowY = "hidden";
			div.style.maxHeight = "40px";
			div.style.transition = "width 0.3s ease-in-out, left 0.3s ease-in-out, right 0.3s ease-in-out";
			const leftLink = document.createElement("a");
			leftLink.href = "www.geocys.com/EnvDebuggerPHP";
			leftLink.id = `${id}-ALEFT`;
			leftLink.style.position = "absolute";
			leftLink.style.left = "10px";
			leftLink.style.color = colors["color"];
			leftLink.style.textDecoration = "none";
			leftLink.style.display = "flex";
			leftLink.style.alignItems = "center";
			const iconSvg = document.createElement("span");
			iconSvg.style.marginTop = "-3px";
			iconSvg.innerHTML = `
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="26px" height="26px">
					<ellipse cx="50" cy="60" rx="20" ry="25" fill="${colors["color"]}" stroke="${colors["color"]}" stroke-width="2" />
					<line x1="50" y1="35" x2="50" y2="85" stroke="${colors["color"]}" stroke-width="2" />
					<circle cx="40" cy="50" r="3" fill="${colors["color"]}" />
					<circle cx="40" cy="60" r="3" fill="${colors["color"]}" />
					<circle cx="40" cy="70" r="3" fill="${colors["color"]}" />
					<circle cx="60" cy="50" r="3" fill="${colors["color"]}" />
					<circle cx="60" cy="60" r="3" fill="${colors["color"]}" />
					<circle cx="60" cy="70" r="3" fill="${colors["color"]}" />
					<circle cx="50" cy="30" r="10" fill="${colors["color"]}" />
					<circle cx="46" cy="28" r="2" fill="${colors["color"]}" />
					<circle cx="54" cy="28" r="2" fill="${colors["color"]}" />
					<line x1="30" y1="55" x2="20" y2="45" stroke="${colors["color"]}" stroke-width="2" />
					<line x1="30" y1="65" x2="20" y2="75" stroke="${colors["color"]}" stroke-width="2" />
					<line x1="70" y1="55" x2="80" y2="45" stroke="${colors["color"]}" stroke-width="2" />
					<line x1="70" y1="65" x2="80" y2="75" stroke="${colors["color"]}" stroke-width="2" />
					<line x1="44" y1="22" x2="38" y2="15" stroke="${colors["color"]}" stroke-width="2" />
					<line x1="56" y1="22" x2="62" y2="15" stroke="${colors["color"]}" stroke-width="2" />
				</svg>`;
			const leftText = document.createElement("span");
			leftText.style.fontSize = "12px";
			leftText.textContent = "EnvDebuggerPHP";
			leftLink.appendChild(iconSvg);
			leftLink.appendChild(leftText);
			const rightLink = document.createElement("a");
			rightLink.id = `${id}-ARIGHT`;
			rightLink.href = this.PATH_FILE_HELP;
			rightLink.textContent = "help";
			rightLink.style.position = "absolute";
			rightLink.style.fontSize = "12px";
			rightLink.style.right = "50px";
			rightLink.style.marginTop = "3px";
			rightLink.style.color = colors["color"];
			rightLink.style.textDecoration = "none";
			rightLink.style.opacity = "0";
			const minimizeButton = document.createElement("button");
			minimizeButton.textContent = "−";
			minimizeButton.style.position = "absolute";
			minimizeButton.style.right = "10px";
			minimizeButton.style.fontSize = "12px";
			minimizeButton.style.backgroundColor = colors["background"];
			minimizeButton.style.color = colors["color"];
			minimizeButton.style.border = `1px solid ${colors["border"]}`;
			minimizeButton.style.cursor = "pointer";
			minimizeButton.addEventListener("click", () => {
				this.adjustTitle(true);
			});
			div.appendChild(leftLink);
			div.appendChild(rightLink);
			div.appendChild(minimizeButton);
			const titleText = document.createElement("span");
			titleText.id = `${id}-SPAN`;
			titleText.textContent = this.TITLE_TITLE;
			titleText.style.opacity = "0";
			titleText.style.marginRight = "32px";
			div.appendChild(titleText);
			document.body.insertAdjacentElement("afterbegin", div);
		}
	}

	getContainerWidth() {
		let width = parseInt(window.innerWidth * 0.10);
		if (window.innerWidth <= this.screenWidth) {
			width = this.MIN_BAR_WIDTH;
		} else if (width > this.MAX_BAR_WIDTH) {
			width = this.MAX_BAR_WIDTH;
		}
		return width;
	}

	getErrorColorsByCode(code) {
		const style = this.settings.style || 'default';
		if (this.settings.colors.error.hasOwnProperty(style)) {
			if (this.settings.colors.error[style].hasOwnProperty(code)) {
				return this.settings.colors.error[style][code];
			}
		}
		return this.settings.colors.environment['default'][code] || this.DEFAULT_ERROR_COLORS;
	}

	getEnvironmentColors(environment) {
		const style = this.settings.style || 'default';
		let key = this.getKeyByValue(this.environments, environment);
		if (this.settings.colors.environment.hasOwnProperty(style)) {
			if (this.settings.colors.environment[style].hasOwnProperty(key)) {
				return this.settings.colors.environment[style][key];
			}
		}
		return this.settings.colors.environment['default'][key] || this.DEFAULT_ENVIRONMENT_COLORS;
	}

	getFileUrl() {
		return `${window.location.origin}${window.location.pathname}`;
	}

	getKeyByValue(obj, value) {
		return Object.keys(obj).find(key => obj[key] === value);
	}
	
	async initialize() {
		try {
			await this.loadConfig();
			return true;
		} catch (error) {
			console.error('Error during initialization:', error);
			throw error;
		}
	}

	async loadConfig() {
		try {
			const response = await fetch(this.PATH_FILE_CONFIG);
			if (!response.ok) {
				throw new Error(`Error loading configuration: ${response.statusText}`);
			}
			const data = await response.json();
			this.environment  = data.environment;
			this.environments = data.environments;
			this.settings	 = data.settings;
		} catch (error) {
			console.error('There was a problem loading the configuration file:', error);
		}
	}
	
	loadVariable(variable) {
		const tableBody = document.querySelector(`#${this.VARIABLES_TABLE_ID} tbody`);
		if (tableBody) {
			const row = document.createElement("tr");
			this.createTableColumn(row, variable.name);
			this.createTableColumn(row, this.convertTo(variable.value, variable.type));
			this.createTableColumn(row, variable.type);
			this.createTableColumn(row, variable.line);
			this.createTableColumn(row, variable.file);
			this.createTableColumn(row, variable.path, {textAlign:'start'});
			tableBody.appendChild(row);
		}
	}

	resetStylesForNarrowScreens(container) {
		this.adjustContainerDimensions(container, "auto", "0", "6px", "100%", `${this.MAX_BAR_HEIGHT}px`, 'auto');
	}

	titleStyles(style, title, container, width) {
		if (title && container) {
			title.style.width = width;
			const links = title.querySelectorAll('a[id*="ARIGHT"]');
			const button = title.querySelector("button");
			const span = title.querySelector("span[id]");
			if (style === 'bottom') {
				title.style.top = "auto";
				title.style.left = "6px";
				title.style.right = "6px";
				title.style.bottom = (this.MAX_BAR_HEIGHT + 15) + "px";
				if (button) {
					button.disabled = true;
					button.style.opacity = "0";
				}
				links.forEach(link => link.style.opacity = "0");
				if (span) span.style.opacity = "0";
			} else {
				title.style.top = "0";
				title.style.left = "auto";
				title.style.right = "6px";
				title.style.bottom = "auto";
				if (button) {
					button.disabled = false;
					button.style.opacity = "1";
				}
				switch (style) {
					case 'max':
						button.textContent = "-";
						links.forEach(link => link.style.opacity = "1");
						if (span) span.style.opacity = "1";
						break;
					case 'min':
						button.textContent = "+";
						links.forEach(link => link.style.opacity = "0");
						if (span) span.style.opacity = "0";
						break;
				}
			}
		}
	}

	async updateVariableValues() {
		try {
			this.clearVariableTable();
			const response = await fetch(this.PATH_FILE_VARS);
			if (!response.ok) {
				throw new Error('Could not access the file');
			}
			const data = await response.json();
			if (data && Array.isArray(data) && data.length > 0) {
				data.forEach(variable => this.loadVariable(variable));
			}
		} catch (error) {
			console.error('Error fetching the data:', error);
		}
	}
}