<?php

//sets timezone
//date_default_timezone_set('Europe/Dublin');
date_default_timezone_set('America/New_York');

function subtractMonth(){
    $_SESSION['monthDiff'] -= 1;
}

function addMonth(){
    $_SESSION['monthDiff'] += 1;
}

function createDateSelection() {
    global $dataBase, $allTimeUsage, $stats, $formattedUtilityType, $unitList;
    
    $thisYear = date("Y");
    $firstYear = date("Y", strtotime($dataBase[0]['date']));
    $firstMonth = date("m", strtotime($dataBase[0]['date']));
    $totalYears = $thisYear - $firstYear;
    
    //williams is a day behind for meter reads so this accounts for that
    if($_SESSION['client'] == 'williams'){
        $activeDateMonth = date('Y-m-d');
        $currentMonthNum =  date('n',(strtotime ( '-1 day' , strtotime ($activeDateMonth))));
    } else {
        $currentMonthNum = date("n");   
    }
    
    //month diff is the number of months you would need to go back to get to each date. it is claculated in each if statement because they apply differently
    $monthDiff = 0;
    
    //loops through to create the years
    for($i=1;$i <= $totalYears + 1;$i++){
        $currentYear = $thisYear + 1 - $i;
        
        echo "<li class='dropend'>";
        
        echo "<a class='dropdown-item dropdown-toggle' href='#' type='button' data-bs-toggle='dropdown' aria-expanded='false'>$currentYear</a>";
        
        echo "<ul class='dropdown-menu' aria-labelledby='dropdownMenu3'>";
        
        //loops through to create the months for each year
        for($y=1;$y<=12;$y++){
            
            //variable used to print the months backwards
            $monthNum = 13 - $y;
            
            //if its printing the current year
            if($currentYear == $thisYear){
                if($currentMonthNum >= $monthNum) {
                    $monthDiff = $monthNum - $currentMonthNum;
                    $month = date('F', mktime(0, 0, 0, $monthNum, 10));
                    echo "<li><a class='dropdown-item' href='main.php?monthDiff=$monthDiff'>$month</a></li>";
                }
            } else {
                //if its not printing the current year, checks to see if its the first year so it makes sure it doesn't print past months that don't exist
                if($firstYear == $currentYear) {
                    $monthDiff = $monthNum - $currentMonthNum - ($totalYears * 12);
                    if($monthNum >= $firstMonth){
                        $month = date('F', mktime(0, 0, 0, $monthNum, 10));
                        echo "<li><a class='dropdown-item' href='main.php?monthDiff=$monthDiff'>$month</a></li>";
                    }
                } else {
                    $currentYearDiff = $thisYear - $currentYear;
                    $monthDiff = $monthNum - $currentMonthNum - ($currentYearDiff * 12);
                    $month = date('F', mktime(0, 0, 0, $monthNum, 10));
                    echo "<li><a class='dropdown-item' href='main.php?monthDiff=$monthDiff'>$month</a></li>";
                }
            }
        }
        
        echo "</ul>";
        echo "</li>";
    }
    
}

//displays the date in main box
function showDate(){
        $activeDateMonth = date('Y-m-d', strtotime(date('Y-m-d')." +" . $_SESSION['monthDiff'] . "month"));
        $activeDate =  date('F Y',(strtotime ( '-1 day' , strtotime ($activeDateMonth))));
    echo $activeDate;
}

//function to set all information to previous month. if no data is found, it resets it to the month it was already on.
function prevMonth(){
    global $activeDate, $lastActiveDay, $activeMonthName, $activeMonthAbrev, $currentDate, $currentDay, $activeMonth, $currentMonthActive;
    
    
//    subtracts the session variable to get the month we are currently on goes like (-1 month) for prev month
    $activeDate = date('Y-m-d', strtotime(date('Y-m-d')." +" . $_SESSION['monthDiff'] . "month"));
    $activeDate =  date('Y-m-d',(strtotime ( '-1 day' , strtotime ($activeDate))));
    
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

//function to set all information to next month. if no data is found, it resets it to the month it was already on.
function nextMonth(){
    global $activeDate, $lastActiveDay, $activeMonthName, $activeMonthAbrev, $currentDate, $currentDay, $activeMonth, $currentMonthActive;
    
//    subtracts the session variable to get the month we are currently on goes like (-1 month) for prev month
    $activeDate = date('Y-m-d', strtotime(date('Y-m-d')." +" . $_SESSION['monthDiff'] . "month"));
    $activeDate =  date('Y-m-d',(strtotime ( '-1 day' , strtotime ($activeDate))));
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
    global $connection, $unitList;
    //each client has a different database, so the proper one is connected to
    if($connection){
        mysqli_close($connection);
    }
            
    $connection = mysqli_connect('localhost', 'root', '', 'meterreadswebsite');
    if(!$connection) {
        die("Database Connection Failed");
    }
    
    $unitList = $_SESSION['unitList'];
}

//gathers all the information into a multi-dimensional array so the database doesn't have to be queried over and over again
function readDB(){
    global $connection, $dataBase, $formattedUtilityType, $stats;
    
    // gets all info from database
    $query = "SELECT * FROM meterreads WHERE facility = '" . $_SESSION['client'] . "' AND utility = '" .  $_SESSION['utility'] . "'";
    //sends the actual command to the db
    $readData = mysqli_query($connection, $query);
    
    //  if the adddata query failed, kill page
    if(!$readData){
        die('QUERY FAILED');
    }

    //    adds each line from the database into an array creating a multidimensional array
    while($variable = mysqli_fetch_assoc($readData)) {
        $dataBase[] = $variable;
    }

    $_SESSION['db'] = $dataBase;
    
    
    //gets the stats for each of the properties
    $query = "SELECT * FROM stats WHERE facility = '" . $_SESSION['client'] . "'";
    $readData = mysqli_query($connection, $query);

    //  if the adddata query failed, kill page
    if(!$readData){
        die('QUERY FAILED');
    }
    
    //gets the proper abbreviation of units for each utility
    If($_SESSION['utility'] == 'ele') {
        $formattedUtilityType = 'KWH';
    }
    If($_SESSION['utility'] == 'nga') {
        $formattedUtilityType = 'CF';
    }
    If($_SESSION['utility'] == 'water') {
        $formattedUtilityType = 'GAL'; 
    }
    
}

//creates google chart to show monthly data
function createMonthChart(){
    global $connection, $dataBase, $dailyUsage, $activeMonthAbrev, $formattedUtilityType;
    
    echo "['Day', 'Usage $formattedUtilityType'],";
    
    $dayFound = false;
    
    //loops through the daily array and prints the values in the way the chart needs. if its the last day it doesn't include a comma at the end
    //special logic for if there isn't a read for the first. This means that its the first month of usage for the client and there is no data for the beginning of that month
    if(!isset($dailyUsage[1])){
        //finds out the first value of the array
        for($i=1;$i<=31;$i++){
            //if value is set, breaks the for loop and we now know the first day that usage occurred
            if(isset($dailyUsage[$i])){
                $firstDay = $i;
                $dayFound = true;
                break;
            }
        }
        
        if($dayFound == true){
            //gets the last day that usage occurred (last of the month)
            $lastDay = count($dailyUsage) + $firstDay - 1;
            
            //loops through like normal and creates the graph
            for($i=$firstDay;$i<=$lastDay;$i++){
                if($i != $lastDay) {
                    echo "['$i', $dailyUsage[$i]],";
                } else {
                    echo "['$i', $dailyUsage[$i]]";
                }
            }
        }
        
        //for if there is no usage at all for the month (williams im lookin at you... day behind smh...)
        if($dayFound == false) {
            echo "['1', 0]";
        }
        
    } else {
        for($i=1;$i<=count($dailyUsage);$i++){
            if($i != count($dailyUsage)) {
                echo "['$i', $dailyUsage[$i]],";
            } else {
                echo "['$i', $dailyUsage[$i]]";
            }
        }
    }
}

//writes the days on top of the table
function writeReadsDays(){
    global $currentMonthActive, $lastActiveDay, $activeMonthAbrev;
//    if the current month is active, prints everyday up to current day, otherwise prints the full month
    echo "<th class='firstCell'>Unit</th>";
    
    //code writes all the days in the month regardless of current day active. creates much better tables for early month readings
    for($n=1;$n <= $lastActiveDay;$n++){
        if ($n <= $lastActiveDay){
            echo "<th class='tableBackground'>$activeMonthAbrev/$n</th>";   
        }
    }
    
}

//writes the days on top of the table
function writeUsageDays(){
    global $currentMonthActive, $lastActiveDay, $activeMonthAbrev;
    
// if the current month is active, prints everyday up to the day previous, otherwise prints the full month
    echo "<th class='firstCell'>Unit</th>";
    
    //code writes all the days in the month regardless of current day active. creates much better tables for early month readings
    for($n=1;$n <= $lastActiveDay;$n++){
        if ($n <= $lastActiveDay){
            echo "<th class='tableBackground'>$activeMonthAbrev/$n</th>";   
        }
    }
    
}

function createReadsTable(){
    global $dataBase, $connection, $activeDate, $lastActiveDay, $activeMonthName, $activeMonthAbrev, $currentDate, $currentDay, $activeMonth, $currentMonthActive, $unitList;
    $unitList = $_SESSION['unitList'];
    $rows = $unitList; // define number of rows
    
    $cols = $lastActiveDay;

    // loops through to create the rows
    for($tr=1;$tr<=$rows;$tr++){

        echo "<tr>";
                
            //if its creating the last cell, it gives it a special class for the border radius
            if($tr == $rows) {
                echo "<th class='lastCell'>$tr</th>"; 
            } else {
                echo "<th>$tr</th>";   
            }
        
            //  loops through to create the columns
            for($td=1;$td<=$cols;$td++){
                $prevRead = 0;
                $currentRead = 0;
                
                //formats the day number (ex. from "1" to "01")
                $dayNum = sprintf("%02d", $td);
                
                //  searches the array by date - returning the key for each read that day $currentDate is just
                $dayKeys = array_keys(array_column($dataBase, 'date'), $activeMonth . '-' . $dayNum);

                // variable for is there is no read present for value
                $readFound = false;
                
                //  loops through the keys until it finds the correct one by unit then it writes it to the table and loop restarts
                foreach ($dayKeys as $key){
                    $meterread = round($dataBase[$key]['meter_read']);        
                    $unit = $dataBase[$key]['unit'];
                    //grabs the meter read
                    if($tr == $unit){
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
    global $dataBase, $connection, $activeDate, $lastActiveDay, $activeMonthName, $activeMonthAbrev, $currentDate, $currentDay, $activeMonth, $currentMonthActive, $unitList, $mtdUsage;
    
    $unitList = $_SESSION['unitList'];
    $rows = $unitList; // define number of rows

    $cols = $lastActiveDay;
    
    //creates the array that will contain the month to date usage for each unit
//    $mtdUsage = [];
//    loops through to create the rows
    for($tr=1;$tr<=$rows;$tr++){
//        $mtdUsage += [$unitList[$tr] => 0];
        echo "<tr>";
            
            //if its creating the last cell, it gives it a special class for the border radius
            if($tr == $rows) {
                echo "<th class='lastCell'>$tr</th>"; 
            } else {
                echo "<th>$tr</th>";   
            }
        
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
                    $meterread = round($dataBase[$key]['meter_read']);        
                    $unit = $dataBase[$key]['unit'];

//                        grabs the first meter read
                    if($tr == $unit){
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
                    $meterread = round($dataBase[$key]['meter_read']);        
                    $unit = $dataBase[$key]['unit'];
                    
                    //grabs the second meter read
                    if($tr == $unit){
                        $read2Found = true;
                        $currentRead = $meterread;
                    }        
                }
                
                //if both reads are found for a day, it calculates the usage and puts it into the table
                if($read1Found != false && $read2Found != false){
                    $usage = $currentRead - $prevRead;
//                    $mtdUsage[$unitList[$tr]] = $mtdUsage[$unitList[$tr]] + $usage;
                    echo "<td>$usage</td>";
                    $prevRead = 0;
                    $currentRead = 0;
                }
                
                //if not reads are found, it creates an empty table cell
                if($currentMonthActive == true){
                    if(($read1Found == false || $read2Found == false) && $nextDayNum <= $cols + 1) {
                        echo "<td></td>";
                    }   
                } else {
                    if(($read1Found == false || $read2Found == false) && $nextDayNum <= $cols + 1) {
                        echo "<td></td>";
                    }  
                }
            }
            
        echo "</tr>";
    }
}

function monthToDateUsage(){
    global $dataBase, $connection, $activeDate, $lastActiveDay, $activeMonthName, $activeMonthAbrev, $currentDate, $currentDay, $activeMonth, $currentMonthActive, $unitList, $mtdUsage, $formattedUtilityType;
    $unitList = $_SESSION['unitList'];
    $rows = $unitList; // define number of rows
    
    $cols = 1;
    
//    loops through to create the rows
    for($tr=1;$tr<=$rows;$tr++){

        echo "<tr>";
            
            //if its creating the last cell, it gives it a special class for the border radius
            if($tr == $rows) {
                echo "<th class='lastCell'>$tr</th>"; 
            } else {
                echo "<th>$tr</th>";   
            }
        
            $mtdUsageFormatter = number_format($mtdUsage[$tr]);
            for($td=1;$td<=$cols;$td++){
                echo "<td>$mtdUsageFormatter&nbsp;$formattedUtilityType</td>";
            }
            
        echo "</tr>";
    }
}

function findTotalAllTimeUsage(){
    global $dataBase, $allTimeUsage, $stats, $formattedUtilityType, $unitList;
    
    $dataFound = false;
    
    $allTimeUsage = 0;
    
    $today = new DateTime(date("Y-m-d"));
    $firstDay = new DateTime($dataBase[0]['date']);
    
    $dateDiff = $today->diff($firstDay);
    
    $totalDays = $dateDiff->format('%a');
    $unitList = $_SESSION['unitList'];
    
    //checks the database to see if the stats were updated today
//    $todaysStats = array_keys(array_column($stats, 'date'), date("Y-m-d"));
       
    //checks the returned keys to see if they match the current utility. if so, data is marked true meaning that data is already in the database for the day and can be pulled from there
//    if(!empty($todaysStats)){
//        foreach($todaysStats as $key){
//            if($stats[$key]['type'] == $_SESSION['utility']){
//                $dataFound = true;
//            }
//        }   
//    }

    //if todays stats hasn't been updated in the database, it updates them (takes a while, but only happens once a day per client per utility)
    if($dataFound == false){
        //loops through each day to calculate the total usage all time
        for ($x = $totalDays; $x > 0; $x--) {
            $firstReadDate = date('Y-m-d',(strtotime ('-' . $x . 'day', strtotime (date("Y-m-d")))));
            $secondReadDate = date('Y-m-d',(strtotime ('-' . ($x - 1) . 'day', strtotime (date("Y-m-d")))));
        //loops through the units to grab each read for each day
            for($y = 1; $y <= $unitList; $y++) {
                $dayKeys = array_keys(array_column($dataBase, 'date'), $firstReadDate);
                // variable for is there is no read present for value
                $read1Found = false;
                $read2Found = false;
                //loops through the keys until it finds the correct one by unit then it moves to the day after to calculate usage
                foreach ($dayKeys as $key){
                    $meterread = round($dataBase[$key]['meter_read']);        
                    $unit = $dataBase[$key]['unit'];
    //                        grabs the first meter read
                    if($y == $unit){
                        $read1Found = true;
                        $prevRead = $meterread;
                    }    
                }

                $dayKeys = array_keys(array_column($dataBase, 'date'), $secondReadDate);

                foreach ($dayKeys as $key){
                    $meterread = round($dataBase[$key]['meter_read']);        
                    $unit = $dataBase[$key]['unit'];

                    //grabs the second meter read
                    if($y == $unit){
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
        //updates the database with the freshly calculated all time usage stat. only triggers if the data hasn't been found
//        updateStats();
    } else {
        //if data is found, loops through the database to find the proper value
        foreach ($todaysStats as $key){
            //checks to see if the utilities match
            if($_SESSION['utility'] == $stats[$key]['type']) {
                $allTimeUsage = $stats[$key]['value'];
            }
        }
    }
    
    
    $allTimeUsageFormatted = number_format($allTimeUsage);
    echo "<h5>$allTimeUsageFormatted $formattedUtilityType</h5>";
}

function findTotalMonthUsage(){
    global $dataBase, $connection, $activeDate, $lastActiveDay, $activeMonthName, $activeMonthAbrev, $currentDate, $currentDay, $activeMonth, $currentMonthActive, $monthUsage, $dailyUsage, $unitList, $formattedUtilityType, $mtdUsage;
    
    $monthUsage = 0;
    
    $unitList = $_SESSION['unitList'];
    
    $rows = $unitList; // define number of rows
    
//if its current month, cols = current day, otherwise it = last day of month
    if($currentMonthActive == true){
        $cols = $currentDay;// define number of columns   
    } else {
        $cols = $lastActiveDay;
    }

    //mtd usage is for month to date usage. collects data for every apartment
    $mtdUsage = [];
    //daily usage counts the usage per day
    $dailyUsage = [];
    
//    loops through to create the rows
    for($tr=1;$tr<=$rows;$tr++){
            //mtd needs to be talied for each row because each row is a unit
            $mtdUsage += [$tr => 0];
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
                    //checks for a comma in the string. smh its because there is one read with a comma in it for the williams
                    if( strpos($dataBase[$key]['meter_read'], ',') !== false ) {
                        $meterread = str_replace(',', '', $dataBase[$key]['meter_read']);  
                    } else {
                        $meterread = round($dataBase[$key]['meter_read']);        
                    }
                    $unit = $dataBase[$key]['unit'];

//                        grabs the first meter read
//                    if($tr == $unit && $_SESSION['utility'] == $dataBase[$key]['type']){
                    if($tr == $unit){
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
                    $meterread = round($dataBase[$key]['meter_read']);        
                    $unit = $dataBase[$key]['unit'];
                    
                    if($tr == $unit){
                        $read2Found = true;
                        $currentRead = $meterread;
                        
                        //daily usage needs to be tallied for each collumn because each col is a day
                        //checks to see if the value for this collumn has been created, if not it creates it. if so it skips this part and adds the usage for the day to the existing vlaue
                        //daily usage variable creation is in the second meter read found area to ensure it doesn't create an entry for a day that doesn't have calculated usage
                        if(!isset($dailyUsage[$td])) {
                            $dailyUsage += [$td => 0];
                        }
                    }        
                }

//                calculates and writes usage then loop restarts
                if($read1Found != false && $read2Found != false){
                    $usage = $currentRead - $prevRead;
//                    $dailyUsage[$td] = $dailyUsage[$td] + $usage;
                    $mtdUsage[$tr] = $mtdUsage[$tr] + $usage;
                    $dailyUsage[$td] = $dailyUsage[$td] + $usage;
                    $monthUsage = $monthUsage + $usage;
                    $prevRead = 0;
                    $currentRead = 0;
                }
            }

    }
    
    $monthUsageFormatted = number_format($monthUsage);
    echo "<h5>$monthUsageFormatted $formattedUtilityType</h5>";
}

function findAvgMonthDailyUsage(){
    global $monthUsage, $currentMonthActive, $lastActiveDay, $formattedUtilityType;
    if($monthUsage != 0){
        if($currentMonthActive == true){
        $day = intval(date('j')) - 1;
        $avgUsage = round($monthUsage / $day);
        } else {
            $avgUsage = round($monthUsage / $lastActiveDay);
        }

        $avgUsageFormatted = number_format($avgUsage);
        echo "<h5>$avgUsageFormatted $formattedUtilityType</h5>";   
    } else {
        echo "<h5>0 $formattedUtilityType</h5>";  
    }
}

function findAvgAllTimeUsage(){
    global $allTimeUsage, $dataBase, $formattedUtilityType;
    
    $today = new DateTime(date("Y-m-d"));
    $firstDay = new DateTime($dataBase[0]['date']);
    
    $dateDiff = $today->diff($firstDay);
    
    $totalDays = $dateDiff->format('%a');
    
    $avgUsage = round($allTimeUsage / $totalDays);
    
    $avgUsageFormatted = number_format($avgUsage);
    
    echo "<h5>$avgUsageFormatted $formattedUtilityType</h5>";
}



?>