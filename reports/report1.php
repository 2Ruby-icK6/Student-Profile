<?php
include_once("../db.php");

try {
    $database = new Database();
    $pdo = $database->getConnection();

    $sql = "SELECT town_city.name as 'Town City', COUNT(student_details.student_id) as student_count FROM student_details
            INNER JOIN town_city ON student_details.town_city = town_city.id
            WHERE town_city.name <> '' GROUP BY town_city.name ORDER BY student_count DESC LIMIT 10";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    throw $e;
}

if (count($result) > 0) {
    $student_count = array();
    $town_barchart = array();
    foreach ($result as $row) {
        $student_count[] = $row['student_count'];
        $town_barchart[] = $row['Town City'];
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
        const town_barchart = <?php echo json_encode($town_barchart); ?>;
        const order_count = <?php echo json_encode($student_count); ?>;

        const data4 ={
            labels: town_barchart,
            datasets: [{
            label: 'Top 10 Town City',
            data: order_count,
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
        // <!-- config block -->
        const config4 = {
            type: 'bar',
            data: data4,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };
        // <!-- render block -->
        const myChartTopTen = new Chart(
        document.getElementById('myChart'),
        config4
        );
    </script>

    <!-- Include the footer -->
    <?php include('../templates/footer.html'); ?>
</body>
</html>
