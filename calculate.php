<?php
function calculateElectricityRates($voltage, $current, $rate)
{
    $results = [];

    for ($hour = 1; $hour <= 24; $hour++) {
        $power = $voltage * $current;
        $energy = $power / 1000; // Convert to kWh
        $total = $energy * $rate / 100;

        $results[] = [
            "hour" => $hour,
            "energy" => $energy,
            "total" => $total
        ];

        // Increment the current for the next iteration
        $current += 0.1;
    }

    return $results;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voltage = floatval($_POST["voltage"]);
    $current = floatval($_POST["current"]);
    $rate = floatval($_POST["rate"]);

    $results = calculateElectricityRates($voltage, $current, $rate);

    // Calculate POWER and RATE outside the loop
    $power = $voltage * $current;
    $formattedPower = number_format($power, 5) . 'kW';
    $formattedRate = 'RM' . number_format($rate / 100, 3);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Electricity Calculation Results</title>
</head>
<body>
    <div class="container">
        <h2>Electricity Calculation Results</h2>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <p>POWER: <?= $formattedPower ?></p>
            <p>RATE: <?= $formattedRate ?></p>

            <table class="table">
                <thead>
                    <tr>
                        <th>Hour</th>
                        <th>Energy (kWh)</th>
                        <th>Total (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result): ?>
                        <tr>
                            <td><?= $result["hour"] ?></td>
                            <td><?= number_format($result["energy"], 5) ?></td>
                            <td><?= number_format($result["total"], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
