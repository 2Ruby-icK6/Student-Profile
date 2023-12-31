<!-- php -S 127.0.0.1:8000  -->

<?php
include_once("db.php");
include_once("student.php");

$db = new Database();
$connection = $db->getConnection();
$student = new Student($db);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <!-- Include the header -->
    <?php include('templates/header.html'); ?>
    <?php include('includes/navbar.php'); ?>


    <div class="content" id="content">
        <h1>Welocome to Student Profile</h1>
        <h3>Rovick Anthony Pasamonte</h3>
        <h3>Jolfi Asaph Delcoro</h3>
    </div>

        <!-- Include the footer -->
    <?php include('templates/footer.html'); ?>
</body>
</html>
