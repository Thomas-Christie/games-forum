<?php
// Session starts so session variables can be declared and used and connection to database is initialised.
session_start();
require_once('connect.php');
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
						<font color="#000080">View topics</font></td>
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
		<table border="1" width="800" cellspacing="0" style="border: 1px solid #000080; padding-left: 10px; padding-right: 10px; padding-top: 10px; padding-bottom: 10px" height="300" bordercolor="#000080" id="table3" cellpadding="2">
          <tr><th>Thread Title</th><th>Creator</th><th>Creation Time</th></tr> <!-- A table is outputted to display the threads, with "Thread Title", "Creator" and "Creation Time" as the headings. -->
          <?php 
          $threads = mysqli_query($link, "SELECT * FROM Threads ORDER BY Creation_Time DESC;"); //An SQL query obtains all the threads from the database.
          while ($row = mysqli_fetch_assoc($threads)) { //A while loop is used to output all the records (threads) from the Threads table.
              $thread_id = $row['Thread_ID']; //The thread id of each thread is extracted from the SQL query.
              $user = $row['UserName']; //The usernames of the creators of each thread are extracted from the SQL query.
              $time = $row['Creation_Time']; //The creation time of each thread is extracted from the SQL query.
              $creation_time = date("d-m-Y-h-i-s", $time); // As the creation time of each thread is stored in the database as Unix time, the PHP "date" function is used to convert this into the human readable format of date and time display.
              echo "<tr><td><a href='view_thread.php?thread_id=$thread_id'>".$row['Title']."</a></td><td>".$user."</td><td>".$creation_time."</td></tr>";
             // The details of each thread are outputted on a new row of the table, and the thread title is hyperlinked to the page displaying the posts for that thread, with "?thread_id=$thread_id" appended to the web address
             // The thread id can then be obtained from the web address suing the "get" method, which is then user on the "view_thread" page to output the posts.
          }
          ?>
		</table>
	</div>
</body>
</html>