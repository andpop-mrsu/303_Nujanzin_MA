<?php
require_once("../entities/Employee.php");
require_once("../data/Repository.php");
require_once("../data/DataContext.php");

$repository = new Repository(new DataContext("sqlite:../data/washer.db"));
$personnelId = intval($_GET['id']);
date_default_timezone_set("Europe/Moscow");

?>
<html>
    <head>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="css/home.css">
    </head>
    <body>
    <div class="page">
        <div class="header">
            <a href="home.php">Return to employees</a>
        </div>
        <div class="table-container">
            <div class="header">
                <h1>Completed works</h1>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>id</th>
                    <th>box number</th>
                    <th>scheduled for</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $jobs = $repository->getAllEmployeeJobsById($personnelId);
                foreach($jobs as $job) {
                    if ($job["scheduled_for"] <= date('d/m/Y h:i a', time()))
                    ?>
                    <tr>
                        <td><?=$job["id"]?></td>
                        <td><?=$job["box_number"]?></td>
                        <td><?=$job["scheduled_for"]?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    </body>
</html>
