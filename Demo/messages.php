<?php 
include("includes/header.php");

$message_obj = new Message($con, $userLoggedIn);


// the name of the user will be in the link : 

if(isset($_GET['u']))
	$user_to = $_GET['u'];
else {
	$user_to = $message_obj->getMostRecentUser();
	if($user_to == false)
		$user_to = 'new';


		 /*

			if no one in the link or no one you have text, user_to will be "new", and then i will print a input form that search for poeple you want to contact them.



		*/
}






if($user_to != "new")
	$user_to_obj = new User($con, $user_to); // if i found a user, so i can get all info about him with this object.



// this code is for line 104, so if you want to send a message

if(isset($_POST['post_message'])) {

	if(isset($_POST['message_body'])) {
		$body = mysqli_real_escape_string($con, $_POST['message_body']);
		$date = date("Y-m-d H:i:s"); // Notice here : you should write the date like that :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
		$message_obj->sendMessage($user_to, $body, $date);
	}

}

 ?>



<!--This the left side information on the page ::::::-->
 <div class="user_details column">
		<a href="<?php echo $userLoggedIn; ?>">  <img src="<?php echo $user['profile_pic']; ?>"> </a>

		<div class="user_details_left_right">
			<a href="<?php echo $userLoggedIn; ?>">
			<?php 
			echo $user['first_name'] . " " . $user['last_name'];

			 ?>
			</a>
			<br>
			<?php echo "Posts: " . $user['num_posts']. "<br>"; 
			echo "Likes: " . $user['num_likes'];

			?>
		</div>
	</div>







	<div class="main_column column" id="main_column">
		<?php  

		//when the meassage.php get the name of the user, it show the convesation for this user



		if($user_to != "new"){
			echo "<h4>You and <a href='$user_to'>" . $user_to_obj->getFirstAndLastName() . "</a></h4><hr><br>";

			echo "<div class='loaded_messages' id='scroll_messages'>";
			echo $message_obj->getMessages($user_to);
			echo "</div>";
		}
		else {
			echo "<h4>New Message</h4>";
		}
		?>

       <!--Here if you don't text anyone, he will print a label that search for poeple to text :::::::::-->

		<div class="message_post">
			<form action="" method="POST">
				<?php
				if($user_to == "new") {
					echo "Select the friend you would like to message <br><br>";
					/*Very Important ::::::

					https://stackoverflow.com/questions/12456399/how-to-use-this-reference-of-the-element-calling-the-function

					this article about this in web.

					*/

					// messages.php onkeyup getUsers >> Demo.js excute getUsers() >> post to ajax_search_friend.

					?> 
					To: <input type='text' onkeyup='getUsers(this.value, "<?php echo $userLoggedIn; ?>")' name='q' placeholder='Name' autocomplete='off' id='seach_text_input'>

					<?php
					echo "<div class='results'></div>";
				}
				else {//:::::::::::::::::::::::::::::::::::::::::::::::::::HERE::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
					echo "<textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>";
					echo "<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>";
				}

				?>
			</form>

		</div>




		<script> // this code to make the scroll automatic for the last message : 
			var div = document.getElementById("scroll_messages");
			div.scrollTop = div.scrollHeight;
		</script>

	</div>

	<div class="user_details column" id="conversations">
			<h4>Conversations</h4>

			<div class="loaded_conversations">
				<?php echo $message_obj->getConvos(); ?>
			</div>
			<br>
			<a href="messages.php?u=new">New Message</a>

		</div>
