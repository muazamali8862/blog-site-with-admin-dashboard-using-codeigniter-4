<?php
namespace App\Controllers;
use CodeIgniter\Controller;
class Login extends  Controller{
    public function index(){
        echo view('example-auth');
    }
}

?>