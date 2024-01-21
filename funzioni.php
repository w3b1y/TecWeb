<?php
function clearInput($value){
        $value = trim($value);
        echo "$value";
        $value = strip_tags($value);
        echo "$value";
        $value = htmlentities($value);
        echo "$value";
        return $value;
    }

?>