<?php
include_once("../db.php");
try {
    $database = new Database();
    $pdo = $database->getConnection();

    $sql = "SELECT 
        (COUNT(CASE WHEN gender = 1 THEN 1 END) / COUNT(*)) * 100 AS Male_Percentage,
        (COUNT(CASE WHEN gender = 0 THEN 1 END) / COUNT(*)) * 100 AS Female_Percentage 
        FROM students;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    throw $e;
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
        <canvas id="maleFemaleChart"></canvas>
    </div>

    <script>
        const malePercentage = <?php echo json_encode([$result[0]['Male_Percentage']]); ?>;
        const femalePercentage = <?php echo json_encode([$result[0]['Female_Percentage']]); ?>;

        const ctx = document.getElementById('maleFemaleChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Gender"],
                datasets: [{
                    label: '% of Male',
                    data: malePercentage,
                    borderWidth: 1,
                    backgroundColor: 'rgb(117, 14, 33)'
                },
                {
                    label: '% of Female',
                    data: femalePercentage,
                    borderWidth: 1,
                    backgroundColor: 'rgb(190, 215, 84)'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 60,
                        ticks: {
                                stepSize: 5
                            }
                    }
                }
            }
        });
    </script>

    <!-- Include the footer -->
    <?php include('../templates/footer.html'); ?>
</body>
</html>
