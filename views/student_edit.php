<?php
include_once("../db.php"); // Include the Database class file
include_once("../student.php"); // Include the Student class file
include_once("../student_details.php");
include_once("../town_city.php");
include_once("../province.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch student data by ID from the database
    $db = new Database();
    $student = new Student($db);
    $student_details = new StudentDetails($db);
    $studentData = $student->read($id); // Implement the read method in the Student class
    $studentDetailsData = $student_details->read($id);
    

    if ($studentData) {
        // The student data is retrieved, and you can pre-fill the edit form with this data.
    } else {
        echo "Student not found.";
    }
} else {
    echo "Student ID not provided.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'id' => $_POST['id'],
        'student_number' => $_POST['student_number'],
        'first_name' => $_POST['first_name'],
        'middle_name' => $_POST['middle_name'],
        'last_name' => $_POST['last_name'],
        'gender' => $_POST['gender'],
        'birthday' => $_POST['birthday'],
    ];

    $db = new Database();
    $student = new Student($db);
    $student_id = $student->update($id, $data);
    if (true){

        $studentDetailsData = [
            'id' => $_POST['det_id'],
            'student_id' => $student_id,
            'contact_number' => $_POST['contact_number'],
            'street' => $_POST['street'],
            'town_city' => $_POST['town_city'],
            'province' => $_POST['province'],
            'zip_code' => $_POST['zip_code'],
        ];
        $studentDetails = new StudentDetails($db);

        if ($studentDetails->update($id, $studentDetailsData)) {
            echo "Record inserted successfully.";
        } else {
            echo "Failed to insert the record.";
        }
    }

    // Call the edit method to update the student data
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <title>Edit Student</title>
</head>
<body>
    <!-- Include the header and navbar -->
    <?php include('../templates/header.html'); ?>
    <?php include('../includes/navbar.php'); ?>

    <div class="content">
    <h2>Edit Student Information</h2>
    <form action="" method="post">
        <input type="hidden" name="id" value="<?php echo $studentData['id']; ?>">
        <input type="hidden" name="det_id" value="<?php echo $studentDetailsData['id']; ?>">
        
        <label for="student_number">Student Number:</label>
        <input type="text" name="student_number" id="student_number" value="<?php echo $studentData['student_number']; ?>">
        
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" value="<?php echo $studentData['first_name']; ?>">
        
        <label for="middle_name">Middle Name:</label>
        <input type="text" name= "middle_name" id="middle_name" value="<?php echo $studentData['middle_name']; ?>">
        
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" value="<?php echo $studentData['last_name']; ?>">
        
        <!-- <label for="gender">Gender:</label>
        <input type="text" name="gender" id="gender" value="<?php // echo $studentData['gender']; ?>"> -->
        
        <label for="gender">Gender:</label>
        <select name="gender" id="gender">
            <option value="0" <?php echo ($studentData['gender'] == 0) ? 'selected' : '';?>>Female</option>
            <option value="1" <?php echo ($studentData['gender'] == 1) ? 'selected' : '';?>>Male</option>
        </select>
        
        <label for="birthday">Birthdate:</label>
        <input type="date" name="birthday" id="birthday" value="<?php echo $studentData['birthday']; ?>">

        <label for="student_number">Contact Number:</label>
        <input type="text" name="contact_number" id="contact_number" value="<?php echo $studentDetailsData['contact_number']; ?>">

        <label for="student_number">Street:</label>
        <input type="text" name="street" id="street" value="<?php echo $studentDetailsData['street']; ?>">

        <label for="town_city">Town / City:</label>
        <select name="town_city" id="town_city" required>
            <option value="selected">Select...</option>
        <?php

            $database = new Database();
            $towns = new TownCity($database);
            $results = $towns->getAll();
            // echo print_r($results);
            foreach($results as $result)
            {
                echo '<option value="' . $result['id'] . '">' . $result['name'] . '</option>';
            }
        ?>      
        </select>

        <label for="province">Province:</label>
        <select name="province" id="province" required>
            <option value="selected">Select...</option>
        <?php

            $database = new Database();
            $provinces = new Province($database);
            $results = $provinces->getAll();
            // echo print_r($results);
            foreach($results as $result)
            {
                echo '<option value="' . $result['id'] . '">' . $result['name'] . '</option>';
            }
        ?> 
        </select>
        
        <label for="zip_code">Zip Code</label>
        <input type="text" name="zip_code" id="zip_code" value="<?php echo $studentDetailsData['zip_code']; ?>">
        
        <input type="submit" value="Update">
    </form>
    </div>
    <?php include('../templates/footer.html'); ?>
</body>
</html>
