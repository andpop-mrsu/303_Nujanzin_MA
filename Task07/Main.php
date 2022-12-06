<?php
    include("Utils.php");
    include("DataContext.php");
    include("Repository.php");

    $sqliteConnectionString = Utils::get_settings("appsettings.json", "SqliteConnection");
    $repository = new Repository(new DataContext($sqliteConnectionString));

    $allEmployees = $repository->getAllEmployees();
    
    $mask = "| %-15.30s| %-20.30s| %-20.30s|\n";
    printf($mask, "personnel_id", "first_name", "last_name");
    echo "--------------------------------------------------------------\n";
    foreach($allEmployees as $employee) {
        printf($mask, $employee['personnel_id'], $employee['first_name'], $employee["last_name"]);
    }

    $is_run = true;
    while($is_run) {
        $command = readline("Enter the employee number (to see all info about one) or press enter (to see all info): ");
        if($command == "exit") {
            $is_run = false;
            break;
        } else if (is_numeric($command) && intval($command) > 0) {
            $filtred_users = array_filter($allEmployees,
            function($x) use ($command) {
                return $x['personnel_id'] == $command;
            });
            if (sizeof($filtred_users) > 0) {
                $userJobs = $repository->getAllEmployeeJobsById(intval($command));
                if (sizeof($userJobs) == 0) {
                    echo "This employee has not completed any job yet\n\n";
                    continue;
                }
                Utils::print_table_jobs($userJobs);
            } else {
                echo "Employee with this id not found\n\n";
            }
        } else if ($command == "") {
            $allJobs = $repository->getAllEmployeesJobs();
            Utils::print_table_jobs($allJobs);
        } else {
            echo "Unknown command!\n\n";
        }
    }
?>





