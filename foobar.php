<?php
// This script is executed from the command line and outputs the integers
// 1 to 100. Any integer that is divisible by 3 is replaced with the word "foo", divisible
// by 5 with the word "bar", and divisible by both with the word "foobar"
// Run the following with a terminal in the script directory:
//      php foobar.php

foreach (range(1, 100) as $number) {
    if ($number % 3 == 0 && $number % 5 == 0) {
        if($number == 100) {
            echo "foobar";
        } else {
            echo "foobar, ";
        }
    } else if ($number % 3 == 0 ) {
        if($number == 100) {
            echo "foo";
        } else {
            echo "foo, ";
        }
    } else if ($number % 5 == 0 ) {
        if($number == 100) {
            echo "bar\n";
        } else {
            echo "bar, ";
        }
    } else {
        if($number == 100) {
            echo $number . "\n";
        } else {
            echo $number . ", ";
        }
    }
}

