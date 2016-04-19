<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>PicApp - View Profile</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<h2 align="center"><a href="index.php"> PicApp</a> - View Profile</h2>
	<table>
	<tr>
		<td><b>My profile</b></td>
		<td><b>My pictures<b></td>
	</tr>
	<tr><td valign="top" class="td_viewprofile">
	<div class="viewProfile">
	<?php
	require_once('connectvars.php');
	require_once('appvars.php');
	$_user_id=$_COOKIE['user_id'];
	$_user_name=$_COOKIE['username'];
	if(isset($_GET['pic_dir'])){
		$remove_file_name = $_GET['pic_dir'];
		$remove_result = unlink($remove_file_name);
	}
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$query = "SELECT username, first_name, last_name, gender, birthday, class, city, picfolder FROM picapp_user WHERE user_id = '". $_user_id . "'";
	$data = mysqli_query($dbc, $query);
	if(mysqli_num_rows($data) == 1){
		$row = mysqli_fetch_array($data);
		echo '<table>';
		if(!empty($row['username'])){
			echo '<tr><td class="label">Username: </td><td>' .$row['username'] .'</td></tr>';
		}
		if(!empty($row['first_name'])){
			echo '<tr><td class="label">First name: </td><td>' .$row['first_name'] .'</td></tr>';
		}
		if(!empty($row['last_name'])){
			echo '<tr><td class="label">Last name: </td><td>' .$row['last_name'] .'</td></tr>';
		}
		if(!empty($row['gender'])){
			echo '<tr><td class="label">Gender: </td><td>';
			if($row['gender'] == 'M'){
				echo 'Male';
			}
			else if($row['gender'] == 'F'){
				echo 'Female';
			}
			else{
				echo '?';
			}
			echo "</td></tr>";
		}
		if(!empty($row['class'])){
			echo '<tr><td class="label">Class: </td><td>' .$row['class'] .'</td></tr>';
		}
		if (!empty($row['birtyday'])) {
      if (!isset($_GET['user_id']) || ($user_id == $_GET['user_id'])) {
        // Show the user their own birtyday
        echo '<tr><td class="label">birtyday: </td><td>' . $row['birtyday'] . '</td></tr>';
      }
      else {
        // Show only the birth year for everyone else
        list($year, $month, $day) = explode('-', $row['birtyday']);
        echo '<tr><td class="label">Year born: </td><td>' . $year . '</td></tr>';
      }
    }
    if (!empty($row['city'])) {
      echo '<tr><td class="label">Location: </td><td>' . $row['city'] . '</td></tr>';
    }
    
    //if (!empty($row['picture'])) {
      // echo '<tr><td class="label">Picture:</td><td><img src="' . MM_UPLOADPATH . $row['picture'] .
        // '" alt="Profile Picture" /></td></tr>';
    // }
    echo '</table>';
  } // End of check for a single row of user results
  else {
    echo '<p class="error">There was a problem accessing your profile.</p>';
  }

  mysqli_close($dbc);
?>
</div></td>
<td>
<div>
	<?php 
    		$my_pic_dir = MM_UPLOADPATH .$_user_name ."-small"."/*.*";
    		//echo $my_pic_dir;
    		$my_pic_files = glob($my_pic_dir);
    		//echo "<br />" .count($my_pic_files);
    		for($i=0; $i<count($my_pic_files); $i++)
    		{
    			$my_pic = $my_pic_files[$i];
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
    			$ext=strtolower(pathinfo($my_pic, PATHINFO_EXTENSION));
    			if(in_array($ext, $supported_file)){
    				//print $my_pic ."<br />";
    				echo '<img src="' .$my_pic .'" alt="Random image" />' ."<br />";
    				echo '<a href="viewprofile.php?pic_dir=' .$my_pic .'">Remove it</a> &#8593;<br /><br />';
    			}else{
    				continue;
    			}
    		}
    	 ?>
</div>
</td>
</tr></table>
</body> 
</html>