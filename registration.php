<?php
// Initialized variables
$name = $email = $id = $gender = $comment = "";
$errname = $erremail = $errid = $errgender = "";

$servername = "localhost"; // Replace with your server name or IP address
$username = "root";        // Replace with your MySQL username
$password = "";            // Replace with your MySQL password
$dbname = "test";          // Replace with your database name
$valid = true;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form handling with validation check
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valid = true; // Ensure this is reset for each POST

    if (empty($_POST["name"])) {
        $errname = "Name field required";
        $valid = false;
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $errname = "Invalid format";
            $valid = false;
        }
    }

    if (empty($_POST["email"])) {
        $erremail = "Email field required";
        $valid = false;
    } else {
        $email = test_input($_POST["email"]);
        if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
            $erremail = "Invalid email format";
            $valid = false;
        }
    }

    if (empty($_POST["id"])) {
        $errid = "ID field required";
        $valid = false;
    } else {
        $id = test_input($_POST["id"]);
        if (!preg_match("/^[0-9]{5,10}$/", $id)) {
            $errid = "Invalid ID format";
            $valid = false;
        }
        
    }

    if (empty($_POST["gender"])) {
        $errgender = "Gender field required";
        $valid = false;
    } else {
        $gender = test_input($_POST["gender"]);
    }

    $comment = test_input($_POST["comment"]);

    // If all fields are valid, insert data into the database field
    if ($valid) {
        $stmt = $conn->prepare("INSERT INTO php3 (id, email, gender, comment, name) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssss", $id, $email, $gender, $comment, $name);

            if ($stmt->execute()) {
                echo "Data stored successfully!";
            } else {
                echo "Error executing statement: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }
}

// Close connection when done
$conn->close();

// Function to sanitize or secure user input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission</title>
    <style>
        .error { color: red; }
    </style>
</head>
<body>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Name: <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
    <span class="error">*<?php echo $errname; ?></span>
    <br><br>
    Email: <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
    <span class="error">*<?php echo $erremail; ?></span>
    <br><br>
    ID: <input type="text" name="id" value="<?php echo htmlspecialchars($id); ?>">
    <span class="error">*<?php echo $errid; ?></span>
    <br><br>
    Comment: <textarea name="comment" rows="5" cols="40"><?php echo htmlspecialchars($comment); ?></textarea>
    <br><br>
    Gender:
    <input type="radio" name="gender" value="female" <?php if ($gender == "female") echo "checked"; ?>>Female
    <input type="radio" name="gender" value="male" <?php if ($gender == "male") echo "checked"; ?>>Male
    <input type="radio" name="gender" value="other" <?php if ($gender == "other") echo "checked"; ?>>Other
    <span class="error">*<?php echo $errgender; ?></span>
    <br><br>
    <input type="submit" name="submit" value="Submit">
    <input type="reset" name="reset" value="Reset">
</form>

<h2>Your Input:</h2>
<?php 
echo "Name: " . $name . "<br>";
echo "Email: " . $email . "<br>";
echo "ID: " . $id . "<br>";
echo "Comment: " . $comment . "<br>";
echo "Gender: " . $gender . "<br>";
?>

</body>
</html>
