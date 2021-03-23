<?php

namespace phpr\database\models;

@$GLOBALS['db'] = mysqli_connect(DB_SETUP['HOST'], DB_SETUP['USER'], DB_SETUP['PASS'], DB_SETUP['DB']);
if ($GLOBALS['db'] === false) {
    echo "\n\033[31m" . "Could not connect to database" . "\033[0m" . " Aborting!!!\n\n";
    die();
}

if(LIMITER['Status'] == 'enabled'){
    $connection_limit   = LIMITER['Requests'];
    $connection_active  = mysqli_fetch_assoc(mysqli_query($GLOBALS['db'], "SELECT COUNT(*) as count FROM `con_limiter` WHERE `time` = '".strtotime(date('H:i'))."'"));
    $connection_active  = $connection_active['count'];
    
    $threads_limit      = LIMITER['Threads'];
    $threads_active     = mysqli_fetch_assoc(mysqli_query($GLOBALS['db'], "SHOW STATUS WHERE `variable_name` = 'Threads_connected'"));
    $threads_active     = $threads_active['Value'];
    
    if($threads_active >= $threads_limit){
        include __DIR__.'/default_page/error_thread.html';
        die();
    }

    if($connection_active >= $connection_limit){
        include __DIR__.'/default_page/error_connection.html';
        die();
    }else{
        mysqli_query($GLOBALS['db'], "INSERT INTO `con_limiter`(`time`) VALUES ('".strtotime(date('H:i'))."')");
    }
    
}
/*
DataModels(
        'test_bambang', 
            array(
                "name1" => "INT(80) NOT NULL AUTO_INCREMENT",
                "name2" => "INT(80) NOT NULL AUTO_INCREMENT",
            )
        );
*/
function DataModels($active, $table_name, $input_array)
{
    if ($active == true) {
        if ($input_array != false) {
            #checking data table exsistance
            $check_query   = "select 1 from `$table_name` LIMIT 1";
            $check_query   = mysqli_query($GLOBALS['db'], $check_query);
            $input_array   = $input_array;
            if ($check_query !== FALSE) {
                echo "\033[33m" . "Migrating [" . $table_name . "]\n" . "\033[37m";
                $data_table = "DESCRIBE $table_name";
                $data_table = mysqli_query($GLOBALS['db'], $data_table);
                $table_array = array();
                foreach ($data_table as $data_table) {
                    $table_array[] .= $data_table['Field'];
                }
                foreach ($table_array as $ta) {
                    $data_exist = '';
                    $data_value = '';
                    foreach ($input_array as $key => $ia) {
                        if ($key != 'KEY') {
                            if ($ta == $key) {
                                $data_exist .= $key;
                                $data_value .= $ia;
                            }
                        }
                    }
                    if ($ta != $data_exist) {
                        #on this list database will be deleted!
                        $query_z = "ALTER TABLE `$table_name` DROP `$ta`";
                        mysqli_query($GLOBALS['db'], $query_z);
                        echo "Column" . "\033[35m" . " $ta " . "\033[31m" . "Deleted" . "\033[37m" . "\n";
                    }

                    if ($ta == $data_exist) {
                        #on this list database will be updated!
                        $query_y = "ALTER TABLE `$table_name` CHANGE `$ta` `$ta` $data_value";
                        mysqli_query($GLOBALS['db'], $query_y);
                        echo "Column" . "\033[35m" . " $ta " . "\033[32m" . "Updated" . "\033[37m" . "\n";
                    }
                }

                foreach ($input_array as $xx => $ix) {
                    if ($xx != 'KEY' && $xx != 'UNIQUE') {
                        $data_exist = '';
                        $data_value = '';
                        foreach ($table_array as $ta) {
                            if ($ta == $xx) {
                                $data_exist .= $xx;
                                $data_value .= $ix;
                            }
                        }
                        if ($xx != $data_exist) {
                            #on this list database will be added!
                            $query_x = "ALTER TABLE `$table_name` ADD `$xx` $ix ";
                            mysqli_query($GLOBALS['db'], $query_x);
                            echo "Column" . "\033[35m" . " $xx " . "\033[36m" . "Added" . "\033[37m" . "\n";
                        }
                    }
                }
                #->add if inside descripted but not in the database
            } else {
                echo "\033[33m" . "Executing [" . $table_name . "]\n" . "\033[37m";
                $ir_res = '';
                foreach ($input_array as $key => $ia) {
                    if ($key == 'KEY') {
                        $ir_res .= " " . $ia . ", ";
                    } elseif ($key == 'UNIQUE') {
                        $ir_res .= " " . $ia . ", ";
                    } else {
                        $ir_res .= "`" . $key . "` " . $ia . ", ";
                    }
                }
                $ir_res = rtrim($ir_res, ", ");
                $query_n = "CREATE TABLE `" . DB_SETUP['DB'] . "`.`$table_name` ( $ir_res ) ENGINE = InnoDB";
                mysqli_query($GLOBALS['db'], $query_n);
                echo "Table" . "\033[35m" . " $table_name " . "\033[36m" . "Created" . "\033[37m" . "\n";
                #if not exist
                #->create    
            }
        } elseif ($input_array == false) {
            echo "\033[33m" . "Executing [" . $table_name . "]\n" . "\033[37m";
            $check_query    = "select 1 from `$table_name` LIMIT 1";
            $check_query    = mysqli_query($GLOBALS['db'], $check_query);
            if ($check_query !== FALSE) {
                $query_key = "SET FOREIGN_KEY_CHECKS = 0";
                $query_main = "DROP TABLE `$table_name`";
                mysqli_query($GLOBALS['db'], $query_key);
                mysqli_query($GLOBALS['db'], $query_main);
                echo "Table" . "\033[35m" . " $table_name " . "\033[31m" . "Deleted" . "\033[37m" . "\n";
            } else {
                echo "Table does not exist,\nModels turned " . "\033[36m" . "false" . "\033[0m" . "\nPlease set array data if you want to create\n";
            }
        }
    } else {
        echo "\033[33m" . "Table [" . $table_name . "] Excluded from Migrations\n" . "\033[37m";
        echo "Models turned " . "\033[36m" . "OFF" . "\033[0m" . "\nPlease set true if you want to migrate\n";
    }
    echo "\n\033[0m";
}
