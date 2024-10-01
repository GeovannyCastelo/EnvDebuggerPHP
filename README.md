# EnvDebuggerPHP

**EnvDebuggerPHP** is a powerful PHP library designed to streamline debugging and error handling across different environments like development, testing, and production. It helps you efficiently track variables and identify errors, adapting to each stage of your project's lifecycle.

## 🚀 Features

- 🎯 Custom error handling tailored to each environment.
- 🛠️ Detailed messages and logs for better error tracking.
- ⚙️ Compatible with JSON, XML, TXT, and PHP for easy configuration.

## 📦 Installation

1. **Download or Clone** the repository:
   ```bash
   git clone https://github.com/yourusername/EnvDebuggerPHP.git
   ```

2. **Include** the library in your PHP project:
   ```php
   require_once 'path/to/EnvDebuggerPHP.php';
   ```

## 🛠️ Quick Setup

Configure your environment with the `start` method at the beginning of your application (e.g., in `index.php`):

```php
EnvDebuggerPHP::start('development');  // Start in development environment
EnvDebuggerPHP::start('testing');      // Start in testing environment
EnvDebuggerPHP::start('production');   // Start in production environment
EnvDebuggerPHP::start();               // Default to production environment
EnvDebuggerPHP::start('debug');        // Start in debug mode
```

Alternatively, load your environment settings from a file:

```php
EnvDebuggerPHP::start('path/to/file.json', 'variableName');  // Load from a JSON file
```

## 📂 Environments

EnvDebuggerPHP manages environments through dedicated classes to handle errors and logs:

- **Development (`DEV::`)**: Handles errors during the development phase.
- **Testing (`TEST::`)**: Used during the testing phase before moving to production.
- **Production**: No class is associated with production to avoid exposing sensitive data.
- **Debug (`DEBUG::`)**: Combines development and testing for real-time debugging.

## 📜 Methods Overview

### DEBUG Class

- `DEBUG::error($message)`: Log an error to `debugging/debug/error_log.txt`.
   ```php
   DEBUG::error('Error found in debug');
   ```

- `DEBUG::msg($message)`: Display a message on the screen.
   ```php
   DEBUG::msg('Message visible only in debug mode');
   ```

- `DEBUG::run(callable $function)`: Execute a function only in debug mode.
   ```php
   DEBUG::run(function() { echo 'Function executed in debug mode'; });
   ```

- `DEBUG::save($content, $file)`: Save content to `debugging/debug/` in debug mode.
   ```php
   DEBUG::save('Hello, World', 'file.txt');
   ```

### DEV Class

- `DEV::error($message)`: Log an error to `debugging/dev/error_log.txt` in development.
   ```php
   DEV::error('Error found in development environment');
   ```

- `DEV::msg($message)`: Display a message with the file and line number in development.
   ```php
   DEV::msg('Message visible in development environment');
   ```

- `DEV::run(callable $function)`: Execute a function in the development environment.
   ```php
   DEV::run(function() { echo 'Function executed in development environment'; });
   ```

- `DEV::save($content, $file)`: Save content to `debugging/dev/` in development.
   ```php
   DEV::save('Hello, World', 'file.txt');
   ```

### TEST Class

- `TEST::error($message)`: Log an error to `debugging/test/error_log.txt` in testing.
   ```php
   TEST::error('Error found in testing environment');
   ```

- `TEST::msg($message)`: Display a message with the file and line number in testing.
   ```php
   TEST::msg('Message visible in testing environment');
   ```

- `TEST::run(callable $function)`: Execute a function in the testing environment.
   ```php
   TEST::run(function() { echo 'Function executed in testing environment'; });
   ```

- `TEST::save($content, $file)`: Save content to `debugging/test/` in testing.
   ```php
   TEST::save('Hello, World', 'file.txt');
   ```

### VARS Class

- `VARS::inc($name)`: Increment the value of a variable by 1.
   ```php
   VARS::inc('counter');
   ```

- `VARS::set($name, $value=null)`: Set a variable's value, defaulting to `null`.
   ```php
   VARS::set('myVariable', 'value');
   ```

- `VARS::toArray($name)`: Convert a variable into an array. If it doesn't exist, it creates an empty array.
   ```php
   VARS::toArray('myVariable');
   ```

- `VARS::toBool($name)`: Convert a variable to a boolean. If it doesn't exist, it defaults to `false`.
   ```php
   VARS::toBool('myVariable');
   ```

- `VARS::toFloat($name)`: Convert a variable to a float. If it doesn't exist, it defaults to `0.0`.
   ```php
   VARS::toFloat('myVariable');
   ```

- `VARS::toInt($name)`: Convert a variable to an integer. If it doesn't exist, it defaults to `0`.
   ```php
   VARS::toInt('myVariable');
   ```

- `VARS::toObject($name)`: Convert a variable to an object. If it doesn't exist, it creates an empty object.
   ```php
   VARS::toObject('myVariable');
   ```

- `VARS::toString($name)`: Convert a variable to a string. If it doesn't exist, it defaults to an empty string.
   ```php
   VARS::toString('myVariable');
   ```

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
