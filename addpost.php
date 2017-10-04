<?php
// Session starts so session variables can be declared and used and connection to database is initialised.
session_start();
require_once('connect.php');
 
// The website checks if the user has submitted details for a new post on the "addpost" form.
// An array, "mistakes", is initialised which will store any errors in the details that the user has entered.
if (isset($_POST["submit"])) {
    $mistakes = array();
    
    // The program checks if the user has inputted their desired post comment and, if not, appends an error message to the "mistakes" array.
    // If they have inputted their post comment, the program checks if it contains a speech mark and escapes it to prevent SQL injection.
    // The post is then assigned to the variable $post2.
    if (strlen($_POST["post"]) > 0) {
        $post = $_POST["post"];
        $post2 = str_replace("'","''",$post);
    } else {
        array_push($mistakes, "Please enter your desired post.");
    }
    
    // If the user isn't logged in then an error message is outputted, as you must log in to be able to add new posts to a thread.
    if ($_SESSION["user"] == "Guest") {
        echo 'Error: You must be logged in to add new posts to the forum.';
    } elseif (empty($mistakes)) {  // If the user has entered a desired comment for their post then it's added to the "Posts" table.
        $thread_ID = $_POST['thread_id']; // The thread id is obtained ffrom the form and assigned to the variable "$thread_ID".
        $user = $_SESSION["user"]; // The username of the current user is assigned to a variable called "$user".
        $datetime = time(); // The current date and time is obtained using the PHP "time" function and assigned to the variable "$datetime".
        mysqli_query($link, "INSERT INTO Posts (Thread_ID, Comment, UserName, Time) VALUES ('$thread_ID','$post2','$user','$datetime');"); // The thread id, username of the creator, comment and creation time of the posts is added to the "Posts" table.
        header("location:view_thread.php?thread_id=$thread_ID"); // The user is redirected to the page of the thread they added a post to, with their new post now appearing on the page.
    } else { // Otherwise, if the user didn't enter a post comment then an error message is displayed telling them they must do this.
        foreach ($mistakes as $mistake) {
            echo $mistake ."<br>";
        }
    }
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
						<div class="Guest">Viewing as <?php echo $_SESSION["user"]; ?><br> <!--The website outputs "Viewing as 'user'", meaning that if they're logged in then it outputs their username, otherwise ouputs "Guest". -->
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
						<font a href="view.php" class="menu">View topics</font></td>
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
<br> <!-- A form is outputted in which the user can enter the post they wish to add to the thread. -->
	<div align="center">
		<table border="0" width="800" cellspacing="0" style="border: 1px solid #000080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" height="300" bordercolor="#000080" id="table3" cellpadding="2">
			<tr>
				<td valign="top">
          <form action="addpost.php" method=POST id="addpost">
            <b>Post:</b><br><br>
            <textarea style="width:100%; height:250px" form="addpost" name="post"></textarea><br><br>
            <input type="submit" value="Submit" name="submit">
            <input type="hidden" name="thread_id" value="<?php echo $_GET['thread_id'];?>"> <!-- The thread id is taken from the URL and is posted when the user presses the submit button on the form. -->
          </form>
        </td>
      </tr>
		</table>
	</div>
</body>
</html>