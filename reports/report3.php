<?php
include_once("../db.php");

try {
    $database = new Database();
    $pdo = $database->getConnection();

    $sql = "SELECT birthday as Birthday, count(student_number) as students from students where birthday between '2000-01-01' and '2005-12-31' 
        group by birthday order by birthday limit 50";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    throw $e;
}

if (count($result) > 0) {
    $student_count = array();
    $birthday_linechart = array();
    foreach ($result as $row) {
        $student_count[] = $row['students'];
        $birthday_linechart[] = $row['Birthday'];
    }
} else {
    echo "No records matching your query were found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Include the header -->
    <?php include('../templates/header.html'); ?>
    <?php include('../includes/navbar.php'); ?>

    <div class="graph">
        <canvas id="myChart"></canvas>
    </div>

    <script>
        const birthday_linechart = <?php echo json_encode($birthday_linechart); ?>;
        const student_count = <?php echo json_encode($student_count); ?>;

        const data4 = {
            labels: birthday_linechart,
            datasets: [{
                label: 'Student Count',
                data: student_count,
                backgroundColor: [
                    'rgba(255, 99, 132)',
                    'rgba(54, 162, 235)',
                    'rgba(255, 206, 86)',
                    'rgba(75, 192, 192)',
                    'rgba(153, 102, 255)',
                    'rgba(255, 159, 64)'
                ],
                borderColor: 'rgb(0, 0, 0, 1)',
                borderWidth: 1
            }]
        };

        const config4 = {
            type: 'line',
            data: data4,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const myChart = new Chart(
            document.getElementById('myChart'),
            config4
        );
    </script>


    <!-- Include the footer -->
    <?php include('../templates/footer.html'); ?>
</body>
</html>
