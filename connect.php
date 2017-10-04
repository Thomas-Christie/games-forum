<!-- Initialises connection with the database located on PHPMyAdmin and returns an error message if the connection isn't established.   -->
<?php
$link = mysqli_connect("localhost", "root", "Caistor01", "Computer_Games_Forum");

// Check connection.
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
?>.