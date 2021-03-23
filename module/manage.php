<?php
define("ROOT_FOLDER",'../');
define("MODL_FOLDER",__DIR__);
define("APPS"       ,__DIR__.'/apps');
include __DIR__ . '/.system/system.php';
$val = getopt(null, ["name:"]);
$opt = getopt(null, ["opt:"]);
if ($opt['opt'] == 'migrate') {
    if ($val == null) {
        foreach (glob(__DIR__ . "/apps/*/models.php") as $models) {
            include $models;
        }
    } elseif ($val != null) {
        if (file_exists(__DIR__ . "/apps/" . $val['name'])) {
            foreach (glob(__DIR__ . "/apps/" . $val['name'] . "/models.php") as $models) {
                include $models;
            }
        } else {
            echo "\nApp [" . "\033[33m" . $val['name'] . "\033[0m" . "]"."\033[31m"." Not Found!"."\033[0m"."\n\n";
        }
    }
}

if ($opt['opt'] == 'createapp') {
    if ($val == null) {
        echo "Please specify the name : phpr-create yourapp_name \n";
    } elseif ($val != null) {
        if (!file_exists(__DIR__ . "/apps/" . $val['name'])) {
            exec('mkdir -p apps/' . $val['name'] . ';cp -a .system/vendor/phpr/quick_setup/basic/* apps/' . $val['name'] . ';', $output);
            echo "\nApp [" . "\033[33m" . $val['name'] . "\033[0m" . "]"."\033[32m"." Created! "."\033[0m"."\n\nCommand Available : \n        phpr-migrate " . $val['name'] . "\n        phpr-delete " . $val['name'] . "\n\n";
        } else {
            echo "\nApp [" . "\033[33m" . $val['name'] . "\033[0m" . "]"."\033[31m"." Already Exist!"."\033[0m"."\n\n";
        }
    }
}


if ($opt['opt'] == 'deleteapp') {
    if ($val == null) {
        echo "Please specify the name : phpr-create yourapp_name \n";
    } elseif ($val != null) {
        if (file_exists(__DIR__ . "/apps/" . $val['name'])) {
            exec('rm -r apps/' . $val['name'] . ';', $output);
            echo "\n" . 'App [' . "\033[33m" . $val['name'] . "\033[0m" . '] '. "\033[31m" .'Deleted' . "\033[0m" . "\n\n";
        } else {
            echo "\n" . 'No App Named [' . "\033[33m" . $val['name'] . "\033[0m" . "]\n\n";
        }
    }
}
