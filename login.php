<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn</title>
</head>
<body>

<?php
//Initialize Varibles
$uname=$password="";

$errUname="";
$errPass="";

//Database connection details
$servername="localhost";
$username="root"; 
$password="";
$dbname="t1";

//Create connection to the database 
$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
    die("Connection faield: ".$conn->connect_error);
}
echo "Connection Sucessfull";

$valid=true;

if($_SERVER["REQUEST_METHOD"]=="POST"){
if(empty($_POST["uname"])){
$errUname="Name field required";
$valid=false;
}
else{
    $uname=$_POST["uname"];
}
if(empty($_POST["password"])){
    $errPass="Password required";
    $valid=false;
}
else{
$password=$_POST["password"];
}
}

?>

<form method="POST">
        Username:<input type="text" name="uname">
        <span class="error">*<?php echo $errUname; ?></span>
<br><br>
        Password:<input type="password" name="password">
<span class ="error">*<?php echo $errPass; ?></span>
<br><br>
<input type="submit" value="LogIn">   
</form>

<?php

    if ($valid) {
        // Use a prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM login WHERE uname = ? AND password = ?");
        $stmt->bind_param("ss", $uname, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            echo "Login success";
        } else {
            echo "Login failed";
        }

        $stmt->close();
        $conn->close();
        exit();
    }

?>
   
</body>
</html>