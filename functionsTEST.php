<?php

//sets timezone
date_default_timezone_set('Europe/Dublin');

function subtractMonth(){
    $_SESSION['monthDiff'] -= 1;
}

function addMonth(){
    $_SESSION['monthDiff'] += 1;
}

function showDate(){
    $activeDate = date('F Y', strtotime(date('Y-m-d')." +" . $_SESSION['monthDiff'] . "month"));
    echo $activeDate;
}

function prevMonth(){
    global $activeDate, $lastActiveDay, $activeMonthName, $activeMonthAbrev, $currentDate, $currentDay, $activeMonth, $currentMonthActive;
    
    
//    subtracts the session variable to get the month we are currently on goes like (-1 month) for prev month
    $activeDate = date('Y-m-d', strtotime(date('Y-m-d')." +" . $_SESSION['monthDiff'] . "month"));

     //    indicates if the graph displayed is of the current month or not
    $currentDate = date("Y-m");
    $activeMonth = date("Y-m", strtotime($activeDate));
    //checks to see if the current month is active
    If($currentDate == $activeMonth){
        $currentMonthActive = true;
    } else {
        $currentMonthActive = false;
    }
    
    //checks to see if previous month has any data - if not it resets the variable and doesn't allow the change
    if(monthExists($activeDate) == "NO DATA FOUND") {
        $_SESSION['monthDiff'] += 1;
        $activeDate = date('Y-m-d', strtotime(date('Y-m-d')." +" . $_SESSION['monthDiff'] . "month"));
        $currentDate = date("Y-m");
        $activeMonth = date("Y-m", strtotime($activeDate));
        //checks to see if the current month is active
        If($currentDate == $activeMonth){
            $currentMonthActive = true;
        } else {
            $currentMonthActive = false;
        }
        echo '<script>alert("NO DATA FOR PRIOR MONTH FOUND")</script>'; 
    } else {
    //    gets the last day of previous month
        $lastActiveDay = date("t", strtotime($activeDate));
    //    gets the full month name
        $activeMonthName = date("F", strtotime($activeDate));
    //    gets the month abrev
    //    $activeMonthAbrev = date("M", strtotime($activeDate));
        $activeMonthAbrev = date("n", strtotime($activeDate));
    //    gets the current day of month
        $currentDay = date('j');
        $currentDate = date("Y-m");
        $activeMonth = date("Y-m", strtotime($activeDate));
        //checks to see if the current month is active
        If($currentDate == $activeMonth){
            $currentMonthActive = true;
        } else {
            $currentMonthActive = false;
        }
    }
}

function nextMonth(){
    global $activeDate, $lastActiveDay, $activeMonthName, $activeMonthAbrev, $currentDate, $currentDay, $activeMonth, $currentMonthActive;
    
//    subtracts the session variable to get the month we are currently on goes like (-1 month) for prev month
    $activeDate = date('Y-m-d', strtotime(date('Y-m-d')." +" . $_SESSION['monthDiff'] . "month"));
    //    indicates if the graph displayed is of the current month or not
    $currentDate = date("Y-m");
    $activeMonth = date("Y-m", strtotime($activeDate));
    
    //checks to see if the current month is active
    If($currentDate == $activeMonth){
        $currentMonthActive = true;
    } else {
        $currentMonthActive = false;
    }
    
    //checks to see if previous month has any data - if not it resets the variable and doesn't allow the change
    if(monthExists($activeDate) == "NO DATA FOUND") {
        $_SESSION['monthDiff'] -= 1;
        $activeDate = date('Y-m-d', strtotime(date('Y-m-d')." +" . $_SESSION['monthDiff'] . "month"));
        $currentDate = date("Y-m");
        $activeMonth = date("Y-m", strtotime($activeDate));
        //checks to see if the current month is active
        If($currentDate == $activeMonth){
            $currentMonthActive = true;
        } else {
            $currentMonthActive = false;
        }
        echo '<script>alert("NO DATA FOR PRIOR MONTH FOUND")</script>'; 
    } else {
    //    gets the last day of previous month
        $lastActiveDay = date("t", strtotime($activeDate));
    //    gets the full month name
        $activeMonthName = date("F", strtotime($activeDate));
    //    gets the month abrev
    //    $activeMonthAbrev = date("M", strtotime($activeDate));
        $activeMonthAbrev = date("n", strtotime($activeDate));
    //    gets the current day of month
        $currentDay = date('j');
        $currentDate = date("Y-m");
        $activeMonth = date("Y-m", strtotime($activeDate));
        //checks to see if the current month is active
        If($currentDate == $activeMonth){
            $currentMonthActive = true;
        } else {
            $currentMonthActive = false;
        }
    }
}

function monthExists($activeDate){
    global $dataBase, $currentMonthActive;
    
//    gets the last day of the date passed to function
    $lastDay = date("Y-m-t", strtotime($activeDate));
    
    if($currentMonthActive == true){
        //    searches the array for current date to see if it exists
        $dayKeys = array_keys(array_column($dataBase, 'date'), $activeDate);   
    } else {
        //    searches the array for that date to determine if it exists
        $dayKeys = array_keys(array_column($dataBase, 'date'), $lastDay);   
    }
    
    if(empty($dayKeys)){
        $status = "NO DATA FOUND";
    } else {
        $status = "DATA HAS BEEN FOUND";
    }
    
    return $status;
}

//connects to the database
function connect(){
    global $connection;
    $connection = mysqli_connect('localhost', 'root', '', 'green acres db');
    if(!$connection) {
        die("Database Connection Failed");
    }
}

function readDB(){
    global $connection, $dataBase;
    // gets all info from database
    $query = "SELECT * FROM meterreads";

    $readData = mysqli_query($connection, $query);

    //  if the adddata query failed, kill page
    if(!$readData){
        die('QUERY FAILED' . mysqli_error());
    }
    
//    adds each line from the database into an array creating a multidimensional array
    while($variable = mysqli_fetch_assoc($readData)) {
        $dataBase[] = $variable;
    }
}

//creates google chart to show monthly data
function createMonthChart(){
    global $connection, $dataBase, $dailyUsage, $currentMonthActive, $currentDay, $lastDayActive, $jsonTable;
    
    $table = array();
    $table['cols'] = array(
        array('label' => 'Day', 'type' => 'number'),
        array('label' => 'Usage', 'type' => 'number')
    );

    for($i=1;$i<=count($dailyUsage);$i++){
        
    }
    
    $table['rows'] = $dailyUsage;
    $jsonTable = json_encode($table);

    return $jsonTable;
    
//    echo $jsonTable;
    
    
//    print_r($dailyUsage);
//    echo $json_encode($dailyUsage);
    
//    if($currentMonthActive == true) {
//        for($i=1;$n<=$currentDay;$i++){
//            if($i == $currentDay) {
//                echo json_encode("[".$i.",".$dailyUsage[$i]."]");  
//            } else {
//                echo json_encode("[".$i.",".$dailyUsage[$i]."],");  
//            }
//        }
//    } else {
//        for($i=1;$n<=$lastDayActive;$i++){
//            if($i == $lastDayActive){
//                echo "[".$i.",".$dailyUsage[$i]."]";  
//            } else {
//                echo "[".$i.",".$dailyUsage[$i]."],";  
//            }
//        }
//    }

}

//writes the days on top of the table
function writeReadsDays(){
    global $currentMonthActive, $lastActiveDay, $activeMonthAbrev;
//    if the current month is active, prints everyday up to current day, otherwise prints the full month
    echo "<th class='firstCell'>Unit</th>";
    if($currentMonthActive == true){
        for($n=1;$n <= $lastActiveDay;$n++){
            if ($n <= date('j')){
                echo "<th>$activeMonthAbrev/$n</th>";   
            }
        }   
    } else {
        for($n=1;$n <= $lastActiveDay;$n++){
            if ($n <= $lastActiveDay){
                echo "<th>$activeMonthAbrev/$n</th>";   
            }
        }
    }
}

//writes the days on top of the table
function writeUsageDays(){
    global $currentMonthActive, $lastActiveDay, $activeMonthAbrev;
    
// if the current month is active, prints everyday up to the day previous, otherwise prints the full month
    echo "<th class='firstCell'>Unit</th>";
    if($currentMonthActive == true){
        for($n=1;$n <= $lastActiveDay;$n++){
            if ($n < date('j')){
                echo "<th>$activeMonthAbrev/$n</th>";   
            }
        }   
    } else {
        for($n=1;$n <= $lastActiveDay;$n++){
            if ($n <= $lastActiveDay){
                echo "<th>$activeMonthAbrev/$n</th>";   
            }
        }
    }
}

function createReadsTable(){
    global $dataBase, $connection, $activeDate, $lastActiveDay, $activeMonthName, $activeMonthAbrev, $currentDate, $currentDay, $activeMonth, $currentMonthActive;
    
    $rows = 13; // define number of rows
//if its current month, cols = current day, otherwise it = last day of month
    if($currentMonthActive == true){
        $cols = $currentDay;// define number of columns   
    } else {
        $cols = $lastActiveDay;
    }
    

//    loops through to create the rows
    for($tr=1;$tr<=$rows;$tr++){

        echo "<tr>";
                
            echo "<th>$tr</th>";
//            loops through to create the columns
            for($td=1;$td<=$cols;$td++){
                $prevRead = 0;
                $currentRead = 0;
                
                $dayNum = sprintf("%02d", $td);
                
//                searches the array by date - returning the key for each read that day $currentDate is just
                $dayKeys = array_keys(array_column($dataBase, 'date'), $activeMonth . '-' . $dayNum);
    
                // variable for is there is no read present for value
                $readFound = false;
                
//                loops through the keys until it finds the correct one by unit then it writes it to the table and loop restarts
                foreach ($dayKeys as $key){
                    $meterread = round($dataBase[$key]['meterread']);
                    $unit = $dataBase[$key]['unit'];

//                        grabs the first meter read
                    if($tr == $unit || ($unit == '14/13' && $tr == 13)){
                        $readFound = true;
                        echo "<td>$meterread</td>";
                    }    
                }
                
                //if no value was found, enter a blank value
                if($readFound == false){
                    echo "<td></td>";
                }
            }
        echo "</tr>";
    }
}

function createUsageTable(){
    global $dataBase, $connection, $activeDate, $lastActiveDay, $activeMonthName, $activeMonthAbrev, $currentDate, $currentDay, $activeMonth, $currentMonthActive;
    
    $rows = 13; // define number of rows
    
//if its current month, cols = current day, otherwise it = last day of month
    if($currentMonthActive == true){
        $cols = $currentDay;// define number of columns   
    } else {
        $cols = $lastActiveDay;
    }

//    loops through to create the rows
    for($tr=1;$tr<=$rows;$tr++){

        echo "<tr>";
            
            echo "<th>$tr</th>";
        
            for($td=1;$td<=$cols;$td++){
 //            loops through to create the columns               
                $prevRead = 0;
                $currentRead = 0;
                
                $dayNum = sprintf("%02d", $td);
                $nextDayNum = sprintf("%02d", $td + 1);
                
//                searches the array by date - returning the key for day 1             
                $dayKeys = array_keys(array_column($dataBase, 'date'), $activeMonth . '-' . $dayNum);
    
                // variable for is there is no read present for value
                $read1Found = false;
                $read2Found = false;
//                loops through the keys until it finds the correct one by unit then it moves to the day after to calculate usage
                foreach ($dayKeys as $key){
                    $meterread = round($dataBase[$key]['meterread']);
                    $unit = $dataBase[$key]['unit'];

//                        grabs the first meter read
                    if($tr == $unit || ($unit == '14/13' && $tr == 13)){
                        $read1Found = true;
                        $prevRead = $meterread;
                    }    
                }

//                searches the array by date - returning the key for day 2      
                if($currentMonthActive == true){
                    $dayKeys = array_keys(array_column($dataBase, 'date'), $activeMonth . '-' . $nextDayNum);   
                } else {
                    if($dayNum == $cols){
                        $nextActiveMonth = date('Y-m', strtotime($activeMonth." +1 month"));
                        $dayKeys = array_keys(array_column($dataBase, 'date'), $nextActiveMonth . '-01');
                    } else {
                        $dayKeys = array_keys(array_column($dataBase, 'date'), $activeMonth . '-' . $nextDayNum);
                    }
                }

//                loops through the keys until it finds the correct one by unit then it calculates usage
                foreach ($dayKeys as $key){
                    $meterread = round($dataBase[$key]['meterread']);
                    $unit = $dataBase[$key]['unit'];
                    
                    //grabs the second meter read
                    if($tr == $unit || ($unit == '14/13' && $tr == 13)){
                        $read2Found = true;
                        $currentRead = $meterread;
                    }        
                }
                
                if($read1Found != false && $read2Found != false){
                    $usage = $currentRead - $prevRead;
                    echo "<td>$usage</td>";
                    $prevRead = 0;
                    $currentRead = 0;
                }
                
                if($currentMonthActive == true){
                    if($read1Found == false || $read2Found == false && $nextDayNum < $cols) {
                        echo "<td></td>";
                    }   
                } else {
                    if($read1Found == false || $read2Found == false && $nextDayNum <= $cols) {
                        echo "<td></td>";
                    }  
                }
            }

//                   echo "<td>$td $DBDay</td>";
            
        echo "</tr>";
    }
}

function findTotalAllTimeUsage(){
    global $dataBase, $allTimeUsage;
    
    $allTimeUsage = 0;
    
    $today = new DateTime(date("Y-m-d"));
    $firstDay = new DateTime($dataBase[0]['date']);
    
    $dateDiff = $today->diff($firstDay);
    
    $totalDays = $dateDiff->format('%a');
    
    //loops through each day to calculate the total usage all time
    for ($x = $totalDays; $x > 0; $x--) {
        $firstReadDate = date('Y-m-d',(strtotime ('-' . $x . 'day', strtotime (date("Y-m-d")))));
        $secondReadDate = date('Y-m-d',(strtotime ('-' . ($x - 1) . 'day', strtotime (date("Y-m-d")))));
        //loops through the units to grab each read for each day
        for($y = 1; $y <= 13; $y++) {
            $dayKeys = array_keys(array_column($dataBase, 'date'), $firstReadDate);
            
            // variable for is there is no read present for value
            $read1Found = false;
            $read2Found = false;
            //loops through the keys until it finds the correct one by unit then it moves to the day after to calculate usage
            foreach ($dayKeys as $key){
                $meterread = round($dataBase[$key]['meterread']);
                $unit = $dataBase[$key]['unit'];

//                        grabs the first meter read
                if($y == $unit || ($unit == '14/13' && $y == 13)){
                    $read1Found = true;
                    $prevRead = $meterread;
                }    
            }
            
            $dayKeys = array_keys(array_column($dataBase, 'date'), $secondReadDate);
            
            foreach ($dayKeys as $key){
                    $meterread = round($dataBase[$key]['meterread']);
                    $unit = $dataBase[$key]['unit'];
                    
                    //grabs the second meter read
                    if($y == $unit || ($unit == '14/13' && $y == 13)){
                        $read2Found = true;
                        $currentRead = $meterread;
                    }        
                }

//                calculates and writes usage then loop restarts
                if($read1Found != false && $read2Found != false){
                    $usage = $currentRead - $prevRead;
                    $allTimeUsage = $allTimeUsage + $usage;
                    $prevRead = 0;
                    $currentRead = 0;
                }
            
            
        }
    }
    $allTimeUsageFormatted = number_format($allTimeUsage);
    echo "<h5>$allTimeUsageFormatted KWH</h5>";
    
}

function findTotalMonthUsage(){
    global $dataBase, $connection, $activeDate, $lastActiveDay, $activeMonthName, $activeMonthAbrev, $currentDate, $currentDay, $activeMonth, $currentMonthActive, $monthUsage, $dailyUsage;
    
    $monthUsage = 0;
    
//    $dailyUsage = array();
    
//    initalizes the array using rows as the amount of items to make
//    if($currentMonthActive == true){
//        for($i=1;$i<=$currentDay;$i++){
//            $newArray = array($i=>0);
//            $dailyUsage = array_merge($dailyUsage, $newArray);
//        } 
//    } else {
//        for($i=1;$i<=$lastActiveDay;$i++){
//            $newArray = array($i=>0);
//            $dailyUsage = array_merge($dailyUsage, $newArray);
//        }   
//    }
    
    $rows = 13; // define number of rows
    
//if its current month, cols = current day, otherwise it = last day of month
    if($currentMonthActive == true){
        $cols = $currentDay;// define number of columns   
    } else {
        $cols = $lastActiveDay;
    }

//    loops through to create the rows
    for($tr=1;$tr<=$rows;$tr++){
            $currentDailyUsage = 0;
            for($td=1;$td<=$cols;$td++){
 //            loops through to create the columns               
                $prevRead = 0;
                $currentRead = 0;
                
                $dayNum = sprintf("%02d", $td);
                $nextDayNum = sprintf("%02d", $td + 1);
                
//                searches the array by date - returning the key for day 1             
                $dayKeys = array_keys(array_column($dataBase, 'date'), $activeMonth . '-' . $dayNum);
    
                // variable for is there is no read present for value
                $read1Found = false;
                $read2Found = false;
//                loops through the keys until it finds the correct one by unit then it moves to the day after to calculate usage
                foreach ($dayKeys as $key){
                    $meterread = round($dataBase[$key]['meterread']);
                    $unit = $dataBase[$key]['unit'];

//                        grabs the first meter read
                    if($tr == $unit || ($unit == '14/13' && $tr == 13)){
                        $read1Found = true;
                        $prevRead = $meterread;
                    }    
                }

//                searches the array by date - returning the key for day 2      
                if($currentMonthActive == true){
                    $dayKeys = array_keys(array_column($dataBase, 'date'), $activeMonth . '-' . $nextDayNum);   
                } else {
                    if($dayNum == $cols){
                        $nextActiveMonth = date('Y-m', strtotime($activeMonth." +1 month"));
                        $dayKeys = array_keys(array_column($dataBase, 'date'), $nextActiveMonth . '-01');
                    } else {
                        $dayKeys = array_keys(array_column($dataBase, 'date'), $activeMonth . '-' . $nextDayNum);
                    }
                }

//                loops through the keys until it finds the correct one by unit then it calculates usage
                foreach ($dayKeys as $key){
                    $meterread = round($dataBase[$key]['meterread']);
                    $unit = $dataBase[$key]['unit'];
                    
                    //grabs the second meter read
                    if($tr == $unit || ($unit == '14/13' && $tr == 13)){
                        $read2Found = true;
                        $currentRead = $meterread;
                    }        
                }

//                calculates and writes usage then loop restarts
                if($read1Found != false && $read2Found != false){
                    $usage = $currentRead - $prevRead;
//                    $dailyUsage[$td] = $dailyUsage[$td] + $usage;
                    $monthUsage = $monthUsage + $usage;
                    $prevRead = 0;
                    $currentRead = 0;
                }
            }

    }
    $monthUsageFormatted = number_format($monthUsage);
    echo "<h5>$monthUsageFormatted KWH</h5>";
}

function findAvgMonthDailyUsage(){
    global $monthUsage, $currentMonthActive, $lastActiveDay;
    
    if($currentMonthActive == true){
        $day = intval(date('j')) - 1;
        $avgUsage = round($monthUsage / $day);
    } else {
        $avgUsage = round($monthUsage / $lastActiveDay);
    }

    
    echo "<h5>$avgUsage KWH</h5>";
}

function findAvgAllTimeUsage(){
    global $allTimeUsage, $dataBase;
    
    $today = new DateTime(date("Y-m-d"));
    $firstDay = new DateTime($dataBase[0]['date']);
    
    $dateDiff = $today->diff($firstDay);
    
    $totalDays = $dateDiff->format('%a');
    
    $avgUsage = round($allTimeUsage / $totalDays);
    
    echo "<h5>$avgUsage KWH</h5>";
}



?>