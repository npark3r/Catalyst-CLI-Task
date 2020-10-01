<?php
// This script is executed from the command line and and accepts a CSV
// file as input and processes it. The parsed file is inserted in a MySQL
// database specified by additional arguments.
// An example command line instruction run script is:
//      php user_upload.php --file=user.csv --dry_run

// The above example will run the script without accessing the DB
// Another example command line instruction run script is:

//      php user_upload.php --create_table

// The above example will cause the MySQL users table to be built (with no other action taken)

// Another example command line instruction run script is:

//      php user_upload.php --file=user.csv -u=MySQL_username -p=MySQL_password
//                          -h=MySQL_host

// The above example will cause the MySQL users table to be built (with no other action taken)

// calling --help will output the CLI help instructions

// parsing CLI arguments
$shortopts  = "";
$shortopts .= "f:";   // Required value for csv file.
$shortopts .= "c";    // No value.
$shortopts .= "d";    // No value.
$shortopts .= "u:";   // Required value for MySQL user.
$shortopts .= "p:";   // Required value for MySQL password.
$shortopts .= "h";    // No value.

$longopts  = array(
    "file:",          // Required value for csv file.
    "create_table",   // No value for create table.
    "dry_run",        // No value for dry run.
    "help",           // No value for help.
);
// Get options.
$options = getopt($shortopts, $longopts);

// If help option was specified print help text and die.
if (array_key_exists("help", $options) == true || array_key_exists("h", $options) == true) {
    fwrite(STDOUT,
    "This CLI accepts a CSV file as input, processes it and the contained users are added to a MySQL database table.
Options:
-f, --file            CSV file name for users
-c, --create_table    Create the users table and do nothing else
-d, --dry_run         Run the script with all functionality aside from DB actions
-u                    MySQL username
-p                    MySQL password
-h, --help            Print this help text
Example:

\$ php user_upload --file 'users.csv'\
        -u 'MySQL_username' -p 'MySQL_password' -h 'MySQL_host'\n");
    die;
}

var_dump($options);



