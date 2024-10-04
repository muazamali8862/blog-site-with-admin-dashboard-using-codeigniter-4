<?php

namespace App\Controllers;

use App\Controllers\BaseController;
// use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;
use App\Models\Passwordresettoken;
use Carbon\Carbon;

class AuthController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIFunction'];
    // register form
    public function registerform(){
        $data = [
            'pagetitle' => 'Register Page',
            'validation' => null,

        ];
        return view('backend/pages/auth/register',  $data);
    }
    // register
    public function register(){
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $isvalid = $this->validate([
            'name' => [
                'rules' => 'required',
                'errors'  => [
                    'required' => 'Please enter your name'
                    ]
                ],
                'email' => [
                'rules' => 'required|is_unique[users.email]|valid_email',
                'errors' => [
                    'required' => 'Email is required',
                    'is_unique' => 'Email already exists.',
                    'valid_email' => 'Invalid Email'
                ]
            ],
            'username' => [
                'rules' => 'required|is_unique[users.username]',
                'errors'  => [
                    'required' => 'Username is required',
                    'is_unique' => 'Username already exists.'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[8]',
                    'errors'  => [
                        'required' => 'Password is required',
                        'min_length' => 'Password must be at least 8 characters long'
                    ],
                    ],
        ]);

        if (!$isvalid) {
            return view('backend/pages/auth/register', [
                'pagetitle'  => 'Register Page',
                'validation' => $this->validator,
            ]);
        } else{
            date_default_timezone_set('Asia/karachi');
            $data = [
                'name' => $request->getVar('name'),
                'username' =>  $request->getVar('username'),
                'email' =>  $request->getVar('email'),
                'password'  =>  password_hash($request->getVar('password'), PASSWORD_DEFAULT),
            ];
            $user = new  User();
            $insert = $user->insert($data);
            if($insert) {
                return redirect()->route('admin.register.form')->with('success', ' User Registered successfully');
            } else{
                return redirect()->route('admin.register.form')->with('error',  'Failed to Register User');
            }
        }

    }
    // login form
    public function loginForm()
    {
        $data = [
            'pagetitle' => 'Login Page',
            'validation' => null,

        ];
        return view('backend/pages/auth/login',  $data);
    }
    // login validation
    public function login()
    {
        $fieldtype = filter_var($this->request->getVar('login_id'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if ($fieldtype == 'email') {
            $isvalid = $this->validate([
                'login_id' => [
                    'rules' => 'required|valid_email|is_not_unique[users.email]',
                    'errors' => [
                        'required' => 'Email is required',
                        'valid_email' => 'Invalid email',
                        'is_not_unique' => 'Email is not exist'
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password is required'
                    ]
                ]
            ]);
        } else {
            $isvalid = $this->validate([
                'login_id' => [
                    'rules' => 'required|is_not_unique[users.username]',
                    'errors' => [
                        'required' => 'Username is required',
                        'is_not_unique' => 'Email is not Username'
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password is required'
                    ]
                ]
            ]);
        }
        if (!$isvalid) {
            return view('backend/pages/auth/login', [
                'pagetitle'  => 'Login Page',
                'validation' => $this->validator,
            ]);
        } else {
            $user = new User();
            $userinfo = $user->where($fieldtype,  $this->request->getVar('login_id'))->first();
            $checkpassword = Hash::check($this->request->getVar('password'), $userinfo['password']);
            if (!$checkpassword) {
                return redirect()->route('admin.login.form')->with('error',  'Invalid Password')->withInput();
            } else {
                CIAuth::setCIAuth($userinfo);
                return redirect()->route('admin.home');
            }
        }
    }

    // forget  password
    public function forgetform()
    {
        $data = array(
            'pagetitle'   => 'Forget Password',
            'validation'  => null,
        );
        return  view('backend/pages/auth/forgot', $data);
    }

    // Send password link
    public function sendpasswordresetlink()
    {
        $isvalid = $this->validate([
            'email' => [
                'rules' => 'required|is_not_unique[users.email]|valid_email',
                'errors' => [
                    'required' => 'Email is required',
                    'is_not_unique' => 'Email does not exists.',
                    'valid_email' => 'Invalid Email'
                ]
            ],
        ]);
        if (!$isvalid) {
            return  view('backend/pages/auth/forgot', [
                'pagetitle'   => 'Forget Password',
                'validation'  => $this->validator,
            ]);
        } else {
            // get user data
            $user = new User();
            $userinfo = $user->asObject()->where('email',  $this->request->getVar('email'))->first();

            //    generate token
            $token = bin2hex(openssl_random_pseudo_bytes(65));

            //    get reset password token
            $password_reset_token = new Passwordresettoken();
            $isoldtokenexist = $password_reset_token->asObject()->where('email', $userinfo->email)->first();

            if ($isoldtokenexist) {
                // update  token
                date_default_timezone_set('Asia/karachi');
                $password_reset_token->where('email',  $userinfo->email)
                    ->set([
                        'token' => $token,
                        'created_at' => Carbon::now(),
                    ])
                    ->update();
            } else {
                date_default_timezone_set('Asia/karachi');
                $password_reset_token->insert([
                    'email' => $userinfo->email,
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]);
            }

            $actionlink = url_to('admin.reset-password', $token);
            $mail_data = array(
                'actionLink' => $actionlink,
                'user' => $userinfo,
            );
            $to = $userinfo->email;
            $subject = 'Account Activation Link';
            $view = \Config\Services::renderer();
            $message =  $view->setVar('mail_data', $mail_data)->render('backend/email-templates/forgot-email-template');
            $email = \config\Services::email();
            $email->setTo($to);
            $email->setFrom('mkcreater333@gmail.com',  'Axcel World');
            $email->setSubject($subject);
            $email->setMessage($message);
            $email->setPriority(1); // Set email priority to high
            if ($email->send()) {
                return redirect()->route('admin.forgot.form')->with('success', 'We have e-mailed your password link.');
            } else {
                return redirect()->route('admin.forgot.form')->with('error', 'Error sending email.');
            }
        }
    }
    // reset password
    public  function resetPassword($token){
        date_default_timezone_set('Asia/karachi');
        $password_reset_token = new Passwordresettoken();
        $check_token = $password_reset_token->asObject()->where('token', $token)->first();
        if(!$check_token){
            return redirect()->route('admin.forgot.form')->with('error', 'Invalid token. Request another password link');

        } else{
            $diffmins = Carbon::createFromFormat('Y-m-d H:i:s', $check_token->created_at)->diffInMinutes(Carbon::now());
            if($diffmins > 15){
                return redirect()->route('admin.forgot.form')->with('error', 'Token  expired. Request another password link');
            } else{
                return view('backend/pages/auth/reset', [
                    'pagetitle'   => 'Reset Password',
                    'validation' => null,
                    'token' => $token
                ]);
                
            }
        }
    }
    // reset  password handler
    public function resetPasswordhandler($token){
       $isvalid = $this->validate([
        'new_password' => [
            'rules' =>  'required|min_length[5]|max_length[20]|Is_password_strong[new_password]',
            'errors' => [
                'required' => 'Please enter a New password',
                'min_length' => 'New Password must be at least 5 characters',
                'max_length' => 'New Password must not be more than 20 characters',
                'Is_password_strong' => 'New Password must contain  at least 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character.'
                ]
        ],
        'confirm_password' => [
            'rules'  =>  'required|matches[new_password]',
            'errors' => [
                'required' => 'Please enter a Confirm password',
                'matches' => 'Confirm password does not match with New password'
                ]
        ]
       ]);
       if(!$isvalid){
        return view('backend/pages/auth/reset', [
            'pagetitle'   => 'Reset Password',
            'validation' => null,
            'token' => $token
        ]);
       } else{
        $password_reset_token = new Passwordresettoken();
        $get_token = $password_reset_token->asObject()->where('token',  $token)->first();
        $user = new  User();
        $user_info = $user->asObject()->where('email', $get_token->email)->first();
        if(!$get_token){
            return redirect()->back()->with('error', 'Invalid token!')->withInput();
        } else{
            // update admin password
            $user->where('email', $user_info->email)->set(['password'=>Hash::make($this->request->getVar('new_password'))])->update();

            // send notification to user email address
            $mail_data = array(
                'user' =>  $user_info,
                'new_password'  => $this->request->getVar('new_password')
            );
           
            $to = $user_info->email;
            $subject = 'Account Activation Link';
            $view = \Config\Services::renderer();
            $message =  $view->setVar('mail_data', $mail_data)->render('backend/email-templates/password-changed-email-template');
            $email = \config\Services::email();
            $email->setTo($to);
            $email->setFrom('mkcreater333@gmail.com',  'Axcel World');
            $email->setSubject($subject);
            $email->setMessage($message);
            $email->setPriority(1); // Set email priority to high
            if ($email->send()) {
                $password_reset_token->where('email', $user_info->email)->delete();
                return redirect()->route('admin.login.form')->with('success', 'Done!,  Your password has been changed successfully.Use your new password to login.');
            } else {
                return  redirect()->back()->with('error', 'Error sending email')->withInput();
            }
        }
       }
    }
}
