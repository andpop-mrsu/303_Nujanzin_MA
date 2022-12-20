<?php
require_once("../entities/Employee.php");
require_once("../data/Repository.php");
require_once("../data/DataContext.php");
require_once("../entities/Job.php");

$repository = new Repository(new DataContext("sqlite:../data/washer.db"));
$serviceTypes = $repository->getServiceTypes();
?>

<html>
    <head>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <div class="page">
            <h1>Book a car wash</h1>
            <div class="form-container">
                <form class="form" method="post" action="book-car-wash.php">

                    <div>Service Type</div>
                    <select name="serviceType">
                        <?php
                            foreach($serviceTypes as $serviceType) {
                                ?><option value="<?=$serviceType["id"]?>"><?=$serviceType["name"]?></option><?php
                        }
                        ?>
                    </select>

                    <div>Box</div>
                    <select name="boxNumber">
                        <?php
                        foreach(range(1,4) as $boxNumber) {
                            ?><option value="<?=$boxNumber?>"><?=$boxNumber?></option><?php
                        }
                        ?>
                    </select>


                    <div class="field">
                        <div class="time-range">
                            <div class="time-field">
                                <label for="date">Date</label>
                                <input id="date" name="date" class="form-control" type="date" required/>
                            </div>
                            <div class="horizontal-divider"></div>
                            <div class="time-field">
                                <label for="time">Time</label>
                                <input id="time" name="time" class="form-control" type="time" required/>
                            </div>
                        </div>
                    </div>

                    <div class="vertical-divider"></div>
                    <div class="form-actions">
                        <div class="button-group">
                            <button class="btn" onclick="window.location.href = 'index.php'">
                                Cancel
                            </button>
                            <input class="btn btn-blue" type="submit" value="Book"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>