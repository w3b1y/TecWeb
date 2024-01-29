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

function date2txt(DateTime $date){
    $txt="";
    if($date->format('H')!='0'){
        $txt.= $date->format('H')." ore ";
    }
    if($date->format('m')!='0'){
        $txt.= $date->format('m')." minuti ";
    }
    return $txt;
}
?>