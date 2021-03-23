<?php
namespace phpr\Apps\Run;

$env_file = file_get_contents(MODL_FOLDER."/settings.env");
$env_file = json_decode($env_file,true);
#TIMEZONE
date_default_timezone_set($env_file['TimeZone']);

#APP INSIDE APPS FODLER!
$global_apps_array = array();
foreach (glob(APPS . "/*") as $urls) {
    $last_word_start = strrpos($urls, '/') + 1;
    $last_word = substr($urls, $last_word_start);
    @$global_apps_array[$last_word] .= APPS . '/' . $last_word . '/';
}
$GLOBALS['installed_apps'] = $global_apps_array;

#YOUR MAIN URLS
$GLOBALS['global_url']      = $env_file['URL']   ;
$GLOBALS['uri_folder']      = $env_file['Folder'];
$base_folder                = $env_file['Folder'];

#FINGERPRINT, YOU CAN USE IT OR NOT TO USE IT.
$GLOBALS['fingerprint']     = $env_file['Fingerprint'];

#DATABASE SETUP
$global_database = array();
foreach($env_file['Database_Settings'] as $key => $dbs){
    @$global_database[$key] .= $dbs;
}
$GLOBALS['database_setup']  = $global_database;

$global_limiter = array();
foreach($env_file['LoadLimiter'] as $load_key => $load_val){
    @$global_limiter[$load_key] .= $load_val;
}
define("LIMITER" , $global_limiter);

#GLOBAL DEFINE
define("GLOBALURL"  ,$GLOBALS['global_url'].$GLOBALS['uri_folder']          );
define("URIFOLDER"  ,$GLOBALS['uri_folder']                                 );
define("DB_SETUP"   ,$GLOBALS['database_setup']                             );
define("FINGERPRINT",$GLOBALS['fingerprint']                                );
define("GLOBAPPSARRAY",$GLOBALS['installed_apps']                                );
#DO NOT CHANGE
function App($app_to_run,$path){
    if(array_key_exists($app_to_run,$GLOBALS['installed_apps'])){
        return $GLOBALS['installed_apps'][$app_to_run]."$path/view.php" ?? false;
    }else{
        return false;
    }
}

#DO NOT CHANGE
function htaccessCHANGE($base_folder){
    $ht_updates = fopen(ROOT_FOLDER . '/.htaccess', "w") or die("Unable to open file!");
    $ht_int = GLOBALURL;
    $ht_new     = "
    <IfModule mod_rewrite.c>
    DirectoryIndex index.php

    # set your rewrite base
    RewriteEngine on
    #EDSTART#
    # Edit this in your init method too if you script lives in a subfolder
    RewriteBase " . $base_folder . "
    
    # Deliver the folder or file directly if it exists on the server
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
     
    # Push every request to index.php
    RewriteRule ^(.*)$ index.php [QSA]
    </IfModule>
      ";
    $ht_text    = $ht_new;
    fwrite($ht_updates, $ht_text);
    fclose($ht_updates);
}
if (!file_exists(ROOT_FOLDER . '/.htaccess')) {
    htaccessCHANGE($base_folder);
}elseif(file_exists(ROOT_FOLDER . '/.htaccess') && strtotime(filemtime(ROOT_FOLDER . '/.htaccess')) <= strtotime('-5 Days', strtotime(date('d-m-Y')))){
    htaccessCHANGE($base_folder);
}

#DO NOT CHANGE
include __DIR__ . '/autoload.php';