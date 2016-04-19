<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>PicApp - Edit Profile</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h2 align="center"><a href="index.php">PicApp</a> - Edit Profile</h2>

<?php
  require_once('appvars.php');
  require_once('connectvars.php');
  $user_id=$_COOKIE['user_id'];
  $username=$_COOKIE['username'];
  //$user_id = 13;
  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  if (isset($_POST['submit'])) {
    // Grab the profile data from the POST
    $first_name = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
    $last_name = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
    $gender = mysqli_real_escape_string($dbc, trim($_POST['gender']));
    $birthday = mysqli_real_escape_string($dbc, trim($_POST['birthday']));
    $city = mysqli_real_escape_string($dbc, trim($_POST['city']));
    $class = mysqli_real_escape_string($dbc, trim($_POST['class']));
    // $state = mysqli_real_escape_string($dbc, trim($_POST['state']));
    // $old_picture = mysqli_real_escape_string($dbc, trim($_POST['old_picture']));
    $new_picture = mysqli_real_escape_string($dbc, trim($_FILES['new_picture']['name']));
    $new_picture_type = end(explode(".",$new_picture)); //get the type of picture
    $new_picture_size = $_FILES['new_picture']['size']; 
    $new_picture_error = $_FILES['new_picture']['error'];
    $type_arr=array("gif","GIF","jpg","JPG","png","PNG","jpeg","GPEG");
    echo $new_picture_error .'<br />';
    echo $new_picture .'<br />';
    echo $new_picture_size .'<br />';
    echo $new_picture_type .'<br />';
    // list($new_picture_width, $new_picture_height) = getimagesize($_FILES['new_picture']['tmp_name']);
    $error = false;
    if(!in_array($new_picture_type, $type_arr)){
    	alert("sorry, only .jpg, .png, .jpeg is supported!");
    }
    if($new_picture_size > MM_MAXFILESIZE){
    	alert("the size of your picture is too big!");
    }
    if (($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE)) {
        if(is_dir(MM_UPLOADPATH .$username)){
          $target = '' .time() .rand(0,100) ."." .$new_picture_type;
          $target_primary = MM_UPLOADPATH .$username ."/" .$target;
          $target_resize = MM_UPLOADPATH .$username ."-small"  ."/" ."small" .$target;
        }else{
          mkdir(MM_UPLOADPATH .$username ."/");
          mkdir(MM_UPLOADPATH .$username ."-small" ."/");
          $target = '' .time() .rand(0,100) ."." .$new_picture_type;
          $target_primary = MM_UPLOADPATH .$username ."/" .$target;
          $target_resize = MM_UPLOADPATH .$username ."-small" ."/" ."small" .$target;
        }
        if(move_uploaded_file($_FILES['new_picture']['tmp_name'], $target_primary)){
        	copy($target_primary, $target_resize);
        	include('abeautifulsite/SimpleImage.php');
        	$src_image = new abeautifulsite\SimpleImage($target_resize);
        	$src_image->best_fit(320,200);
        	$src_image->save($target_resize);
        }
    }
    // Validate and move the uploaded picture file, if necessary
    // if (!empty($new_picture)) {
    //   if ((($new_picture_type == 'image/gif') || ($new_picture_type == 'image/jpeg') || ($new_picture_type == 'image/pjpeg') ||
    //     ($new_picture_type == 'image/png')) && ($new_picture_size > 0) && ($new_picture_size <= MM_MAXFILESIZE) &&
    //     ($new_picture_width <= MM_MAXIMGWIDTH) && ($new_picture_height <= MM_MAXIMGHEIGHT)) {
    //     if ($_FILES['file']['error'] == 0) {
    //       // Move the file to the target upload folder
    //       $target = MM_UPLOADPATH . basename($new_picture);
    //       if (move_uploaded_file($_FILES['new_picture']['tmp_name'], $target)) {
    //         // The new picture file move was successful, now make sure any old picture is deleted
    //         if (!empty($old_picture) && ($old_picture != $new_picture)) {
    //           @unlink(MM_UPLOADPATH . $old_picture);
    //         }
    //       }
    //       else {
    //         // The new picture file move failed, so delete the temporary file and set the error flag
    //         @unlink($_FILES['new_picture']['tmp_name']);
    //         $error = true;
    //         echo '<p class="error">Sorry, there was a problem uploading your picture.</p>';
    //       }
    //     }
    //   }
    //   else {
    //     // The new picture file is not valid, so delete the temporary file and set the error flag
    //     @unlink($_FILES['new_picture']['tmp_name']);
    //     $error = true;
    //     echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
    //       ' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size.</p>';
    //   }
    // }

    // Update the profile data in the database
    if (!$error) {
      if (!empty($first_name) && !empty($last_name) && !empty($gender) && !empty($birthday) && !empty($city) && !empty($class)) {
        // Only set the picture column if there is a new picture
        // if (!empty($new_picture)) {
        //   $query = "UPDATE mismatch_user SET first_name = '$first_name', last_name = '$last_name', gender = '$gender', " .
        //     " birthday = '$birthday', city = '$city', state = '$state', picture = '$new_picture' WHERE user_id = '$user_id'";
        // }
        // else {
          $query = "UPDATE picapp_user SET first_name = '$first_name', last_name = '$last_name', gender = '$gender', " .
            " birthday = '$birthday', city = '$city', class = '$class' WHERE user_id = '$user_id'";
        // }
        mysqli_query($dbc, $query);

        // Confirm success with the user
        echo '<p>Your profile has been successfully updated. Would you like to <a href="viewprofile.php">view your profile</a>?</p>';

        mysqli_close($dbc);
        exit();
      }
      else {
        echo '<p class="error">You must enter all of the profile data (the picture is optional).</p>';
      }
    }
  } // End of check for form submission
  else {
    // Grab the profile data from the database
    $query = "SELECT first_name, last_name, gender, birthday, city, class FROM picapp_user WHERE user_id = '$user_id'";
    $data = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($data);

    if ($row != NULL) {
      $first_name = $row['first_name'];
      $last_name = $row['last_name'];
      $gender = $row['gender'];
      $birthday = $row['birthday'];
      $city = $row['city'];
      $class = $row['class'];
      // $old_picture = $row['picture'];
    }
    else {
      echo '<p class="error">There was a problem accessing your profile.</p>';
    }
  }

  mysqli_close($dbc);
?>

  <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAXFILESIZE; ?>" />
    <fieldset>
      <legend>Personal Information</legend>
      <label for="firstname">First name:</label>
      <input type="text" id="firstname" name="firstname" value="<?php if (!empty($first_name)) echo $first_name; ?>" /><br />
      <label for="lastname">Last name:</label>
      <input type="text" id="lastname" name="lastname" value="<?php if (!empty($last_name)) echo $last_name; ?>" /><br />
      <label for="gender">Gender:</label>
      <select id="gender" name="gender">
        <option value="M" <?php if (!empty($gender) && $gender == 'M') echo 'selected = "selected"'; ?>>Male</option>
        <option value="F" <?php if (!empty($gender) && $gender == 'F') echo 'selected = "selected"'; ?>>Female</option>
      </select><br />
      <label for="birthday">Birthday:</label>
      <input type="text" id="birthday" name="birthday" value="<?php if (!empty($birthday)) echo $birthday; else echo 'YYYY-MM-DD'; ?>" /><br />
      <label for="city">City:</label>
      <input type="text" id="city" name="city" value="<?php if (!empty($city)) echo $city; ?>" /><br />
      <label for="class">Class:</label>
      <input type="text" id="class" name="class" value="<?php if (!empty($class)) echo $class; ?>" /><br />
      <label for="new_picture">Picture:</label>
      <input type="file" id="new_picture" name="new_picture" />

      <!-- <input type="hidden" name="old_picture" value="<?php if (!empty($old_picture)) echo $old_picture; ?>" />
      <label for="new_picture">Picture:</label>
      <input type="file" id="new_picture" name="new_picture" /> -->
      <?php 
        // if (!empty($old_picture)) {
        // echo '<img class="profile" src="' . MM_UPLOADPATH . $old_picture . '" alt="Profile Picture" />';}
         ?>
    </fieldset>
    <input type="submit" value="Save Profile" name="submit" />
  </form>

</body> 
</html>
