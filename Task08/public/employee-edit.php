<?php
require_once("../entities/Employee.php");
require_once("../data/Repository.php");
require_once("../data/DataContext.php");

$repository = new Repository(new DataContext("sqlite:../data/washer.db"));

if (isset($_POST["firstName"]) && isset($_POST["lastName"])) {
    if (isset($_POST["id"])) {
        $emp = new Employee();
        $emp->first_name = $_POST["firstName"];
        $emp->last_name = $_POST["lastName"];

        $repository->updateEmployee($emp, intval($_POST["id"]));
    } else {
        $emp = new Employee();
        $emp->first_name = $_POST["firstName"];
        $emp->last_name = $_POST["lastName"];
        $emp->beginning_of_work = "00:00";
        $emp->end_of_work = "00:00";

        $repository->addEmployee($emp);
    }

    header("Location:home.php");
}
?>
