<?php
require_once("../entities/Employee.php");
require_once("../data/Repository.php");
require_once("../data/DataContext.php");

$repository = new Repository(new DataContext("sqlite:../data/washer.db"));
$personnelId = intval($_GET['id']);
$currentEmp = $repository->getEmployeeById($personnelId)[0];

?>
<html>
    <head>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
    <div class="page">
        <h1>Edit work schedule</h1>
        <div class="form-container">
            <form class="form" method="post" action="work-schedule.php">
                <input type="hidden" name="id" value="<?=$personnelId?>"/>
                <div class="field">
                    <div class="time-range">
                        <div class="time-field">
                            <input name="begin" type="time" class="form-control" value="<?=$currentEmp["beginning_of_work"]?>"/>
                        </div>
                        <div class="time-field">
                            <input name="end" type="time" class="form-control" value="<?=$currentEmp["end_of_work"]?>"/>
                        </div>
                    </div>
                </div>

                <div class="vertical-divider"></div>
                <div class="form-actions">
                    <div class="button-group">
                        <button class="btn" onclick="window.location.href = 'home.php'">
                            Cancel
                        </button>
                        <input class="btn btn-blue" type="submit" value="Save"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </body>
</html>