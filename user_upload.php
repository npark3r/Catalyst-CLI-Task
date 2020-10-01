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

/**
 * Process contents of a CSV file into an array and return it.
 *
 * @param string $file
 */
function readFromCSV($file): ?array {
    // Open CSV file.
    try {
        // Check if file name exists in directory.
        if ( !file_exists($file) ) {
            throw new Exception("File not found." . PHP_EOL);
        }
        // Attempt to open the file.
        $fp = fopen($file, 'r');
        if (! $fp) {
            throw new Exception("File open failed." . PHP_EOL);
        }

        // Read csv headers from first row.
        $key = fgetcsv($fp,"1024",",");

        // Parse CSV rows into array.
        $user_array = array();
        while ($row = fgetcsv($fp,"1024",",")) {
            $user_array[] = array_combine($key, $row);
        }
        // Close file.
        fclose($fp);

        return $user_array;

    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
        die;
    }
}

$users = [];
// Flag to check if arguments parsed are valid.
$argumentsIncorrect = FALSE;

// parsing CLI arguments
$shortopts  = "";
$shortopts .= "f:";   // Required value for csv file.
$shortopts .= "c";    // No value.
$shortopts .= "d";    // No value.
$shortopts .= "u:";   // Required value for MySQL user.
$shortopts .= "p:";   // Required value for MySQL password.
$shortopts .= "h:";   // Required value for MySQL hostname.

$longopts  = array(
    "file:",          // Required value for csv file.
    "create_table",   // No value for create table.
    "dry_run",        // No value for dry run.
    "help",           // No value for help.
);

// Get options.
$options = getopt($shortopts, $longopts);

//var_dump($options);

// If help option was specified -> print help text and die.
if (array_key_exists("help", $options) == TRUE) {
    fwrite(STDOUT,
    "This CLI accepts a CSV file as input, processes it and the contained users are added to a MySQL database table.
Options:
-f, --file            CSV file name for users
-c, --create_table    Create the users table and do nothing else
-d, --dry_run         Run the script with all functionality aside from DB actions
-u                    MySQL username
-p                    MySQL password
-he,--help            Print this help text
Example:

\$ php user_upload --file 'users.csv'\
        -u 'MySQL_username' -p 'MySQL_password' -h 'MySQL_host'" . PHP_EOL);
    die;
}

// This conditional will execute if the create_table argument was provided. A database called 'fakeuserdb' is created.
// A table, if it does not exist, called users is created. if it does exist then it is dropped and rebuilt. This flag
// causes the script to do nothing else.
if (array_key_exists("create_table", $options) == TRUE || array_key_exists("c", $options) == true) {
    // Check that DB credentials were supplied.
    if (array_key_exists("u", $options) == TRUE &&
        array_key_exists("p", $options) == TRUE &&
        array_key_exists("h", $options) == TRUE) {

        $hostname = strval($options["h"]);
        $username = strval($options["u"]);
        $password = strval($options["p"]);

        // Create DB connection.
        try {
            // Try to connect while suppressing warning.
            $conn = @mysqli_connect($hostname, $username, $password);
            // Check that the connection was successful.
            if (!$conn) {
                echo "Error: Unable to connect to MySQL." . PHP_EOL;
                echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
                echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
                die;
            }

            // Create the database if it does not exist.
            $sql = "CREATE DATABASE IF NOT EXISTS fakeuserdb";
            if ($conn->query($sql) === TRUE) {
                echo "Database created successfully or already existed" . PHP_EOL;
            } else {
                echo "Error creating database: " . $conn->error . PHP_EOL;
                // Close connection.
                mysqli_close($conn);
                die;
            }
            // Change to correct DB.
            mysqli_select_db($conn, "fakeuserdb");

            // Check if table users exists.
            $result = $conn->query("SHOW TABLES LIKE 'users'");

            if($result->num_rows == 0) {
                // SQL to create table.
                $sql1 = "CREATE TABLE users (
                            email VARCHAR(50) PRIMARY KEY,
                            name VARCHAR(30) NOT NULL,
                            surname VARCHAR(30) NOT NULL
                )";
                if ($conn->query($sql1) === TRUE) {
                    echo "Table \"users\" created successfully." . PHP_EOL;
                } else {
                    echo "Error creating table: " . $conn->error . PHP_EOL;
                }
            } else {
                $sql2 = "DROP TABLE users";
                if ($conn->query($sql2) === TRUE) {
                    // SQL query to recreate table.
                    $sql3 = "CREATE TABLE users (
                            email VARCHAR(50) PRIMARY KEY,
                            name VARCHAR(30) NOT NULL,
                            surname VARCHAR(30) NOT NULL
                )";
                    if ($conn->query($sql3) === TRUE) {
                        echo "Table \"users\" successfully recreated" . PHP_EOL;
                    } else {
                        echo "Error rebuilding table: " . $conn->error . PHP_EOL;
                    }
                } else {
                    echo "Error dropping table: " . $conn->error . PHP_EOL;
                }
            }
            // Close connection.
            mysqli_close($conn);
        } catch (Exception $e) {
            echo "Message: " . $e->getMessage();
            die;
        }
    } else {
        fwrite(STDOUT, "Create table was called but DB credentials were not supplied" . PHP_EOL);
        die;
    }
    die;
}

if (array_key_exists("file", $options) == TRUE || array_key_exists("f", $options) == TRUE) {
    if (array_key_exists("file", $options) == TRUE) {
        $filename = $options["file"];
        $users = readFromCSV($filename);

    } else if (array_key_exists("f", $options) == TRUE) {
        $filename = $options["f"];
        $users = readFromCSV($filename);
    }
}







