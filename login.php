<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;
	$query2 = "SELECT user_id FROM tbusers WHERE email = '$email' AND password = '$pass'";
	$res = $mysqli->query($query2);
	if($row = mysqli_fetch_array($res)){
		$user_id=$row['user_id'];
	}

	if(isset($_POST['submit'])){
			$filename= $_FILES['picToUpload']['name'];
			$target_dir="gallery/";
			if($filename != ''){
				$target_file=$target_dir.basename($_FILES['picToUpload']['name']);
				//file extension
				$extension=strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
				//valid file extension 
				$extensions_arr= array("jpg","jpeg");

				//check size 
				$img_size=$_FILES['picToUpload']['size'];
				//check extension
				if(!in_array($extension, $extensions_arr)){
					echo "Invalid type";
					}
					elseif($img_size>1000000)
					{
						echo "Image is too large";
					}
					else
					{
						$query="INSERT INTO tbgallery(filename,user_id) VALUES('".$filename."','".$user_id."')";
						if($mysqli->query($query)){
							move_uploaded_file($_FILES['picToUpload']['tmp_name'], $target_file);
						}
					}	
				//}
				//else{
					//echo "Invalid file type";
				}
			}
		

?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Kiara Jaimungal">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php

			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form action='login.php' method='post' enctype='multipart/form-data' >
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
									<input type='hidden' name='loginEmail' value='" . $_POST["loginEmail"] . "' />
									<input type='hidden' name='loginPass' value='" . $_POST["loginPass"] . "' />
								</div>
						  	</form>";

					
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>

		<div class="container">
			<h3>Image gallery</h3>
				<div class="row imageGallery">
					<?php
					$query3 = "SELECT filename from tbgallery WHERE user_id = '$user_id'";
					$result3 = mysqli_query($mysqli,$query3);
        			while($row2 = mysqli_fetch_array($result3))
        			{
        				echo "<div class='col-3' style='background-image: url(gallery/".$row2['filename'].")'>
						</div>";
        			}
				?>
			</div>
		</div>
	</div>
</body>
</html>