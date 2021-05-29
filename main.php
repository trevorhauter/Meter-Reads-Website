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

    if (!isset($_SESSION['client'])){
        $_SESSION['client'] = 'greenacres';
    }

    if (!isset($_SESSION['utility'])){
        $_SESSION['utility'] = 'ele';
    }

    if(isset($_GET['monthDiff'])){
        $_SESSION['monthDiff'] = $_GET['monthDiff'];
    }

    if (isset($_GET['client'])){
        $_SESSION['client'] = $_GET['client'];
    }

    if (isset($_GET['utility'])){
        $_SESSION['utility'] = $_GET['utility'];
    }

    if (!isset($_GET['page'])) {
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
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
</head>
<body>
   
<?php include "header.php"; ?>

<div class="container-fluid">
    <div class="row">
        <center>
            <div class="card pageDescription">
                <div class="card-body shadow-sm">
                    <div class="panel-body">
                        <h2><?php showDate(); ?> Meter Reads: 
                            <?php 
                                if(isset($_SESSION['client'])) {
                                    if($_SESSION['client'] == 'greenacres') {
                                        echo "Green Acres";
                                    }
                                    if($_SESSION['client'] == 'williams') {
                                        echo "Williams";
                                    }
                                }
                            ?>
                        </h2></div>
                </div>
            </div>
        </center>
    </div>
        
    
    <div class="row">
       <div class="col-lg-3 statsBox">
            <h4>Total Usage This Month:</h4>
            <div class="card shadow-sm">
                <div class="card-body panel-body"><?php findTotalMonthUsage();?></div>
            </div>
        </div>

        <div class="col-lg-3 statsBox">
            <h4>Avg Daily Usage This Month:</h4>
            <div class="card shadow-sm">
                <div class="card-body panel-body"><?php findAvgMonthDailyUsage();?></div>
            </div>
        </div>
        
        <div class="col-lg-3 statsBox">
           <h4>Total Usage All Time:</h4>
            <div class="card shadow-sm">
                <div class="card-body"><?php findTotalAllTimeUsage();?></div>
            </div>
        </div>

        <div class="col-lg-3 statsBox">
            <h4>Avg Daily Usage All Time:</h4>
            <div class="card shadow-sm">
                <div class="card-body"><?php findAvgAllTimeUsage();?></div>
            </div>
        </div>                          
    </div>         
    <br>
    <div class="row">
        <div class="col-lg-12 graphContainer">
            <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
            var data = google.visualization.arrayToDataTable([
                <?php createMonthChart(); ?>
            ]);

            var options = {
              title: 'Daily Usage This Month',
              //curveType: 'function',
              legend: { position: 'bottom' }
                
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
            }
            </script>
            <div id="curve_chart" style="width: 100%; height: 500px"></div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-1">
            <div class="card card-body sidePanels">
                <center>
                    <div class="btn-group" style="padding: 10px 0px 10px 0px">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Utility
                        </button>
                        <div class="dropdown-menu">

                        <?php 
                            if(isset($_SESSION['client'])){
                                if($_SESSION['client']=='greenacres'){ 
                                    echo '<a class="dropdown-item" href="main.php?utility=ele">Electric (KWH)</a>'; 
                                }
                            }
                        ?>

                        <?php 
                            if(isset($_SESSION['client'])){
                                if($_SESSION['client']=='williams'){ 
                                    echo '<a class="dropdown-item" href="main.php?utility=ele">Electric (KWH)</a>';
                                    echo '<a class="dropdown-item" href="main.php?utility=cf">Natural Gas (CF)</a>';
                                    echo '<a class="dropdown-item" href="main.php?utility=wtr">Water (GAL)</a>';
                                }
                            }
                        ?>

                        </div>
                    </div>

                    <div class="btn-group" style="padding: 10px 0px 10px 0px">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Date
                        </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <center>
                                <?php createDateSelection(); ?>
                               </center>
                            </ul>
                    </div>
                </center>
                
               
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
                                  
        <div class="col-lg-11">
            <div class="row">
                <div class="col-lg-10" style="padding-bottom:40px;">

                    <h4>
                        Daily Usage 
                        <?php
                            if(isset($_SESSION['utility'])){
                                if($_SESSION['utility'] == 'ele'){
                                    echo '(KWH)';
                                }
                                if($_SESSION['utility'] == 'cf'){
                                    echo '(CF)';
                                }
                                if($_SESSION['utility'] == 'wtr'){
                                    echo '(GAL)';
                                }
                            }
                        ?>
                    </h4>
                    <div class="table-responsive tableContainerStyle">
                        <table class='table table-hover table-striped tableStyle'>
                            <thead class="tblHeadBackground">
                                <?php writeUsageDays(); ?>
                            </thead>
                            <tbody>
                                <?php createUsageTable(); ?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-lg-2">

                    <h4>Usage Breakdown</h4>
                    <table class='table table-hover table-striped tableStyle'>
                    <thead class="tblHeadBackground">
                        <th class="firstCell">Unit</th>
                        <th class="firstCell">Usage 
                        <?php
                        if(isset($_SESSION['utility'])){
                                    if($_SESSION['utility'] == 'ele'){
                                        echo '(KWH)';
                                    }
                                    if($_SESSION['utility'] == 'cf'){
                                        echo '(CF)';
                                    }
                                    if($_SESSION['utility'] == 'wtr'){
                                        echo '(GAL)';
                                    }
                                }
                        ?>
                        </th>
                    </thead>
                    <tbody>
                        <?php monthToDateUsage(); ?>
                    </tbody>
                    </table>

                </div>   
            </div>      
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
           <br>
            <h4>Daily Reads 

                <?php
                    if(isset($_SESSION['utility'])){
                        if($_SESSION['utility'] == 'ele'){
                            echo '(KWH)';
                        }
                        if($_SESSION['utility'] == 'cf'){
                            echo '(CF)';
                        }
                        if($_SESSION['utility'] == 'wtr'){
                            echo '(GAL)';
                        }
                    }
                ?>

            </h4>
            <div class="table-responsive tableContainerStyle">
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

<?php 
    include "footer.php"; 
?>

</body>
</html>