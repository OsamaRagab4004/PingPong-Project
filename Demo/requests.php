<?php
    
	
	include("includes/header.php");
?>

<div class="main_column column" id="main_column">

	<h4>Friend Requests</h4>

	<?php  

	//get all the requests, that sent to this user

	$query = mysqli_query($con, "SELECT * FROM friend_requests WHERE user_to='$userLoggedIn'");
	if(mysqli_num_rows($query) == 0)
		echo "You have no friend requests at this time!";

	else {

		// if this user have requests : 

		while($row = mysqli_fetch_array($query)) {

			$user_from = $row['user_from'];
			$user_from_obj = new User($con, $user_from);

			echo $user_from_obj->getFirstAndLastName() . " sent you a friend request!";

			//$user_from_friend_array = $user_from_obj->getFriendArray();

			if(isset($_POST['accept_request' . $user_from ])) {

				//if accept the request, i will update the array for two person, and add them to friends array.

				//Update the friend array of logged_in person
				$add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array, '$user_from,') WHERE username='$userLoggedIn'");

				//Update the friend array of user
				$add_friend_query = mysqli_query($con, "UPDATE users SET friend_array=CONCAT(friend_array, '$userLoggedIn,') WHERE username='$user_from'");

				// Delete the request from the friend requests table.

				$delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
				echo "You are now friends!";
				header("Location: requests.php");
			}

			if(isset($_POST['ignore_request' . $user_from ])) {
				$delete_query = mysqli_query($con, "DELETE FROM friend_requests WHERE user_to='$userLoggedIn' AND user_from='$user_from'");
				echo "Request ignored!";
				header("Location: requests.php");
			}

			/*

				every time in while loop, it will make a form for the friend request, and notice that, every input has the name of the person who send the request, so you can know which one will you add to the array and usw, name="accept_request<?php echo $user_from; ?>,,,(isset($_POST['accept_request' . $user_from ]));



			*/

			?>


			<form action="requests.php" method="POST">
				<input type="submit" name="accept_request<?php echo $user_from; ?>" id="accept_button" value="Accept">
				<input type="submit" name="ignore_request<?php echo $user_from; ?>" id="ignore_button" value="Ignore">
			</form>
			<?php


		} //end while

	}

	?>


</div>