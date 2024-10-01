<?php

	require_once 'EnvDebuggerPHP/EnvDebuggerPHP.php'; // Call the library

	$environments = EnvDebuggerPHP::getEnvironments();
	$environment  = $environments['debug'];
	$error = 0;

	if (isset($_POST['error'])) {
		$error = (int) $_POST['error'];
	}

	if (isset($_POST['environment'])) {
		$environment = $_POST['environment'];
	}

	EnvDebuggerPHP::start($environment); // Initialize the environment

	DEBUG::msg('This message will be shown in the mode: debug');
	DEV::msg('This message will only be shown in the environments: ' . $environments['development']);
	TEST::msg('This message will only be shown in the environments: ' . $environments['testing']);

	VARS::set('var1', 5);
	VARS::set('var1', ['dev' => 'valor']);
	VARS::set('var1', 'rojo');

	VARS::set('i', 0);
	while (VARS::toInt('i')  <= 5) {
		VARS::inc('i');
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PHP Error Testing in Different Environments</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
	<style>
		body {
			background-color: #2c2f33; 
			color: #ffffff; 
		}

		.container {
			max-width: 600px;
			margin-top: 50px;
		}

		.card {
			background-color: #23272a; 
			color: #ffffff; 
			border-radius: 10px;
			padding: 20px;
		}

		.card-header {
			font-size: 1.5rem;
			background-color: #2c2f33;
			border-radius: 10px 10px 0 0;
			border-bottom: 1px solid #444;
		}

		.btn-custom {
			background-color: #5cb85c; 
			color: #ffffff;
			border: none;
		}

		.btn-custom:hover {
			background-color: #4cae4c; 
		}

		label {
			margin-top: 10px;
		}
	</style>
</head>
<body>
	<div class="container pt-3 pb-5">
		<div class="card shadow">
			<div class="card-header text-center">
				PHP Error Testing
			</div>
			<div class="card-body">
				<div style="text-align:center">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="100" height="100">
						<path d="M0 0 C4.61356102 0.32954007 5.32504293 2.32504293 9 6 C18.57 6.33 28.14 6.66 38 7 C38 27.13 38 47.26 38 68 C27.07870169 66.78652241 21.63088137 64.63088137 14 57 C11.58921773 56.08234114 11.58921773 56.08234114 9 57 C7.1303947 58.50243676 5.4265585 60.07364365 3.74609375 61.78515625 C2 63 2 63 -0.21484375 62.68359375 C-2 62 -2 62 -2.76953125 60.29296875 C-3 58 -3 58 -1.19921875 55.67578125 C0.04408203 54.50595703 0.04408203 54.50595703 1.3125 53.3125 C2.13363281 52.52488281 2.95476563 51.73726562 3.80078125 50.92578125 C6 49 6 49 8 48 C7.67 47.319375 7.34 46.63875 7 45.9375 C5.82692685 42.49159762 5.85819507 39.61602578 6 36 C5.21753906 36.01160156 4.43507813 36.02320313 3.62890625 36.03515625 C2.61699219 36.04417969 1.60507813 36.05320313 0.5625 36.0625 C-0.44683594 36.07410156 -1.45617187 36.08570313 -2.49609375 36.09765625 C-5 36 -5 36 -6 35 C-6.125 32.5 -6.125 32.5 -6 30 C-5 29 -5 29 -2.49609375 28.90234375 C-1.48675781 28.91394531 -0.47742188 28.92554687 0.5625 28.9375 C1.57441406 28.94652344 2.58632812 28.95554687 3.62890625 28.96484375 C4.80259766 28.98224609 4.80259766 28.98224609 6 29 C6.18175781 28.33613281 6.36351562 27.67226563 6.55078125 26.98828125 C7.89523291 22.26546387 9.14984737 18.07164661 12 14 C10.989375 13.814375 9.97875 13.62875 8.9375 13.4375 C4.28352413 12.1145907 0.60628371 9.063745 -2 5 C-1.75079288 2.84280088 -1.55117511 1.55117511 0 0 Z " fill="#fff" transform="translate(11,27)"/>
						<path d="M0 0 C0.5775 0.226875 1.155 0.45375 1.75 0.6875 C2.46875 2.42578125 2.46875 2.42578125 2.75 4.6875 C0.28198481 8.38266719 -3.09486529 11.92757377 -7.34375 13.42578125 C-9.25 13.6875 -9.25 13.6875 -12.25 13.6875 C-11.90066406 14.26757812 -11.55132812 14.84765625 -11.19140625 15.4453125 C-8.61970109 19.86019444 -6.65004495 23.48691563 -6.25 28.6875 C-5.39535156 28.67589844 -4.54070312 28.66429688 -3.66015625 28.65234375 C-2.55542969 28.64332031 -1.45070312 28.63429688 -0.3125 28.625 C0.78964844 28.61339844 1.89179687 28.60179688 3.02734375 28.58984375 C5.75 28.6875 5.75 28.6875 6.75 29.6875 C6.875 32.1875 6.875 32.1875 6.75 34.6875 C5.75 35.6875 5.75 35.6875 3.24609375 35.78515625 C2.23675781 35.77355469 1.22742188 35.76195312 0.1875 35.75 C-0.82441406 35.74097656 -1.83632812 35.73195312 -2.87890625 35.72265625 C-4.05259766 35.70525391 -4.05259766 35.70525391 -5.25 35.6875 C-5.34087891 36.73550781 -5.34087891 36.73550781 -5.43359375 37.8046875 C-5.51738281 38.71476562 -5.60117188 39.62484375 -5.6875 40.5625 C-5.76871094 41.46742187 -5.84992188 42.37234375 -5.93359375 43.3046875 C-6.25 45.6875 -6.25 45.6875 -7.25 47.6875 C-6.70730469 48.15800781 -6.16460937 48.62851563 -5.60546875 49.11328125 C-4.89003906 49.73589844 -4.17460938 50.35851562 -3.4375 51 C-2.72980469 51.61488281 -2.02210937 52.22976562 -1.29296875 52.86328125 C2.51888388 56.2669814 2.51888388 56.2669814 3.75 57.6875 C3.4375 59.875 3.4375 59.875 2.75 61.6875 C0.96484375 62.37109375 0.96484375 62.37109375 -1.25 62.6875 C-2.99609375 61.47265625 -2.99609375 61.47265625 -4.6875 59.75 C-7.39262704 57.29430205 -7.39262704 57.29430205 -10.75 55.9375 C-14.04661129 56.92648339 -15.85680277 58.79277013 -18.37109375 61.08984375 C-24.21585592 66.05971428 -29.46805512 66.82283946 -37.25 67.6875 C-37.25 47.5575 -37.25 27.4275 -37.25 6.6875 C-27.68 6.6875 -18.11 6.6875 -8.25 6.6875 C-7.59 5.3675 -6.93 4.0475 -6.25 2.6875 C-3.0339196 -0.42137772 -3.0339196 -0.42137772 0 0 Z " fill="#fff" transform="translate(88.25,27.3125)"/>
						<path d="M0 0 C2.125 0.8125 2.125 0.8125 3.75 2.875 C7.03711876 5.55659688 9.57757238 5.42290089 13.7265625 5.2890625 C17.24720513 4.58952113 18.78765865 2.41696608 21.125 -0.1875 C23.9375 0 23.9375 0 26.125 0.8125 C25.57776317 4.18712715 25.0701 5.89485 23.125 8.8125 C24.300625 10.17375 24.300625 10.17375 25.5 11.5625 C29.46330303 16.36786458 30.125 18.19189704 30.125 24.8125 C17.585 24.8125 5.045 24.8125 -7.875 24.8125 C-7.875 18.06394509 -7.875 18.06394509 -5.5 14.625 C-5.05914062 13.97144531 -4.61828125 13.31789063 -4.1640625 12.64453125 C-3.52597656 11.73767578 -3.52597656 11.73767578 -2.875 10.8125 C-2.215 9.8225 -1.555 8.8325 -0.875 7.8125 C-1.37 7.4 -1.865 6.9875 -2.375 6.5625 C-3.875 4.8125 -3.875 4.8125 -3.875 0.8125 C-1.875 -0.1875 -1.875 -0.1875 0 0 Z " fill="#fff" transform="translate(38.875,6.1875)"/>
					</svg>
				</div>
				<form action="" method="POST">
					<div class="mb-3">
						<label for="environment" class="form-label">Select development environment:</label>
						<select name="environment" id="environment" class="form-select">
							<?php $env = EnvDebuggerPHP::getEnvironment(); ?>
							<option value="debug" <?php echo ('debug' === $env ? 'selected' : ''); ?>>Debug</option>
							<?php 
								foreach($environments as $key => $value){ ?>
									<option value="<?php echo $value; ?>" <?php echo ($value === $env ? 'selected' : ''); ?>>
										<?php echo ucfirst($value); ?>
									</option><?php
								}
							?>
						</select>
					</div>
					<div class="mb-3">
						<label for="error" class="form-label">Select error type:</label>
						<select name="error" id="error" class="form-select">
							<option value="0" <?php echo ($error == 0 ? 'selected' : ''); ?>>None</option>
							<option value="1" <?php echo ($error == 1 ? 'selected' : ''); ?>>E_NOTICE: Undefined variable</option>
							<option value="2" <?php echo ($error == 2 ? 'selected' : ''); ?>>E_ERROR: Undefined function</option>
							<option value="3" <?php echo ($error == 3 ? 'selected' : ''); ?>>E_WARNING: Non-existent file</option>
							<option value="4" <?php echo ($error == 4 ? 'selected' : ''); ?>>E_WARNING: Division by zero</option>
							<option value="5" <?php echo ($error == 5 ? 'selected' : ''); ?>>E_PARSE: Parse error</option>
							<option value="6" <?php echo ($error == 6 ? 'selected' : ''); ?>>E_ERROR: Redefine function</option>
							<option value="7" <?php echo ($error == 7 ? 'selected' : ''); ?>>E_NOTICE: Non-existent array index</option>
							<option value="8" <?php echo ($error == 8 ? 'selected' : ''); ?>>E_ERROR: Class not found</option>
							<option value="9" <?php echo ($error == 9 ? 'selected' : ''); ?>>E_RECOVERABLE_ERROR: Type error</option>
						</select>
					</div>
					<div class="text-center pt-3">
						<button type="submit" class="btn btn-custom btn-lg">START</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>

<?php
	DEBUG::run(function(){
		global $environments; ?>
		<div>
			<h5>RUN FUNCTION IN DEBUG</h5>
			<p>This function will run only in the environments:<br> &#10148; debug</p>
		</div><?php
	});

	DEV::run(function(){
		global $environments; ?>
		<div>
			<h5>RUN FUNCTION IN DEV</h5>
			<p>This function will run only in the environments:<br> &#10148; <?php echo $environments['development']; ?></p>
		</div><?php
	});

	TEST::run(function(){
		global $environments; ?>
		<div>
			<h5>RUN FUNCTION IN TEST</h5>
			<p>This function will run only in the environments:<br> &#10148; <?php echo $environments['testing']; ?></p>
		</div><?php
	});

	switch ($error) {
		case 0:
		break;
		case 1:
			// -----------------------------------
			// E_NOTICE: Undefined variable error
			// -----------------------------------
			echo $undefined_var;
		break;
		case 2:
			// -----------------------------------
			// E_ERROR: Undefined function error
			// -----------------------------------
			non_existing_function();
		break;
		case 3:
			// -----------------------------------
			// E_WARNING: Warning for including a non-existent file
			// -----------------------------------
			include('non_existent_file.php');
		break;
		case 4:
			// -----------------------------------
			// E_WARNING: Warning for division by zero
			// -----------------------------------
			echo 10 / 0;
		break;
		case 5:
			// -----------------------------------
			// E_PARSE: Parse error
			// -----------------------------------
			eval('echo "Hello;');
		break;
		case 6:
			// -----------------------------------
			// E_ERROR: Fatal error for redefining a function
			// -----------------------------------
			function myFunction() {
				echo "Original function";
			}
			// Uncomment this line if you want to test the duplicate function error
			function myFunction() {
				echo "Duplicate function";
			}
		break;
		case 7:
			// -----------------------------------
			// E_NOTICE: Error accessing a non-existent array index
			// -----------------------------------
			$array = [1, 2, 3];
			echo $array[5];
		break;
		case 8:
			// -----------------------------------
			// E_ERROR: Class not found error
			// -----------------------------------
			$object = new NonExistentClass();
		break;
		case 9:
			// -----------------------------------
			// E_RECOVERABLE_ERROR: Type error in function parameters
			// -----------------------------------
			function sum(int $a, int $b) {
				return $a + $b;
			}
			echo sum('string', 5);
		break;
		default:
			echo "No error case selected.";
		break;
	}
?>