<?php
// Session starts so session variables can be declared and used and connection to database is initialised.
session_start();
require_once('connect.php');

// The website checks if the user has submitted details for a new thread on the "addthread" form.
// An array, "incorrect", is initialised which will store any errors in the details that the user has entered.
if (isset($_POST["submit"])) {
    $incorrect = array();
    
    // The program checks if the user has inputted their desired thread title and, if not, appends an error message to the "incorrect" array.
    // The desired thread title is then assigned to the variable "$des_thread_title".
    if (strlen($_POST["title"]) > 0) {
        $des_thread_title = $_POST["title"];
    } else {
        array_push($incorrect, "Please enter your desired thread title.");
    }
  
    // The program validates the user's desired first post using exactly the same method as for the desired thread title.
    if (strlen($_POST["first_post"]) > 0) {
        $des_first_post = $_POST["first_post"];
    } else {
        array_push($incorrect, "Please enter your desired first post.");
    }
    
    // If the user isn't logged in then an error message is outputted, as you must log in to be able to add new threads.
    if ($_SESSION["user"] == "Guest") { 
        echo 'Error: You must be logged in to create new items on the forum.';
    } elseif (empty($incorrect)) { // If the user has entered all the details, then an SQL statement is used to see if there is already a thread with the desired thread title in the database.
        $results = mysqli_query($link, "SELECT * FROM Threads WHERE Title = '$des_thread_title';");
        $threads = mysqli_num_rows($results);
        if ($threads > 0) { // If there is already a thread with the desired title, then an error message is outputted telling the user to try creating a thread with a different name to prevent duplicate threads.
            echo 'Error: Thread already exists with the desired title, please try creating a thread with a different title.';
        } else {
            $datetime = time(); // The current date and time is obtained using the PHP "time" function.
            $user = $_SESSION["user"]; // The username of the current user is assigned to a variable called "$user".
            $des_thread_title2 = str_replace("'","''",$des_thread_title); // The program checks if their desired thread title contains a speech mark and escapes it to prevent SQL injection.
            $des_first_post2 = str_replace("'","''",$des_first_post); // // The program checks if their desired first post contains a speech mark and escapes it to prevent SQL injection.
            mysqli_query($link, "INSERT INTO Threads (Title, UserName, Creation_Time) VALUES ('$des_thread_title2','$user','$datetime');"); // An SQL query inserts the username of the creator of the thread, the thread title and the creation time of the thread into the "Threads" table.
            $thread_ID = mysqli_fetch_assoc(mysqli_query($link, "SELECT Thread_ID FROM Threads WHERE Title = '$des_thread_title2';"))["Thread_ID"]; // The thread id is obtained from the "Threads" table using an SQL statement.
            mysqli_query($link, "INSERT INTO Posts (Thread_ID, Comment, UserName, Time) VALUES ('$thread_ID','$des_first_post2','$user','$datetime');"); // An SQL query inserts the thread id, comment of the first post, username of the creator of the first post and the creation time of the first post into the "Posts" table.
            header("location:view.php"); // The user is redirected to the page displaying the threads.
        }
    } else { // Otherwise, if the user didn't enter a thread title or first post then an error message is displayed telling them they must do this.
        foreach ($incorrect as $error) {
            echo $error ."<br>";
        }
    }
}

?>

<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
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
						<td align="center" width="120" bgcolor="#000080">
						<a href="view.php" class="menu">View topics</a></td>
            <td align="center" width="120" bgcolor="#FFFF99">
						<font color="#000080">Add thread</font></td>
						<td align="center" width="120" bgcolor="#000080">
						<a href="index.php" class="menu">Forum index</a></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
</div>
<br> <!-- A form is outputted in which the user can enter the thread title and first post for a new thread. -->
	<div align="center">
		<table border="0" width="800" cellspacing="0" style="border: 1px solid #000080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" height="300" bordercolor="#000080" id="table3" cellpadding="2">
			<tr>
				<td valign="top">
          <form action="additem.php" method=POST id="addthread">
            <b>Thread Title:</b><br>
            <input type="text" name="title" value=""><br><br>
            <b>First Post:</b><br>
            <textarea style="width:100%; height:250px" form="addthread" name="first_post"></textarea><br><br>
            <input type="submit" value="Submit" name="submit">
          </form></td>
			</tr>
		</table>
	</div>
</body>

</html>