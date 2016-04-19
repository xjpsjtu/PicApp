<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>PicApp - My Class</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h2 align="center"><a href="index.php">PicApp</a> - My Class</h3>

<?php 
	require_once('appvars.php');
  	require_once('connectvars.php');
  	$user_id=$_COOKIE['user_id'];
  	$username=$_COOKIE['username'];
  	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  	//access the class id of current user
  	$query = "SELECT class FROM picapp_user WHERE user_id='" .$user_id ."'";
  	$data = mysqli_query($dbc, $query);
  	$row = mysqli_fetch_array($data);
  	$class_id = $row['class'];
  	$query = "SELECT username, first_name, last_name, gender, birthday, class, city, picfolder FROM picapp_user WHERE class = '". $class_id ."'";
  	//echo $query;
  	$data = mysqli_query($dbc, $query);
  	while($row = mysqli_fetch_array($data)){
  		$one_user = $row['username'];
  		//echo "the user is  " .$one_user .'<br />';
  		$this_user_dir = MM_UPLOADPATH .$one_user ."-small" ."/*.*";
  		//echo "the dir of this user is  " .$this_user_dir .'<br />';
  		$this_user_files = glob($this_user_dir);
  		if(count($this_user_files) == 0)continue;
  		echo '<fieldset>';
  		echo '<legend>' .$one_user .'</legend>';
  		echo '<div class="class_pic">';
  		for($i=0; $i<count($this_user_files); $i++)
    		{
    			
    			$this_user_pic = $this_user_files[$i];
    			$supported_file=array(
    				'gif',
    				'GIF',
    				'jpg',
    				'JPG',
    				'jpeg',
    				'JPEG',
    				'png',
    				'PNG'
    			);
    			$ext=strtolower(pathinfo($this_user_pic, PATHINFO_EXTENSION));
    			if(in_array($ext, $supported_file)){
    				//print $this_user_pic ."<br />";
    				echo '<div class="in_pic">';
    				echo '<img src="' .$this_user_pic .'" alt="Random image" />' ."<br />";
    				echo '</div>';
    			}else{
    				continue;
    			}
    			
    		}
    		echo '</div>';
    		echo '</fieldset>';
  	}
  	mysqli_close($dbc);


?>

</body>