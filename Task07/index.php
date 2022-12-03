<!DOCTYPE html>
<html>
    <head>
        <?php
            include("Utils.php");
            include("DataContext.php");
            include("Repository.php");
            $sqliteConnectionString = Utils::get_settings("appsettings.json", "SqliteConnection");
            $repository = new Repository(new DataContext($sqliteConnectionString));
        ?>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="main-container">
            <div class="table-container">
                <div class="table-name">All employees</div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>first name</th>
                        <th>last name</th>
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
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div>
                <form action="" method="post">
                    <select name="EmployeesId">
                        <option value="allJobs">all jobs</option>
                        <?php
                        foreach($allEmployees as $employee) {
                            ?>
                            <option value="<?=$employee["personnel_id"]?>"><?=$employee["personnel_id"]?></option>
                        <?php } ?>
                    </select>
                    <input type="submit" name="submit" value="Choose id">
                </form>
            </div>
            <div>
                <div class="jobs-table-container">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>first name</th>
                            <th>last name</th>
                            <th>job name</th>
                            <th>date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            if($_POST["EmployeesId"] == "allJobs") {
                                $selectedJobs = $repository->getAllEmployeesJobs();
                            } else {
                                $selectedJobs = $repository->getAllEmployeeJobsById(intval($_POST["EmployeesId"]));
                            }
                            foreach($selectedJobs as $employee) {
                            ?>
                            <tr>
                                <td><?=$employee["personnel_id"]?></td>
                                <td><?=$employee["first_name"]?></td>
                                <td><?=$employee["last_name"]?></td>
                                <td><?=$employee["name"]?></td>
                                <td><?=$employee["scheduled_for"]?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
            </div>
        </div>
    </body>
</html>