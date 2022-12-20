<html>
    <head>
        <?php
            require_once("../data/DataContext.php");
            require_once("../data/Repository.php");
            $repository = new Repository(new DataContext("sqlite:../data/washer.db"));
        ?>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="css/home.css">
    </head>
    <body>
        <div class="page">
            <div class="header">
                <a href="index.php">Return to menu</a>
            </div>
            <div class="table-container">
                <div class="header">
                    <h1>All employees</h1>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>first name</th>
                        <th>last name</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $allEmployees = $repository->getAllEmployees();
                    foreach($allEmployees as $employee) {
                        ?>
                        <tr>
                            <td><?=$employee["personnel_id"]?></td>
                            <td><?=$employee["first_name"]?></td>
                            <td><?=$employee["last_name"]?></td>
                            <td>
                                <div class="dropdown">
                                    <img class="dropbtn" src="svg/dropdown.svg" width="16" height="16">
                                    <div class="dropdown-content">
                                        <form action="delete-employee.php" method="POST">
                                        <a class="btn" href="#"
                                           onclick="window.location.href = 'employee-edit-page.php?id='+<?=$employee["personnel_id"]?>">
                                            Edit
                                        </a>
                                        <a class="btn" href="#"
                                           onclick="window.location.href = 'work-schedule-page.php?id='+<?=$employee["personnel_id"]?>">
                                            Work schedule
                                        </a>
                                        <a class="btn" href="#"
                                           onclick="window.location.href = 'completed-works.php?id='+<?=$employee["personnel_id"]?>">
                                            Completed works
                                        </a>
                                        <input type="hidden" name="id" value="<?=$employee["personnel_id"]?>"/>
                                        <input name="deleteEmployee" type="submit" value="Delete" class="btn"/>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="button-group">
                <a href="employee-edit-page.php" class="btn btn-blue">
                    Add employee
                </a>
            </div>
        </div>
    </body>
</html>
