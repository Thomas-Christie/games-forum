<?php
// Session starts so session variables can be declared and used and connection to database is initialised.
session_start();
require_once('connect.php');

// Checks if the user has pressed the logout button (which adds ?logout=true to the web address).
// If the user has pressed logout then the session variable "user" returns to "Guest".
// This signifies that the user has logged out, preventing them to access certain functions on the website.
if (isset($_GET['logout'])) {
    $logout = $_GET['logout'];
    if ($logout==true) {
        $_SESSION["user"] = "Guest";
    }
}

// If the session variable "user" has already been declared then the local variable "username" is assigned to the username for later use.
// Otherwise, the session variable "user" is assigned to "Guest", signifying that they haven't logged in yet.
if (isset($_SESSION["user"])) {
    $username = $_SESSION["user"];
} else {
    $_SESSION["user"] = "Guest";
}

// The website checks if the user has submitted details to sign up with a new account on the "signup" form.
// An array, "wrong", is initialised which will store any errors in the details that the user has entered.
if (isset($_POST["signup"])) {
    $wrong = array();
    
    // The program checks if the user has inputted their desired username and, if not, appends an error message to the "wrong" array.
    // If they have inputted their desired username, the program checks if it contains a speech mark and escapes it to prevent SQL injection.
    // The desired username is then assigned to the variable $desusername2.
    if (strlen($_POST["desusername"]) > 0) {
        $desusername = $_POST["desusername"];
        $desusername2 = str_replace("'","''",$desusername);
    } else {
        array_push($wrong, "Please enter your desired username.");
    }
 
    // The program validates the user's desired password using exactly the same method as for the desired username. 
    if (strlen($_POST["despassword"]) > 0) {
        $despassword = $_POST["despassword"];
        $despassword2 = str_replace("'","''",$despassword);
    } else {
        array_push($wrong, "Please enter your desired password.");
    }
  
    // If the user has entered a username and password then the program uses SQL to check if an account already exists with the given username.
    if (empty($wrong)) {
        $results = mysqli_query($link, "SELECT * FROM Users WHERE UserName = '$desusername2';");
        $accounts = mysqli_num_rows($results);
        if ($accounts > 0) { //If an account already exists with a given username the user is told to input a different one.
            echo 'Error: Account already exists with the desired username, please try a different username.'; 
        } else { //If an account doesn't exist with the desired username then the user's username and password are added to the "Users" table using SQL.
            mysqli_query($link, "INSERT INTO Users (UserName,Password) VALUES ('$desusername2','$despassword2');");
            $_SESSION["user"] = $desusername; // The session variable "user" is assigned to the user's username, meaning they're regarded as logged in and the website will now output "Viewing as 'user'" rather than guest.
            echo 'Your account has been created!'; // The user is told that their account has been created.
        }
    } else { // If the user hadn't inputted a desired username and/or password then they're given an error message telling them they must input the appropriate value.
        foreach ($wrong as $incorrect) {
            echo $incorrect ."<br>";
        }
    }
  
  // The website checks if the user has submitted details to login on the "login" form.
  // An array, "errors", is initialised which will store any errors in the details that the user has entered.
} elseif (isset($_POST["login"])) {
    $errors = array();
    
    // The program checks if the user has inputted their username and, if not, appends an error message to the "errors" array.
    // If they have inputted their username, the program checks if it contains a speech mark and escapes it to prevent SQL injection.
    // The username is then assigned to the variable $username2.
    if (strlen($_POST["username"]) > 0) {
        $username = $_POST["username"];
        $username2 = str_replace("'","''",$username);
    } else {
        array_push($errors, "Please enter your username.");
    }
    
    // The program validates the user's password using exactly the same method as for the username. 
    if (strlen($_POST["password"]) > 0) {
        $password = $_POST["password"];
        $password2 = str_replace("'","''",$password);
    } else {
        array_push($errors, "Please enter your password.");
    }
   
    //If the user entered a username and password an SQL query checks if there is a record in the table with the username and password entered.
    if (empty($errors)) {
        $data = mysqli_query($link, "SELECT * FROM Users WHERE UserName = '$username2' AND Password = '$password2';");
        $number = mysqli_num_rows($data);
        if ($number == 0) { //If there wasn't a record with those login credentials the user is told that they inputted the wrong username and/or password and aren't regarded as logged on.
            echo 'Error: Incorrect Username and/or Password, please try logging in again.';
        } else {
            $_SESSION["user"] = $username; //If the user has entered the correct username and password then the session variable "user" is assigned to their username, signifying they have logged on.
        }
    } else { // If the user hadn't inputted their username and/or password then they're given an error message telling them they must input the appropriate value.
      foreach ($errors as $error) {
        echo $error."<br>";
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
						<div class="Guest">Viewing as <?php echo $_SESSION["user"] ; ?><br> <!--The website outputs "Viewing as 'user'", meaning that if they're logged in then it outputs their username, otherwise ouputs "Guest" -->
            <?php if ($_SESSION["user"]=="Guest") {
    ?>
            <a href="index.php"> (Login) </a> <!-- If the user isn't logged in then next to the welcome message a "login" hyperlink will appear, linking to the homepage where they can sign in  -->
            <?php 
} else {
    ?>
            <a href="index.php?logout=true"> (Logout) </a> <!-- If the user is logged in a "logout" hyperlink will appear next to the welcome message, linking to the homepage with "?logout=true" appended to the web address which logs them out  -->
            <?php 
} ?>
            </div></td>
						<td width="110">
						<p align="right">
						<img border="0" src="../images/soc.png" width="100" height="42"></td>
					</tr>
				</table>
				</td>
				<td width="500" align="right" style="border-right: 1px solid #000080; border-bottom: 1px solid #000080">
				<table border="0" width="480" cellpadding="0" id="table2" height="40">
					<tr>
						<td align="center" width="120" bgcolor="#000080">
						<a href="view.php" class="menu">View topics</a></td>
						<td align="center" width="120" bgcolor="#000080">
						<a href="additem.php" class="menu">Add thread</a></td>
						<td align="center" width="120" bgcolor="#FFFF99">
						<font color="#000080">Forum index</font></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
</div>
<br>
	<div align="center">
		<table border="0" width="800" cellspacing="0" style="border: 1px solid #000080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" height="300" bordercolor="#000080" id="table3" cellpadding="2">
			<tr>
				<td valign="top">GameZsoc is <b>the</b> forum for computer 
				gamers and console users on the web. Post tips or pleas for 
				help, reviews, patches and hardware mods. We are an active 
				community of gamers and love our hobby.<p><font color="#FF0000">
				<b>Forum Ettiquette.</b></font><br>
				Please do not hide behind multiple accounts and keep your posts 
				family friendly. Try to keep your posts relevant to the topic at 
				hand. Remember that the forum is not for advertising business 
				sales although members are able to post private ads for the sale 
				of their own equipment.</p>
				<p>Should you read a post that is offensive please refer it to 
          moderators@gamezsoc.org.uk.</p></td>
			</tr>
		</table>
	</div>
<br> <!-- If the user isn't logged on then two forms will be displayed, one to sign up and oine to log in, and once the user has logged in then these forms will no longer be displayed.  -->
  <?php if ($_SESSION["user"]=="Guest") {
    ?>
  <div align="center">
    <table width = "800" style="border: 1px solid #000080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
      <tr>
        <td><b>Signup</b>
          <form action="index.php" method=POST>
            Username:<br>
            <input type="text" name="desusername" value=""><br>
            Password:<br>
            <input type="password" name="despassword" value=""><br><br>
            <input type="submit" value="Submit" name="signup">
          </form>
        </td>
        <td><b>Login</b>
          <form action="index.php" method=POST>
            Username:<br>
            <input type="text" name="username" value=""><br>
            Password:<br>
            <input type="password" name="password" value=""><br><br>
            <input type="submit" value="Login" name="login">
          </form>
        </td>
     </tr>
    </table>
  </div>
  <?php 
} ?>
</body>
</html>