<?php

// $routes->setAotoRoute(true);
/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'BlogController::index');
$routes->get('post/(:any)','BlogController::readPost/$1', ['as' =>'read-post'] );
// $routes->get('category/(:any)','BlogController::categoryposts/$1', ['as' =>'category-posts'] );
$routes->get('categoryposts/(:any)', 'BlogController::categoryposts/$1',['as' =>'category-posts']);
$routes->get('categoryposts/(:any)/(:num)', 'BlogController::categoryposts/$1/$2',['as' =>'category-posts-pagination']);
$routes->get('tags/(:any)', 'BlogController::tagposts/$1',['as' =>'tag-posts']);
$routes->get('tags/(:any)/(:num)', 'BlogController::tagposts/$1/$2',['as' =>'tag-posts-pagination']);
$routes->get('search', 'BlogController::searchposts',['as'  =>'search-posts']);
$routes->get('search/(:any)/(:num)', 'BlogController::searchposts/$1/$2', ['as' =>'search-posts-pagination']);
$routes->get('contact-us',  'BlogController::contactUs',['as'  =>'contact-us']);
$routes->post('contact-us',   'BlogController::contactUsSend',['as'  =>'contact-us-send']);
$routes->group('admin', static function($routes){
    $routes->group('', ['filter' => 'cifilter:auth'],  static function($routes){
        // $routes->view('example-page', 'example-page');
        $routes->get('home', 'AdminController::index',['as'=> 'admin.home'] );
        $routes->get('logout', 'AdminController::logout', ['as' => 'admin.logout']);
        $routes->get('profile', 'AdminController::profile', ['as' => 'admin.profile']);
        $routes->post('update-personal-details',  'AdminController::updatePersonalDetails', ['as' => 'update-personal-details']);
       $routes->post('change-password', 'AdminController::changePassword', ['as' => 'change-password']);
       $routes->get('settings',  'AdminController::settings', ['as' => 'settings']);
       $routes->post('update-general-setting',  'AdminController::updateGeneralSetting', ['as' => 'update-general-setting']);
       $routes->post('update-blog-logo',   'AdminController::updateBlogLogo', ['as' => 'update-blog-logo']);
       $routes->post('update-blog-favicon',   'AdminController::updateBlogFavicon', ['as' => 'update-blog-favicon']);
       $routes->post('update-social-media',   'AdminController::updateSocialMedia', ['as' => 'update-social-media']);
       $routes->get('Categories', 'AdminController::Categories', ['as' => 'Categories']);
       $routes->post('add-category',  'AdminController::addCategory', ['as' => 'add-category']);
       $routes->get('get-categories', 'AdminController::getCategories',  ['as' => 'get-categories']);
       $routes->get('get-cetegory',  'AdminController::getCategory', ['as' => 'get-cetegory']);
       $routes->post('update-category',  'AdminController::updateCategory', ['as' => 'update-category']);
       $routes->post('delete-category','AdminController::deleteCategory', ['as' => 'delete-category']);
       $routes->post('reorder-categories', 'AdminController::reorderCategories',['as' => 'reorder-categories'] );
       $routes->get('get-parent-categories', 'AdminController::getParentCategories', ['as' => 'get-parent-categories']);
       $routes->post('add-subcategory','AdminController::addSubcategory', ['as' => 'add-subcategory']);
       $routes->get('get-subcategories', 'AdminController::getSubcategories', ['as'  => 'get-subcategories']);
       $routes->get('get-subcetegory',  'AdminController::getsubCategory', ['as' => 'get-subcetegory']);
       $routes->post('update-subcategory',  'AdminController::updatesubCategory', ['as' => 'update-subcategory']);
       $routes->post('reorder-subcategories','AdminController::reordersubCategories', ['as' => 'reorder-subcategories']);
    //    $routes->post('delete-subcategory', 'Controller::deletesubCategory', ['as' => 'delete-subcategory']);
       $routes->post('delete-subcategory','AdminController::deletesubCategory', ['as' => 'delete-subcategory']);

       $routes->group('posts', static function($routes){
        $routes->get('new_post',  'AdminController::addpost', ['as' => 'new_post']);
        $routes->post('create-post',  'AdminController::createpost', ['as' =>  'create-post']);
        $routes->get('/',  'AdminController::allposts', ['as' =>  'all-posts']);
        $routes->get('get-posts',   'AdminController::getPosts', ['as' =>  'get-posts']);
        $routes->get('edit-post/(:any)', 'AdminController::editpost/$1', ['as' =>  'edit-post']);
        $routes->post('update-post',   'AdminController::updatepost', ['as' =>  'update-post']);
        $routes->post('delete-post',   'AdminController::deletepost', ['as' =>   'delete-post']);
        
       });
    });
    $routes->group('', ['filter' => 'cifilter:guest'],  static function($routes){
        $routes->get('register', 'AuthController::registerform', ['as' => 'admin.register.form']);
        $routes->post('register', 'AuthController::register',['as'=> 'admin.register']  );
        $routes->get('login', 'AuthController::loginForm',['as'=> 'admin.login.form'] );
        $routes->post('login', 'AuthController::login',['as'=> 'admin.login']  );
        $routes->get('forgot-password', 'AuthController::forgetform', ['as'=> 'admin.forgot.form']);
        $routes->post('send-password-reset-link', 'AuthController::sendpasswordresetlink', ['as'=>  'send_password_reset_link']);
        // $routes->get('password/reset/(:any)', 'AuthController::resetpassword/$1', ['as '=> 'admin.reset-password']);
        $routes->get('password/reset-password/(:any)', 'AuthController::resetPassword/$1',  ['as' => 'admin.reset-password']);
        $routes->post('reset-password-handler/(:any)', 'AuthController::resetPasswordhandler/$1',  ['as' => 'reset-password-handler']);
        // $routes->view('example-auth', 'example-auth');
    });
});
