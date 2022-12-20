<?php
require_once("../data/DataContext.php");
require_once("../data/Repository.php");
$repository = new Repository(new DataContext("sqlite:../data/washer.db"));
$repository->removeEmployee($_POST["id"]);
header("Location: home.php");
?>