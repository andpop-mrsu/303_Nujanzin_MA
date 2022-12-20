<?php
require_once("../entities/Employee.php");
require_once("../data/Repository.php");
require_once("../data/DataContext.php");
require_once("../entities/Job.php");

$repository = new Repository(new DataContext("sqlite:../data/washer.db"));
$serviceTypes = $repository->getServiceTypes();
$job = new Job();

if (isset($_POST["serviceType"]) && isset($_POST["date"]) && isset($_POST["time"]) && isset($_POST["boxNumber"])) {
    $job->service_id = $_POST["serviceType"];
    $job->box_number = $_POST["boxNumber"];
    $job->schedule_for_date = $_POST["date"];
    $job->schedule_for_time = $_POST["time"];

    $res = $repository->addJob($job);

    if ($res == false) {
        ?>
<html>
    <body>
        <div>No masters available at this time</div>
        <div>
            <a href="book-car-wash-page.php">
                Return to book
            </a>
        </div>
    </body>
</html> <?php
    } else {
        header("Location: index.php");

    }
}

?>
