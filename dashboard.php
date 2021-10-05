<?php
require 'util/connection.php';

session_start();
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please log in')</script>";
    echo "<script>location.href='index.php'</script>";
}
$username = $_SESSION['username'];
$month = mysqli_query($connect, "SELECT DATE_FORMAT(date, \"%M\") AS month FROM log WHERE user='$username' GROUP BY month ORDER BY date ASC");
$income = mysqli_query($connect, "SELECT SUM(value) as total FROM log WHERE type='income' AND user='$username' GROUP BY DATE_FORMAT(date, \"%M\") ORDER BY date ASC");
$outcome = mysqli_query($connect, "SELECT SUM(value) as total FROM log WHERE type='outcome' AND user='$username' GROUP BY DATE_FORMAT(date, \"%M\") ORDER BY date ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <title>Dashboard</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="dashboard.php">Buku Kas</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="transaction.php">Transaction</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="category.php">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Sign out</a>
                </li>
            </ul>
            <span class="navbar-text">
                <?php echo $_SESSION['username'] ?>
            </span>
        </div>
    </nav>
    <div class="container" style="max-width: 1440px">
        <div class="row">
            <div class="col-sm-12">
                <h4>Welcome, <?php echo $_SESSION['name'] ?></h4>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-sm-6">
                <h5><i class="bi bi-box-arrow-in-left"></i> Total Income</h5>
                <h5>
                    <?php
                    $totalIncome = mysqli_query($connect, "SELECT SUM(value) as total FROM log WHERE type='income' AND user='$username'");
                    while ($data = mysqli_fetch_array($totalIncome)) {
                        echo number_format($data['total'], 2, ".", ",");
                        $income_value = $data['total'];
                    };
                    ?>
                </h5>
            </div>
            <div class="col-sm-6">
                <h5><i class="bi bi-box-arrow-right"></i> Total Outcome</h5>
                <h5>
                    <?php
                    $totalOutcome = mysqli_query($connect, "SELECT SUM(value) as total FROM log WHERE type='outcome' AND user='$username'");
                    while ($data = mysqli_fetch_array($totalOutcome)) {
                        echo number_format($data['total'], 2, ".", ",");
                        $outcome_value = $data['total'];
                    };
                    ?>
                </h5>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-sm-12">
                <h5><i class="bi bi-wallet"></i> Balance</h5>
                <h5>
                    <?php
                    $balance = $income_value - $outcome_value;
                    echo number_format($balance, 2, ".", ",");
                    ?>
                </h5>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-sm-12">
                <br>
                <h4>Transaction Graph</h4>
                <canvas id="transChart"></canvas>
            </div>
        </div>
    </div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
    var ctx = document.getElementById('transChart');
    var chart = new Chart(ctx, {
        type: 'line',

        data: {
            labels: [<?php while ($m = mysqli_fetch_array($month)) {
                            echo '"' . $m['month'] . '",';
                        } ?>],
            datasets: [{
                label: 'Income',
                borderColor: 'rgb(45, 189, 76)',
                backgroundColor: 'rgb(45, 189, 76)',
                fill: false,
                data: [<?php while ($v = mysqli_fetch_array($income)) {
                            echo '"' . $v['total'] . '",';
                        } ?>],
                borderWidth: 3
            }, {
                label: 'Outcome',
                borderColor: 'rgb(227, 18, 49)',
                backgroundColor: 'rgb(227, 18, 49)',
                fill: false,
                data: [<?php while ($v = mysqli_fetch_array($outcome)) {
                            echo '"' . $v['total'] . '",';
                        } ?>],
                borderWidth: 3
            }]
        },

        options: {}
    });
</script>

</html>