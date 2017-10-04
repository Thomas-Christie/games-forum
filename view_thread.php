<?php
// Session starts so session variables can be declared and used and connection to database is initialised.
session_start();
require_once('connect.php');

//If the user presses the "delete" button by one of their posts, the page refreshes and the post is deleted.
if (isset($_POST["delete"])) {
  $id = $_POST["id"]; //The id of the post to be deleted is obtained, as it is "posted" when the delete button is pressed.
  mysqli_query($link, "DELETE FROM Posts WHERE Post_ID = $id;"); //An SQL query then deletes the user's post from the "Posts" table, so their post is no longer displayed.
}
?>

<html>
<head>
  <title>GameZsoc</title>
  <link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
  <div align="center">
		<table border="0" width="800" cellspacing="0">
			<tr>
				<td width="300" style="border-left: 1px solid #000080; border-top: 1px solid #000080;">
				<img border="0" src="images/GameZ.png" width="300" height="78"></td>
				<td valign="bottom" align="right" width="500" style="border-top: 1px solid #000080; border-right: 1px solid #000080">
				<div class="title">The Gaming and Console forum<img border="0" src="images/Blank.gif" width="2" height="8"></div>
				</td>
			</tr>
			<tr>
				<td width="300" style="border-left: 1px solid #000080; border-bottom: 1px solid #000080">
				<table border="0" width="300" cellspacing="0" cellpadding="0" id="table1">
					<tr>
						<td align="center">
						<div class="Guest">Viewing as <?php echo $_SESSION["user"]; ?><br> <!--The website outputs "Viewing as 'user'", meaning that if they're logged in then it outputs their username, otherwise outputs "Guest". -->
            <?php if ($_SESSION["user"]=="Guest") {
    ?>
            <a href="index.php"> (Login) </a> <!-- If the user isn't logged in then next to the welcome message a "login" hyperlink will appear, linking to the homepage where they can sign in.  -->
            <?php 
} else {
    ?>
            <a href="index.php?logout=true"> (Logout) </a> <!-- If the user is logged in a "logout" hyperlink will appear next to the welcome message, linking to the homepage with "?logout=true" appended to the web address which logs them out.  -->
            <?php 
} ?>
            </div></td>
						<td width="110">
						<p align="right">
						<img border="0" src="images/soc.png" width="100" height="42"></td>
					</tr>
				</table>
				</td>
				<td width="500" align="right" style="border-right: 1px solid #000080; border-bottom: 1px solid #000080">
				<table border="0" width="480" cellpadding="0" id="table2" height="40">
					<tr>
						<td align="center" width="120" bgcolor="#FFFF99">
						<a href="view.php">View topics</a></td>
						<td align="center" width="120" bgcolor="#000080">
						<a href="additem.php" class="menu">Add thread</a></td>
						<td align="center" width="120" bgcolor="#000080">
						<a href="index.php" class="menu">Forum index</a></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
  </div>
<br>
	<div align="center">
		<table border="1" width="800" cellspacing="0" style="border-bottom: 1px solid #000080; padding-left: 10px; padding-right: 10px; padding-top: 10px; padding-bottom: 10px" height="300" bordercolor="#000080" id="table3" cellpadding="2">
          <?php 
          //If the user has just clicked onto the page displaying the posts for a thread, the thread id will be appended to the web address using "?thread_id=x"
          if (isset($_GET['thread_id'])) {
            $_SESSION['thread_id'] = $_GET['thread_id']; //If the thread id is present in the web address, it will be taken using the "get" method and stored as a session variable
          } else {
            $_SESSION['thread_id'] = $_SESSION['thread_id']; //However, if the thread id isn't present in the web address (the user has pressed "delete" for a post and so the page has refreshed and no longer has "?thread_id=x" appended to the web address), then the session variable "thread_id" will remain what it previously was (as they're viewing the same thread)
          }
          $posts = mysqli_query($link, "SELECT * FROM Posts WHERE Thread_ID = '$_SESSION[thread_id]' ORDER BY Time ASC;"); //The posts for the thread which the user has clicked on are obtained from the "Posts" table, ordered to show oldest posts first
          $title = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM Threads WHERE Thread_ID = '$_SESSION[thread_id]';"))["Title"]; //The thread title is obtained from the "Threads" database
          echo "<tr><td><h1>$title</h1></td></tr>"; //The thread title is outputted as a heading at the top of the page.                         
          while ($row = mysqli_fetch_assoc($posts)) { //A while loop is used to output all the posts for the thread the user is viewing (which have been obtained from the database on line 76)
              $user = $row['UserName']; //As "row" is an associative array, the username of the creator of the post, the time of the creation of the post and the post id of each post have to be extracted and stored in variables on lines 80-82
              $time = $row['Time'];
              $post_id = $row['Post_ID'];
              $creation_time = date("Y-m-d-h-i-s", $time); //The time is converted back from Unix time using in-built PHP "date" function
              echo "<tr><td><b>$user</b> posted at <b>$creation_time</b><br><br>"; //The username and creation time of the post is outputted
              echo "{$row['Comment']}<br><br>"; //The comment of each post is outputted beneath the username of its creator and its creation time
              if ($_SESSION["user"]==$user) { //If the person logged in is the same as the user who created a post, then a delete button will appear beneath that post
                echo "<form action='view_thread.php' method=POST id='delete_post'><input type='submit' value='Delete' name='delete'><input type='hidden' value='$post_id' name='id'></form></td></tr>"; //When the "delete" button is pressed, the id of the post is posted so that it can be used in an SQL statement to delete the post from the "Posts" table
              } else {
                echo "<br><br>";
              }
          }
          echo "<tr><td><a href='addpost.php?thread_id=$_SESSION[thread_id]'>Add Post</a></td></tr>"; //After all the post are outputted, a hyperlinked "Add Post" message is outputted, which links to a page where the user can add a post to the thread. The thread id is appended to the web address so that when they add a new post it can have the thread id saved in the database with the post, connecting the post to its thread.                                                                                                                                            
          ?>
	</div>
</body>
</html>