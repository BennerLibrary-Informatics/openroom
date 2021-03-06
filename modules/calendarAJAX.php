<?php
date_default_timezone_set('America/Chicago');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$date = time();

$day = isset($_GET["month"]) ? 0 : date('d', $date);
$month = isset($_GET["month"]) ? $_GET["month"] : date('m', $date);
$year = isset($_GET["year"]) ? $_GET["year"] : date('Y', $date);

//Determine prev and next links
$prevmonth = ($month == 1) ? 12 : $month - 1;
$prevyear = ($month == 1) ? $year - 1 : $year;
$nextmonth = ($month == 12) ? 1 : $month + 1;
$nextyear = ($month == 12) ? $year + 1 : $year;

$first_day = mktime(0, 0, 0, $month, 1, $year);
$title = date('F', $first_day);
$blank = date('w', $first_day);

//cal_days_in_month() function doesn't appear to be supported in php 7.0.7
if (!function_exists('cal_days_in_month')) {
        function cal_days_in_month($calendar, $month, $year) {
          return date('t', mktime(0, 0, 0, $month, 1, $year));
        }
}
else {
    $days_in_month = cal_days_in_month(0, $month, $year);
}

?>
<table id="calendarTable">
    <tr>
        <th colspan="7">
            <table id="calendarTableHeader" cellpadding="0" cellspacing="0">
                <tr>
                    <td id="prevmonth"><span onClick="ajaxFunction(<?php echo $prevmonth; ?>,<?php echo $prevyear; ?>);"
                                             id="calendarleftarrow"><img
                                    src="<?php echo $_SESSION["themepath"]; ?>images/calendarLeftArrow.png"
                                    alt="Previous Month"/></span></td>
                    <td id="month"><?php echo $title . " " . $year; ?></td>
                    <td id="nextmonth"><span onClick="ajaxFunction(<?php echo $nextmonth; ?>,<?php echo $nextyear; ?>);"
                                             id="calendarrightarrow"><img
                                    src="<?php echo $_SESSION["themepath"]; ?>images/calendarRightArrow.png"
                                    alt="Next Month"/></span></td>
                </tr>
            </table>
        </th>
    </tr>
    <tr>
        <td class="dayname">S</td>
        <td class="dayname">M</td>
        <td class="dayname">T</td>
        <td class="dayname">W</td>
        <td class="dayname">R</td>
        <td class="dayname">F</td>
        <td class="dayname">S</td>
    </tr>
    <?php
    $day_count = 1;
    echo "<tr>";
    while ($blank > 0) {
        echo "<td class=\"dayfiller\"></td>";
        $blank = $blank - 1;
        $day_count++;
    }

    $day_num = 1;

    while ($day_num <= date('t', mktime(0, 0, 0, $month, 1, $year))) {
        $classString = "calendarday";
        if ($day_num == date('d', $date) && $month == date('m', $date) && $year == date('Y', $date)) $classString = "currentcalendarday";
        $fromrange = mktime(0, 0, 0, $month, $day_num, $year);
        $torange = mktime(23, 59, 59, $month, $day_num, $year);
        echo "<td onClick=\"dayviewer('$fromrange','$torange','',this);closePopUp();\" class=\"$classString\">$day_num</td>";
        $day_num++;
        $day_count++;
        if ($day_count > 7) {
            echo "</tr><tr>";
            $day_count = 1;
        }
    }

    while ($day_count > 1 && $day_count <= 7) {
        echo "<td class=\"dayfiller\"></td>";
        $day_count++;
    }

    ?>
    </tr>
</table>
