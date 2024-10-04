<?php
namespace App\Validation;
use App\Libraries\Hash;
use App\Libraries\CIAuth;
use App\Models\User;

class Iscurrentpasswordcorrect {
    public function check_current_password($password) : bool{
        $password = trim($password);
        $user_id = CIAuth::id();
        $user = new User();
        $user_info = $user->where('id', $user_id)->first();
        if(!Hash::check($password, $user_info['password'])){ // Access password property using array syntax
            return false;
        } else{
            return true;
        }
    }
}
?>