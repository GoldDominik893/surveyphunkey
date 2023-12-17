<?php
session_start();
if ($_SESSION['logged_in'] == true) {
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../styles/main.css">
<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body class="loginbg-color">
<ul>
  <li><a href="../">LibreMonkey</a></li>
  <?php 
  if ($_SESSION['logged_in'] == true) { ?>
   <li style="float:right"><div class="currentuser"><?php echo $_SESSION['logged_in_user']; ?></div></li>
   <li style="float:right"><a href="../logout.php">Logout</a></li>
  <?php } ?>
</ul>


<div class="loginbg">
<h1 class="center">Client Area</h1>
    <div class="center-container2 whitebg">
        <a class="button-form" style="color: black;" href="createform.php">Create a form</a>
        <h1 class="no-margin">All of your forms:</h1>




            <?php
            include('../config.php');
            $connection = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
            if ($connection->connect_error) {
                die("Connection failed: " . $connection->connect_error);
            }
            $targetUsername = $_SESSION['logged_in_user'];
            $sql = "SELECT form_id, creator_username, title, description, creation_date, last_modified_date, status, form_url, response_count FROM forms WHERE creator_username = ?";
            $stmt = $connection->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $targetUsername);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<br>";
                        echo "<b>" . $row['title'] . "</b><br>";
                        echo "Form ID: " . $row['form_id'] . "<br>";
                        echo "Creator Username: " . $row['creator_username'] . "<br>";
                        echo "Description: " . nl2br($row['description']) . "<br>";
                        echo "Created: " . $row['creation_date'] . "<br>";
                        echo "Last Modified: " . $row['last_modified_date'] . "<br>";
                        echo "Status: " . $row['status'] . "<br>";
                        echo "form/?id=" . $row['form_id'] . "<br>";
                        echo $row['response_count'] . " Responses <br>";
                        echo '<a style="color: black;" href="edit_form.php?form_id=' . $row['form_id'] . '">Edit form</a><br>';
                    }
                } else {
                    echo '<br><p class="no-margin">You don\'t have any forms yet! <a href="createform.php" style="color: black;">Create one</a></p>';
                }
                $stmt->close();
            } else {
                echo "Error preparing the SQL statement: " . $connection->error;
            }
            $connection->close();
            ?>




        
    </div><br>
</div>
</body>
</html>
<?php
} else { 
    header('Location: ../');
}
?>