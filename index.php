<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>PicApp</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<h2 align="center">Upload your pictures</h2>
		<div class="index">
		<?php 
			require_once("appvars.php");
			require_once("connectvars.php");
			if(isset($_COOKIE['username'])){
				echo '&#10084; <a href="viewprofile.php" class="index">View profile</a><br />';
				echo '&#10084; <a href="editprofile.php" class="index">Edit profile</a><br />';
				echo '&#10084; <a href="logout.php" class="index">Log Out(' .$_COOKIE['username'] .')</a><br />';
				echo '&#10084; <a href="myclass.php" class="index">My Class</a><br />';
			}else{
				echo '&#10084; <a href="login.php" class="index">Log in</a><br />';
				echo '&#10084; <a href="signup.php" class="index">Sign Up</a><br />';
			}
		 ?>
		</div>
	</body>
</html>