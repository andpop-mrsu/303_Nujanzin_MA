<?php
require_once("../entities/Employee.php");
require_once("../data/Repository.php");
require_once("../data/DataContext.php");

$repository = new Repository(new DataContext("sqlite:../data/washer.db"));
if (isset($_GET['id'])) {
    $personnelId = intval($_GET['id']);
    $currentEmp = $repository->getEmployeeById($personnelId)[0];
    $currentFirstName = $currentEmp["first_name"];
    $currentLastName = $currentEmp["last_name"];
} else {
    $currentFirstName = "";
    $currentLastName = "";
}

?>
<html>
    <head>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <div class="page">
            <?php
                if (isset($_GET['id'])) {
                    ?><h1>Edit Employee</h1><?php
                } else {
                    ?><h1>Add Employee</h1><?php
                }
            ?>
            <div class="form-container">
                <form class="form" method="post" action="employee-edit.php">

                    <?php
                        if (isset($_GET['id'])) { ?>
                            <input type="hidden" name="id" value="<?=$personnelId?>"/>
                        <?php }
                    ?>

                    <div class="field">
                        <label for="firstName">First Name</label>
                        <input id="firstName" name="firstName" class="form-control" type="text" required value="<?=$currentFirstName?>"/>
                    </div>

                    <div class="field">
                        <label for="lastName">Last Name</label>
                        <input id="lastName" name="lastName" class="form-control" type="text" required value="<?=$currentLastName?>"/>
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
