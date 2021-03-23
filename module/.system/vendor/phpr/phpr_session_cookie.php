<?php
namespace phpr\session\cookie;
use \SQLite3;

$GLOBALS['db_dir'] = __DIR__ . '/database/token_auth.sqlite3';

function SET($setup, $data)
{
    if ($setup == true) {
        #getDatabase
        $con            = new SQLite3($GLOBALS['db_dir']);
        $cookies_value  = md5($data['user_type'] . $data['user_id'] . date("Dd-Mm-Y H:i:s")) . md5($data['user_type'] . $data['user_id'] . date("Dd-Mm-Y H:i:s") . rand(135829, 999999));
        $cookies_ip     = md5($_SERVER['HTTP_USER_AGENT']);
        #setCOOKIES
        setcookie(
            "PHPRSSID",
            $cookies_value,
            time()+(3600*24*2), "/"
        );
        #insertTOdatabase
        $sql    = "INSERT INTO `settings_token_auth`(`user_type`, `user_id`, `app_name`, `token`, `token_ip`, `data`) VALUES ('" . $data['user_type'] . "','" . $data['user_id'] . "','" . $data['app_name'] . "','$cookies_value','$cookies_ip','" . json_encode($data) . "')";
        $con->query($sql);
        #checkIFthereISSomeIDS
        $sql    = "DELETE FROM `settings_token_auth` WHERE `user_type` = '" . $data['user_type'] . "' AND `user_id` = '" . $data['user_id'] . "' AND `app_name` = '" . $data['app_name'] . "' AND `token` != '$cookies_value'";
        $con->query($sql);
    } else {
        return false;
    }
}

function GET($setup, $app_name)
{
    if ($setup == true) {
        if (isset($_COOKIE["PHPRSSID"])) {
            $cookie         = $_COOKIE["PHPRSSID"];
            $cookies_ip     = md5($_SERVER['HTTP_USER_AGENT']);
            $cookie         = preg_replace("/[^a-zA-Z0-9]+/", "", $cookie);
            $con            = new SQLite3($GLOBALS['db_dir']);
            $sql            = "SELECT COUNT(`token`) as count FROM `settings_token_auth` WHERE `app_name` = '$app_name' AND `token` = '$cookie' AND `token_ip` = '$cookies_ip'";
            $result         = $con->querySingle($sql);

            if ($result == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function Fetch($app_name)
{
    if (isset($_COOKIE["PHPRSSID"])) {
        $cookie         = $_COOKIE["PHPRSSID"];
        $cookies_ip     = md5($_SERVER['HTTP_USER_AGENT']);
        $cookie         = preg_replace("/[^a-zA-Z0-9]+/", "", $cookie);
        $con            = new SQLite3($GLOBALS['db_dir']);
        $sql            = "SELECT * FROM `settings_token_auth` WHERE `app_name` = '$app_name' AND `token` = '$cookie' AND `token_ip` = '$cookies_ip'";
        $result         = $con->query($sql);

        return $result->fetchArray(SQLITE3_ASSOC);
    } else {
        return false;
    }
}

function FetchData($app_name)
{
    if (isset($_COOKIE["PHPRSSID"])) {
        $cookie         = $_COOKIE["PHPRSSID"];
        $cookies_ip     = md5($_SERVER['HTTP_USER_AGENT']);
        $cookie         = preg_replace("/[^a-zA-Z0-9]+/", "", $cookie);
        $con            = new SQLite3($GLOBALS['db_dir']);
        $sql            = "SELECT * FROM `settings_token_auth` WHERE `app_name` = '$app_name' AND `token` = '$cookie' AND `token_ip` = '$cookies_ip'";
        $result         = $con->query($sql);
        $data           = $result->fetchArray(SQLITE3_ASSOC);

        return json_decode($data['data'], true);
    } else {
        return false;
    }
}

function DEL($setup)
{
    if ($setup == true) {
        unset($_COOKIE['PHPRSSID']);
        setcookie('PHPRSSID', '', time() - 3600, "/");
    } else {
        return false;
    }
}
