<?php
// This script is executed from the command line and outputs the integers
// 1 to 100. Any integer that is divisible by 3 is replaced with the word "foo", divisible
// by 5 with the word "bar", and divisible by both with the word "foobar"
// Run the following with a terminal in the script directory:
//      php foobar.php

// Script is over-engineered to account for future changes to foo and bar values.

// Definitions for values are adjustable.
define("FOO", 3);
define("BAR", 5);
define("RANGE", 100);
define("START", 1);

/**
 * Check if an integer is cleanly divisible by another integer.
 *
 * @param int $value
 * @param int $divisor
 * @return bool
 */
function divisibleBy($value, $divisor) : bool {
    if ($value % $divisor == 0) {
        return TRUE;
    } else {
        return FALSE;
    }
}

// Iterate over range to print output.
foreach (range(START, RANGE) as $number) {
    if (divisibleBy($number, FOO) && divisibleBy($number,BAR)) {
        if($number == RANGE) {
            echo "foobar";
        } else {
            echo "foobar, ";
        }
    } else if (divisibleBy($number, FOO)) {
        if($number == RANGE) {
            echo "foo";
        } else {
            echo "foo, ";
        }
    } else if (divisibleBy($number, BAR)) {
        if($number == RANGE) {
            echo "bar\n";
        } else {
            echo "bar, ";
        }
    } else {
        if($number == RANGE) {
            echo $number . "\n";
        } else {
            echo $number . ", ";
        }
    }
}

