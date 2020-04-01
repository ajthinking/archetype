<?php

if (! function_exists('r')) {
    function r($name)
    { 
        return require(__DIR__ . "/sketches/$name.php");
    }
}