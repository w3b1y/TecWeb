<?php
function clearInput($value){
        $value = trim($value);
        $value = strip_tags($value);
        $value = htmlentities($value);
        return $value;
    }

function getDateInterval(string $timeInterval) {
    $timeInterval = str_replace('PT-', 'PT', $timeInterval);
    list($hours, $minutes, $seconds) = explode(':', $timeInterval);
    return new DateInterval("PT{$hours}H{$minutes}M");
}

function date2txt(DateTime $date){
    $txt="";
    if($date->format('H')=='1') $txt.= $date->format('H')." ora ";
    else if($date->format('H')!='0') $txt.= $date->format('H')." ore ";

    if($date->format('i')=='1') $txt.= $date->format('i')." minuto ";
    else if($date->format('i')!='0') $txt.= $date->format('i')." minuti ";
    return $txt;
}

function sortDate($array, $new) {
    $length = count($array);

    if ($length === 0) {
        return 0;
    }

    $low = 0;
    $high = $length - 1;

    while ($low <= $high) {
        $mid = (int)(($low + $high) / 2);
        $compare = $new <=> $array[$mid];

        if ($compare < 0) {
            $high = $mid - 1;
        } elseif ($compare > 0) {
            $low = $mid + 1;
        } else {
            return $mid;
        }
    }
    return $low;
}
?>