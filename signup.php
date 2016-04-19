<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>PicApp - Sign Up</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h3>PicApp - Sign Up</h3>
<?php 
	require_once('appvars.php');
	require_once('connectvars.php');

	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if(isset($_POST['submit'])){
		$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
		$password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
		$password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));
		if(!empty($username) && !empty($password1) && !empty($password2) && ($password1==$password2)){
			$query = "SELECT * FROM picapp_user WHERE username = '$username'";
			$data = mysqli_query($dbc, $query);
			if(mysqli_num_rows($data) == 0){
				$query = "INSERT INTO picapp_user (username, password) VALUES ('$username', SHA('$password1'))";
				mysqli_query($dbc, $query);
				echo '<p>Your new account has been successfully created. You\'re now ready to '
				. '<a href="index.php">log in</a>.</p>';
				mysqli_close($dbc);
				exit();
			}
			else{
				echo '<p class="error">An account already exists for this username. Please use a' 
				. 'different address.</p>';
				$username = "";
			}
		}
		else{
			echo '<p class = "error">You must enter all of the sign up data, including the disired' . 'password twice.</p>';
		}
	}
	mysqli_close($dbc);
 ?>
<p>Please enter your username and desired password to sign up to PicApp.</p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset>
	<legend>Registration Info</legend>
	<label for="usrname">Username:</label>
	<input type="text" id="username" name="username"><br />
	<label for="password1">Password:</label>
	<input type="password" id="password1" name="password1"><br />
	<label for="password2">Password Again:</label>
	<input type="password" id="password2" name="password2">
</fieldset>
	<input type="submit" value="Sign up" name="submit" />
</form>
</body>
</html>