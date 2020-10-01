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




