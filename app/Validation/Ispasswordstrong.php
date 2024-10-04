<?php

namespace App\Validation;

class Ispasswordstrong
{
   public function Is_password_strong($password): bool
   {
    $password = trim($password);
    if(!preg_match('/^(?=.*[\W])(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{5,20}$/',  $password)) {
        return false;
    }
    return  true;
   }

}
