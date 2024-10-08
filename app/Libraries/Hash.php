<?php
namespace App\Libraries;
class Hash {
    public static function make($password){
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public  static function check($password, $db_hashed_Password){
        if(password_verify($password,  $db_hashed_Password)){
            return  true;
    } else{
        return false;
        }
}
}
?>