<?php 

namespace oQuiz\Utils;

class UserSession {

    public function __construct()
    {
        
    }

    public static function isConnected() {

        if(isset($_SESSION['user']))
        {
            $result = true;
        } 
        else 
        { 
            $result  = false;
        }
        return $result;
    }

    public static function getUser() {

        if(self::isConnected())
        {
            $result = $_SESSION['user'];
        } 
        else 
        {
            $result = false;
        }
        return $result;
    }

    public static function getRoleId() {

        if(self::isConnected()) 
        {
            $result = $_SESSION['user']['role']['id'];
        }
        else 
        {
            $result = false;
        }
        return $result;
    }

    public static function isAdmin(){

        if($_SESSION['user']['role']['id'] === 2) 
        {
            $result = true;
        }
        else 
        {
            $result = false;
        }
        return $result;
    }
}