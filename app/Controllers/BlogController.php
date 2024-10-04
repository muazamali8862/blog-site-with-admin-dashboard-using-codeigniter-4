<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Post;
use App\Models\Subcategory;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class BlogController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIFunction', 'text'];
    public function __construct()
    {
        $pagination = \Config\Services::pagination();
    }
    public function index()
    {
        $data = [
            'pagetitle' => get_settings()->blog_title
        ];
        return view('frontend/pages/home', $data);
    }
    // categoryposts
  public function categoryposts($category_slug, $page = 1)
{
    $subcat = new Subcategory();
    $subcategory = $subcat->asObject()->where('slug', $category_slug)->first();
    $post = new Post();

    $data = [];
    $data['pagetitle'] = 'Category: ' . $subcategory->name;
    $data['category'] =  $subcategory;

    $pager = \Config\Services::pager();
    $perPage = 6;
    
    $data['posts'] = $post->asObject()->where('visibility', 1)->where('category_id', $subcategory->id)->paginate($perPage, 'default', $page);
    $data['pager'] = $pager;
    $data['category_slug'] = $category_slug;
    $data['total'] = $post->where('visibility', 1)->where('category_id', $subcategory->id)->countAllResults();
    $data['perPage'] = $perPage;
    $data['page'] = $page;
    $data['links'] = $pager->links('default', 'default_full');

    return view('frontend/pages/category_posts', $data);
}
// tagposts
public function tagposts($tag, $page = 1){
    $post = new  Post();
    $data = [];
    $pager = \Config\Services::pager();
    $perPage = 6;
    $data['pagetitle'] = 'Tag: '.$tag;
    $data['tag'] = $tag;
    $data['page'] = $page;
    $data['perPage'] = $perPage;
    $data['total'] = $post->where('visibility', 1)->like('tags', '%'.$tag.'%')->countAllResults();
    $data['posts'] = $post->asObject()->where('visibility', 1)->like('tags', '%'.$tag.'%')->orderBy('created_at', 'desc')->paginate($perPage, 'default', $page);
    $data['pager'] = $pager;
    return view('frontend/pages/tag_posts', $data);

}

// searchposts
// searchposts
public function searchposts($search = '', $page = 1){
    $request  = \Config\Services::request();
    $searchData = $request->getGet();
    $search = $search ? $search : (isset($searchData) && isset($searchData['q']) ? $searchData['q']  : '');
    $perPage = 6;
    $post = new  Post();
    $post2 = new  Post();

    if($search == ''){
        return redirect()->to(base_url());
    } else{
        $keywords = explode(" ",trim($search));
        $post = $this->getsearchdata($post,$keywords);
        $post2 = $this->getsearchdata($post2,$keywords);

        $paginated_data = $post->asObject()->where('visibility',1)->paginate($perPage, 'default', $page);
        $total = $post2->where('visibility',1)->countAllResults();
        $pager = \Config\Services::pager();

        $data = [
            'pagetitle' => 'Search for: '.$search,
            'posts' => $paginated_data,
            'pager' => $pager,
            'page' =>  $page,
            'search'  => $search,
            'total' => $total,
            'perPage' => $perPage
        ];

        return view('frontend/pages/search_posts',$data);
    }
}

// get search data
public function getsearchdata($object, $keywords){
    $object->select('*');
    $object->groupStart();
    foreach($keywords  as $keyword){
        $object->orLike('title',$keyword)->orLike('tags',$keyword);
    }
    return  $object->groupEnd();
}

// readPost
public function readPost($slug){
    $post = new  Post();
    try{
        $post = $post->asObject()->where('slug', $slug)->first();
        $data = [
            'pagetitle' => $post->title,
            'post' => $post
        ];
        return view('frontend/pages/single_post',$data);

    } catch(Exception $e){
        throw $e->getMessage();
    }
}
// contactUs
public function contactUs(){
    $data = [
        'pagetitle' => 'Contact Us',
        'validation' => null
    ];
    return view('frontend/pages/contact_us',  $data);
}
// contactUsSend
public function contactUsSend(){
    $request = \Config\Services::request();
    $isvalid  = $this->validate([
        'name' => [
            'rules' =>  'required',
            'errors' => [
                'required' =>  'Please enter your name'
            ]
            ],
            'email' => [
                'rules' =>  'required|valid_email',
                'errors' => [
                'required' =>  'Email  is required',
                'valid_email' => 'Please enter a valid email'
            ]
                ],
                'subject'  => [
                    'rules' =>  'required',
                    'errors' => [
                        'required' =>  'Subject  is required'
                        ]
                        ],
                        'message'   => [
                            'rules' =>  'required',
                            'errors'  => [
                                'required' =>  'Enter  your message please!'
                            ]
                            ],
    ]);
    if(!$isvalid){
        $data = [
            'pagetitle' => 'Contact Us',
            'validation' => $this->validator
            ];
            return view('frontend/pages/contact_us',  $data);
    } else{
        $from = $request->getVar('email');
        $mail_body = 'Message from: <b>'.$request->getVar('name') .'<b><br>';
        $mail_body .= '....................................<br>'.$from;
        $mail_body .= $request->getVar('message').'<br>';
        $to = get_settings()->blog_email;
        $subject = $request->getVar('subject');
       
        $name = $request->getVar('name');
        $email = \config\Services::email();
        $email->setTo($to);
        $email->setFrom($request->getVar('email'),  $name);
        $email->setSubject($subject);
        $email->setMessage($mail_body);
        $email->setPriority(1); 
        if($email->send()){
            return redirect()->route('contact-us')->with('success', 'Your  message has been sent successfully!.');
        }  else {
            return redirect()->route('contact-us')->with('error', 'Failed to send your message!.');
            }
    }
}
}
