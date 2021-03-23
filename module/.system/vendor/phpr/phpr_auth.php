<?php

namespace phpr\auth;

#default login system phpr
@$GLOBALS['db'] = mysqli_connect(DB_SETUP['HOST'], DB_SETUP['USER'], DB_SETUP['PASS'], DB_SETUP['DB']);
#Return true/false
function login($username, $password)
{
    $username = htmlentities(strtolower($username));
    $password = htmlentities($password);
    $password = md5($password);
    $query = "SELECT COUNT(*) as count FROM `users` WHERE `username` = '$username' AND `password` = '$password'";
    $login = mysqli_fetch_array(mysqli_query($GLOBALS['db'], $query));
    if ($login[0] == 1) {
        return true;
    } else {
        return false;
    }
}

#Return true=success,1=character not allowed,2=email contains forbiden words,3=username already exist
function register($username, $password, $email)
{
    if (preg_match('/[^a-z_\-0-9]/i', $username)) {
        if (preg_match('/^[\w\.]+$/', $email)) {
            $username = htmlentities(strtolower($username));
            $password = htmlentities($password);
            $password = md5($password);
            $query = "SELECT COUNT(*) as count FROM `users` WHERE `username` = '$username'";
            $users = mysqli_fetch_assoc(mysqli_query($GLOBALS['db'], $query));
            if ($users['count'] == '1') {
                return 3;
            } else {
                $query_register = "INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES (NULL, '$username', '$password', '$email')";
                mysqli_query($GLOBALS['db'], $query_register);
                return true;
            }
        } else {
            return 2;
        }
    } else {
        return 1;
    }
}
