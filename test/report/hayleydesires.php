<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$Mysql_servername3 = "localhost";
$Mysql_username3 = "s1074057_timesht";
$Mysql_password3 = "Edd5CXZMgmiu";
$Mysql_dbname3 = "s1074057_timesheet";

$connmysql = mysqli_connect($Mysql_servername3, $Mysql_username3, $Mysql_password3, $Mysql_dbname3);




echo "<html>";
echo "<head></head>";
echo "<body>";

echo '<img src="http://coastalmidwest.freight2020.com/custom-images/logo.png" width="388" height="93" alt="Coastal Midwest Transport">';

echo "<br><br>TIMESHEET REPORT FOR LAST 3 MONTHS<br><br>";

if(!empty($_POST['users'])) {
        $userID = $_POST['users'];
        echo 'You have chosen: ';
    } else {
        $userID="0";
    }
        




echo '<form method="post" action="">';
//echo '<input type="submit" value="Submit the form"/>';

function AddPlayTime($times){
    $minutes = 0; //declare minutes either it gives Notice: Undefined variable
    // loop throught all the times
    foreach ($times as $time) {
        list($hour, $minute) = explode(':', $time);
        $minutes += $hour * 60;
        $minutes += $minute;
        
    }

    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;

    return $hours.' hours '.$minutes.' minutes';
}

function AddTime($times){
    $minutes = 0;
    // loop throught all the times
    foreach ($times as $time) {
        list($hour, $minute) = explode(':', $time);
        $minutes += $hour * 60;
        $minutes += $minute;
        
    }

    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;

    return $hours.':'.$minutes;
}

function AverageTime($times){
    $totaltime = '';
    foreach($times as $time){
            $timestamp = strtotime($time);
            $totaltime += $timestamp;
    }
    if (count($times)!=0){
        $average_time = ($totaltime/count($times));
    }else{
        $average_time = 0;
    }

    return $average_time;
    
    
}


function Averageweekhours($times){
    $minutes = 0;
    $hours = 0;
    foreach($times as $time){
        list($hour, $minute) = explode(':', $time);
       
        $minutes += $minute;
        $hours += $hour;
    }
    
    if (count($times)!=0){
        $hours = floor($hours / count($times));
        $minutes = floor($minutes / count($times));
    }else{
        $hours = 0;
        $minutes = 0;
    }

    return $hours.' hours '.$minutes.' minutes';
    
}

function showcontent($times){
    $totaltime = 0;
    foreach($times as $time){
            $totaltime+=1;
            echo '<br> Week '.$totaltime;
            echo ' Total hours : '.$time;
    }
}
function isweekend($date){
    $date = strtotime($date);
    $date = date("l", $date);
    $date = strtolower($date);
//    echo $date;
    if($date == "saturday" || $date == "sunday") {
        return "true";
    } else {
        return "false";
    }
}

if (!$connmysql) {
    echo("Connection failed: " . mysqli_connect_error());
}else {
    
    
    $SQLuserlist = "SELECT id, first_name, last_name FROM user order by first_name";
//    $SQL = "SELECT U.id, first_name, last_name, roster_start_time,roster_end_time FROM user U, roster WHERE U.id = roster.user_id order by U.first_name";
    $resultuserlist = mysqli_query($connmysql, $SQLuserlist);
//    echo $SQLuserlist;
//    echo "<select name='users'>";
    echo "<select onchange='this.form.submit()' name='users'>";
    echo "<option>Select a User</option>";
    while ($rowuserlist = mysqli_fetch_array($resultuserlist)) {
        if ($userID == $rowuserlist["id"]){
            echo "<option value=".$rowuserlist["id"]." selected>".$rowuserlist["first_name"]." ".$rowuserlist["last_name"]."</option>";
        }else{
            echo "<option value=".$rowuserlist["id"].">".$rowuserlist["first_name"]." ".$rowuserlist["last_name"]."</option>";
        }
    }
    
    echo "</select>";
    
    echo"<br><br>";
    
    $SQL = "SELECT U.id, first_name, last_name, roster_start_time,roster_end_time FROM user U, roster WHERE U.id = roster.user_id AND U.id = ".$userID."";
//    $SQL = "SELECT U.id, first_name, last_name, roster_start_time,roster_end_time FROM user U, roster WHERE U.id = roster.user_id order by U.first_name";
    $result = mysqli_query($connmysql, $SQL);
//    echo $SQL;
    
    while ($row = mysqli_fetch_array($result)) {
        echo $row["first_name"]." ".$row["last_name"]."<br>";
        $RosterStart = new DateTime($row["roster_start_time"]);
        $RosterEnd = new DateTime($row["roster_end_time"]);
        echo "Roster : ".date_format($RosterStart,'H:i')." - ".date_format($RosterEnd,'H:i')."<br>";
        
        $SQLuser = "SELECT * FROM user U, user_attendance UA
                    WHERE U.id = ".$row["id"]."
                    AND U.id = UA.userid
                    AND UA.login_time >= CURRENT_DATE - INTERVAL 3 MONTH
                    order by UA.login_time
                    ";
//        echo "<br>".$SQLuser."<br>";
        $SQLuserresult = mysqli_query($connmysql, $SQLuser);
        if (mysqli_num_rows($SQLuserresult)>0){
            echo "<table border=1 style='text-align:center'>";
            echo "<th>Date</th>";
            echo "<th>Normal Hours</th>";
            echo "<th>Overtime Hours</th>";
            echo "<th>TOTAL Hours</th>";
            echo "<th>LOGIN Time</th>";
            echo "<th>LOGOUT Time</th>";
//            echo "<th>Roster Start Time</th>";
//            echo "<th>Roster End Time</th>";
            
            $countWE = 0;
            $times = array();
            $timesWE = array();
            $weekday=array();
            $weeks=array();
            $weeknumberrecord = 0;
            
            while ($userresult = mysqli_fetch_array($SQLuserresult)) {
                $userdate= $userresult["login_time"];
                $mystarttime = new DateTime($userresult["login_time"]);
                $mystoptime = new DateTime($userresult["logout_time"]);
                $myRosterstarttime = new DateTime(date("Y-M-d",strtotime($userdate))." ".$row["roster_start_time"]);
                $myRosterstoptime = new DateTime(date("Y-M-d",strtotime($userdate))." ".$row["roster_end_time"]);
                
                
                $beforeRosterH = 0;
                $beforeRosterM = 0;
                
                $afterRosterH = 0;
                $afterRosterM = 0;
                
                $beforeRosterHours="00 h 00 min";
                if (date_format($mystarttime,'H:i:s')<date_format($myRosterstarttime,'H:i:s')){
                    if (date_format($myRosterstarttime,'H:i:s')<date_format($mystoptime,'H:i:s')){
                       $beforeRoster = $mystarttime->diff($myRosterstarttime);
                        $beforeRosterHours = $beforeRoster->format("%H h %I min");
                        $beforeRosterH =$beforeRoster->format("%H");
                        $beforeRosterM =$beforeRoster->format("%I"); 
                    }else{
                        $beforeRoster = $mystarttime->diff($mystoptime);
                        $beforeRosterHours = $beforeRoster->format("%H h %I min");
                        $beforeRosterH =$beforeRoster->format("%H");
                        $beforeRosterM =$beforeRoster->format("%I");
                    }
                    
                }
                
                $afterRosterHours="00 h 00 min";
                if (date_format($mystoptime,'H:i:s')>date_format($myRosterstoptime,'H:i:s')){
                    $afterRoster = $mystoptime->diff($myRosterstoptime);
                    $afterRosterHours = $afterRoster->format("%H h %I min");
                    $afterRosterH =$afterRoster->format("%H");
                    $afterRosterM =$afterRoster->format("%I");
                }
                
                
                $overtime = ($beforeRosterH+$afterRosterH+intdiv(($beforeRosterM+$afterRosterM),60))." h ".(($beforeRosterM+$afterRosterM)%60)." min";
                
                
                
                
                if (date_format($mystarttime,'H:i:s')>date_format($myRosterstarttime,'H:i:s')){
                    $normalhoursstart = $mystarttime;
                }else{
                    $normalhoursstart = $myRosterstarttime;
                }
                
                if (date_format($mystoptime,'H:i:s')<date_format($myRosterstoptime,'H:i:s')){
                    $normalhoursstop = $mystoptime;
                }else{
                    $normalhoursstop = $myRosterstoptime;
                }
                
                if ($normalhoursstart<$normalhoursstop){
                    $normalhoursdiff = $normalhoursstart->diff($normalhoursstop);
                    $normalhours = $normalhoursdiff->format("%H h %I min");
                }
                else {
                    $normalhours = '00 h 00 min';
                }
                
                
                if ($mystarttime<$mystoptime){
                    $difference = $mystarttime->diff($mystoptime);
                    $mytotaltime = $difference->format("%H h %I min");
                    $times[]= $difference->format("%H:%I");
                    if (isweekend($userdate)== 'true'){
                        $timesWE[]= $difference->format("%H:%I");
                        $countWE = $countWE + 1;
                    }
                    
                    
                    $weeknumber = idate('W', strtotime($userdate));
                
                    if (($weeknumberrecord>0) and ($weeknumber>$weeknumberrecord) ){
                        
                        $weeks[]= AddTime($weekday);
                        $weekday=array();

                    }
                        $weekday[]=$difference->format("%H:%I");
                        $weeknumberrecord=$weeknumber;
                    
                    
                    
                }else{
                    $mytotaltime = '00 h 00 min';
                }
                
                
                
                if (isweekend($userdate)== 'true'){
                    $linecolor = 'bgcolor="red"';
                }else{
                    $linecolor = 'bgcolor="green"';
                }        
                        
                echo "<tr ".$linecolor."><td>Week ".$weeknumber." - ".date("l d M Y",strtotime($userdate))."</td>";
                echo "<td>".$normalhours."</td>";
                echo "<td>".$overtime."</td>";
                echo "<td>".$mytotaltime."</td>";
                echo "<td>".date_format($mystarttime,'Y-m-d H:i')."</td>";
                echo "<td>".date_format($mystoptime,'Y-m-d H:i')."</td>";
//                echo "<td>".$row["roster_start_time"]."</td>";
//                echo "<td>".$row["roster_end_time"]."</td>";
                echo "</tr>";
            }
            
            echo "</table><br><br>"; 
            
            echo "<br>Total time Worked : ".AddPlayTime($times);
            
            echo "<br><br>Average time Worked : ".date('H:i:s',AverageTime($times));
            
            echo "<br><br>Number of Sat/Sun Worked : ".count($timesWE);
            
            echo "<br><br>Average time Worked on Sat/Sun : ".date('H:i:s',AverageTime($timesWE))."<br>";
            
//            $averageweek = AverageTime($weeks);
            
            echo showcontent($weeks);
            
            
            
            echo "<br><br>Average amount of hours worked per Week : ".Averageweekhours($weeks);
            
        }else{
            echo "<br>NO RECORDINGS FOR THE LAST 3 MONTHS<br><br>";
        }
        
        
        echo"<br>--------------------------------------------------------------------------------------<br>";
    }
}

echo "</form>";
echo "</body>";
echo "</html>";
