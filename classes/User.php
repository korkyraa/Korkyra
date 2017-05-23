<?php

class User
{

    public static function logIn($email, $pass)
    {
        if (DB::query('SELECT email FROM users WHERE email = :email', array(':email' => $email)))
        {
            if (password_verify($pass, DB::query('SELECT password FROM users WHERE email = :email', array(':email' => $email))[0]['password']))
            {
                $user = DB::query('SELECT id, username FROM users WHERE email = :email', array(':email' => $email));

                echo "<pre>";
                print_r($user);
                echo "</pre>";

                $_SESSION['user_id'] = $user[0]['id'];
                $_SESSION['username'] = $user[0]['username'];

                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public static function isLoggedIn()
    {
        if (isset($_SESSION['user_id']) && isset($_SESSION['username']))
        {
            return $_SESSION['user_id'];
        }

        return false;
    }

    public static function createAccount($email, $username, $pass)
    {



    }

}