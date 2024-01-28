<?php
function clearInput($value){
        $value = trim($value);
        $value = strip_tags($value);
        $value = htmlentities($value);
        return $value;
    }

function getDateInterval(string $timeInterval) {
    list($hours, $minutes, $seconds) = explode(':', $timeInterval);
    return new DateInterval("PT{$hours}H{$minutes}M");
}
?>