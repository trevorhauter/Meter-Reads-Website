<?php 

     session_start();

    if (isset($_GET['restart'])){
        session_destroy();
        session_start();
    }

    include "functions.php";

    if (!isset($_SESSION['monthDiff'])){
        $_SESSION['monthDiff'] = 0;
    }

    if (!isset($_GET['page'])){
        $_GET['page'] = 'meterreads';
    }

    connect();
    readDB();
    prevMonth();

    if(isset($_POST['previous'])){
        subtractMonth();
        prevMonth();
    }

    if(isset($_POST['next'])){
        addMonth();
        nextMonth();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RealMTRX</title>

    <?php include "refs.php"; ?>
    
</head>
<body>
   
<?php include "header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <center>
            <div class="card pageDescription">
                <div class="card-body">
                    <div class="panel-body"><h2><?php showDate(); ?> Meter Reads: Green Acres</h2></div>
                </div>
            </div>
        </center>
    </div>
        
    
    <div class="row">
       <div class="col-lg-3 statsBox">
            <h4>Total Usage This Month:</h4>
            <div class="card">
                <div class="card-body panel-body"><?php findTotalMonthUsage();?></div>
            </div>
        </div>

        <div class="col-lg-3 statsBox">
            <h4>Avg Daily Usage This Month:</h4>
            <div class="card">
                <div class="card-body panel-body"><?php findAvgMonthDailyUsage();?></div>
            </div>
        </div>
        
        <div class="col-lg-3 statsBox">
           <h4>Total Usage All Time:</h4>
            <div class="card">
                <div class="card-body"><?php findTotalAllTimeUsage();?></div>
            </div>
        </div>

        <div class="col-lg-3 statsBox">
            <h4>Avg Daily Usage All Time:</h4>
            <div class="card">
                <div class="card-body"><?php findAvgAllTimeUsage();?></div>
            </div>
        </div>                          
    </div>         
    <br>
    
    <div class="row">
        <div class="col-lg-1">
            <div class="card card-body sidePanels">
            
                <form action="main.php" method="POST" style="padding: 10px 0px 10px 0px">
                    <input class="btn btn-outline-primary" type="submit" name="next" value="Next">
                </form>
                
                <form action="main.php" method="POST" style="padding: 10px 0px 10px 0px">
                    <input class="btn btn-outline-primary" type="submit" name="previous" value="Previous">
                </form>
                
                <form action="main.php" method="GET" style="padding: 10px 0px 10px 0px">
                        <input class="btn btn-primary" type="submit" name="restart" value="Reset">
                </form>
            </div>
        </div>
                                  
        <div class="col-lg-10">
                           
                <h4>Daily Usage (KWH):</h4>
                <div class="table-responsive">
                    <table class='table table-hover table-striped tableStyle'>
                        <thead class="tblHeadBackground">
                            <?php writeUsageDays(); ?>
                        </thead>
                        <tbody>
                            <?php createUsageTable(); ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <h4>Daily Reads (KWH):</h4>
                <div class="table-responsive">
                   <table class='table table-hover table-striped tableStyle'>
                    <thead class="tblHeadBackground">
                        <?php writeReadsDays(); ?>
                    </thead>
                    <tbody>
                        <?php createReadsTable(); ?>
                    </tbody>
                    </table>
                </div>
        </div>
    
    </div>
</div>

<?php include "footer.php"; ?>

</body>
</html>