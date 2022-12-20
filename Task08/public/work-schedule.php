<?php
if (isset($_POST["begin"]) && isset($_POST["end"])) {


    require_once("../entities/Employee.php");
    require_once("../data/Repository.php");
    require_once("../data/DataContext.php");

    $repository = new Repository(new DataContext("sqlite:../data/washer.db"));

    $emp = new Employee();

    $emp->beginning_of_work = $_POST["begin"];
    $emp->end_of_work = $_POST["end"];


    $repository->updateWorkSchedule($emp, intval($_POST["id"]));

    header("Location:home.php");
}?>
