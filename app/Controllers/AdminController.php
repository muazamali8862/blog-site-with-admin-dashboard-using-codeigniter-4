<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\CIAuth;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\User;
use App\Models\Settings;
use  App\Models\SocialMedia;
use App\Models\Category;
use SSP;
use SawaStacks\CodeIgniter\Slugify;
use App\Models\Subcategory;
use App\Models\Post;

class AdminController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIFunction'];
    protected $db;
    public  function __construct()
    {
        require_once  APPPATH . 'ThirdParty/ssp.php';
        $this->db = db_connect();
    }
    // admin dashboard
    public function index()
    {
        $data  = [
            'pagetitle' => 'Dashboard Admin',
        ];
        return view('backend/pages/home');
    }
    // logout
    public function logout()
    {
        CIAuth::forget();
        return redirect()->route('admin.login.form')->with('success', 'You Are Logged out!');
    }
    // profile
    public function profile()
    {
        $data = array(
            'pagetitle' => 'Profile',
        );
        return  view('backend/pages/profile', $data);
    }
    // update user data
    public function updatePersonalDetails()
    {
        $request = \Config\Services::request();
        $user_id = CIAuth::id();
        $isvalid = $this->validate([
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter your name',
                ],
            ],
            'username' => [
                'rules' => 'required|is_unique[users.username]|min_length[5]',
                'errors' => [
                    'required' => 'Please enter your username',
                    'is_unique' => 'Username already exists',
                    'min_length' => 'Username must be at least 5 characters long',
                ],
            ],

        ]);
        if (!$isvalid) {
            return  view('backend/pages/profile', [
                'pagetitle'   => 'Profile',
                'validation'  => $this->validator,
            ]);
        } else {
            $user = new User();
            $user_info = $user->asObject()->where('id', $user_id)->first();
            $path = 'images/users/';

            $file = $request->getFile('picture');
            if ($file !== null && $file->isValid()) {
                $old_picture = $user_info->picture;
                $new_filename = 'UIMG_' . $user_id . $file->getRandomName();
                if ($file->move($path, $new_filename)) {
                    if ($old_picture != null && file_exists($path . $old_picture)) {
                        unlink($path . $old_picture);
                    }
                    $user->where('id', $user_info->id)->set(['picture' =>  $new_filename])->update();
                }
            }

            $update = $user->where('id', $user_id)->set(['name' => $request->getVar('name'), 'username' => $request->getVar('username'), 'bio' => $request->getVar('bio')])->update();

            if ($update) {
                return  redirect()->route('admin.profile')->with('success', 'profile updated successfully');
            } else {
                return  redirect()->route('admin.profile')->with('error', 'Failed to update profile');
            }
        }
    }

    // change password
    public function changePassword()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $user_id = CIAuth::id();
        $user = new User();
        $user_info = $user->asObject()->where('id', $user_id)->first();
        $isvalid = $this->validate([
            'current_password' => [
                'rules' => 'required|check_current_password[current_password]',
                'errors' => [
                    'required' => 'Please enter your current password',
                    'check_current_password' => 'Current password is incorrect',
                ],
            ],
            'new_password' => [
                'rules' =>  'required|min_length[5]|max_length[20]|Is_password_strong[new_password]',
                'errors' => [
                    'required' => 'Please enter a New password',
                    'min_length' => 'New Password must be at least 5 characters',
                    'max_length' => 'New Password must not be more than 20 characters',
                    'Is_password_strong' => 'New Password must contain  at least 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character.'
                ]
            ],
            'confirm_new_password' => [
                'rules'  =>  'required|matches[new_password]',
                'errors' => [
                    'required' => 'Please enter a Confirm password',
                    'matches' => 'Confirm password does not match with New password'
                ]
            ]
        ]);
        if (!$isvalid) {
            return  view('backend/pages/profile', [
                'pagetitle'   => 'Profile',
                'validation'  => $this->validator,
            ]);
        } else {
            $user->where('id', $user_id)->set(['password' => password_hash($request->getVar('new_password'), PASSWORD_DEFAULT)])->update();
            return  redirect()->route('admin.profile')->with('success', 'Password changed successfully');
        }
    }
    // settings
    public function settings()
    {
        $data = [
            'pagetitle'   => 'Settings',
        ];
        return  view('backend/pages/settings', $data);
    }
    // update General Setting
    public function updateGeneralSetting()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $isvalid = $this->validate([
            'blog_title' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Please enter a Blog Title',
                ]
            ],
            'blog_email'  => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Please enter a Email',
                    'valid_email' => 'Please enter a valid email',
                ],
            ],
        ]);
        if (!$isvalid) {
            return  view('backend/pages/settings', [
                'pagetitle'   => 'Settings',
                'validation'  => $this->validator,
            ]);
        } else {
            $settings = new  Settings();
            $setting_id = $settings->asObject()->first()->id;
            $update = $settings->where('id',  $setting_id)->set([
                'blog_title' => $request->getVar('blog_title'),
                'blog_email' => $request->getVar('blog_email'),
                'blog_phone' => $request->getVar('blog_phone'),
                'blog_meta_keyword' =>  $request->getVar('blog_meta_keyword'),
                'blog_meta_description'  =>  $request->getVar('blog_meta_description'),
            ])->update();
            if ($update) {
                return  redirect()->route('settings')->with('success', 'General Settings have  been updated successfully');
            } else {
                return  redirect()->route('settings')->with('error', 'Failed to update General Settings');
            }
        }
    }
    // update Blog Logo
    public function updateBlogLogo()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $settings = new Settings();
        $path = 'images/blogs/';
        $setting_data = $settings->asObject()->first();
        $file = $request->getFile('blog_logo');
        if ($file !== null && $file->isValid()) {
            $old_picture = $setting_data->blog_logo;
            $new_filename = 'UIMG_' . $setting_data->blog_logo . '_' . $file->getRandomName();
            if ($file->move($path, $new_filename)) {
                if ($old_picture != null && file_exists($path . $old_picture)) {
                    unlink($path . $old_picture);
                }
                $update = $settings->where('id', $setting_data->id)->set(['blog_logo' =>  $new_filename])->update();
                if ($update) {
                    return  redirect()->route('settings')->with('success', 'Blog Logo has been updated successfully ');
                } else {
                    return  redirect()->route('settings')->with('error', 'Failed to update Blog Logo');
                }
            }
        } else {
            return  redirect()->route('settings')->with('error', 'Failed to update Blog Logo');
        }
    }
    // update Blog Favicon
    public function updateBlogFavicon()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $settings = new Settings();
        $path = 'images/blogs/';
        $setting_data = $settings->asObject()->first();
        $file = $request->getFile('blog_favicon');
        if ($file !== null && $file->isValid()) {
            $old_picture = $setting_data->blog_favicon;
            $new_filename = 'UIMG_' . $setting_data->blog_favicon . '_' . $file->getRandomName();
            if ($file->move($path, $new_filename)) {
                if ($old_picture != null && file_exists($path . $old_picture)) {
                    unlink($path . $old_picture);
                }
                $update = $settings->where('id', $setting_data->id)->set(['blog_favicon' =>  $new_filename])->update();
                if ($update) {
                    return  redirect()->route('settings')->with('success', 'Blog Favicon has been updated successfully ');
                } else {
                    return  redirect()->route('settings')->with('error', 'Failed to update Blog Favicon');
                }
            }
        } else {
            return  redirect()->route('settings')->with('error', 'Failed to update Blog Favicon');
        }
    }

    // update social media
    public function updateSocialMedia()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $isvalid = $this->validate([
            'facebook_url' => [
                'rules'  => 'permit_empty|valid_url_strict',
                'errors' => [
                    'valid_url_strict' => 'Invalid Facebook URL',
                ],
            ],
            'twitter_url' => [
                'rules'  => 'permit_empty|valid_url_strict',
                'errors' => [
                    'valid_url_strict' => 'Invalid Twitter URL',
                ],
            ],
            'instagram_url' => [
                'rules'  => 'permit_empty|valid_url_strict',
                'errors' => [
                    'valid_url_strict' => 'Invalid Instagram URL',
                ],
            ],
            'youtube_url' => [
                'rules'  => 'permit_empty|valid_url_strict',
                'errors' => [
                    'valid_url_strict' => 'Invalid Youtube URL',
                ],
            ],
            'github_url' => [
                'rules'  => 'permit_empty|valid_url_strict',
                'errors' => [
                    'valid_url_strict' => 'Invalid Github URL',
                ],
            ],
            'linkedin_url' => [
                'rules'  => 'permit_empty|valid_url_strict',
                'errors' => [
                    'valid_url_strict' => 'Invalid LinkedIn URL',
                ],
            ]
        ]);
        if (!$isvalid) {
            return redirect()->route('settings')->with('error', $validation->listErrors());
        } else {
            $social_media = new SocialMedia();
            $social_media_id = $social_media->asObject()->first()->id;
            $update = $social_media->where('id', $social_media_id)->set([
                'facebook_url' => $request->getVar('facebook_url'),
                'twitter_url' => $request->getVar('twitter_url'),
                'instagram_url' => $request->getVar('instagram_url'),
                'youtube_url' => $request->getVar('youtube_url'),
                'github_url' => $request->getVar('youtube_url'),
                'linkedin_url' => $request->getVar('linkedin_url')
            ])->update();
            if ($update) {
                return redirect()->route('settings')->with('success', 'Social media updated successfully');
            } else {
                return redirect()->route('settings')->with('error', 'Failed to update social media');
            }
        }
    }

    // catogeries
    public function Categories()
    {
        $data = [
            'pagetitle'   => 'Categories',
        ];
        return  view('backend/pages/Categories', $data);
    }
    // add Category
    public function addCategory()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $isvalid = $this->validate([
            'category_name' => [
                'rules' => 'required|is_unique[categories.name]',
                'errors' => [
                    'required' => 'Category name is required',
                    'is_unique' => 'Category name already exists',
                ],
            ]
        ]);
        if (!$isvalid) {
            return redirect()->route('Categories')->with('error', $validation->listErrors());
        } else {
            $category = new Category();
            $save = $category->save(['name' => $request->getVar('category_name')]);
            if ($save) {
                return  redirect()->route('Categories')->with('success', 'Category added successfully');
            } else {
                return  redirect()->route('Categories')->with('error', 'Failed to add category');
            }
        }
    }
    // get categories
    public function getCategories()
    {
        $dbdetails = array(
            'host' => $this->db->hostname,
            'user' =>  $this->db->username,
            'pass' => $this->db->password,
            'db'  => $this->db->database,
        );
        $table = 'categories';
        $primaryKey = 'id';
        $columns = array(
            array(
                'db' => 'id',
                'dt' => 0
            ),
            array(
                'db' => 'name',
                'dt' => 1
            ),
            array(
                'db' => 'id',
                'dt' => 2,
                'formatter' => function ($d, $row) {
                    // return "(x) will be added later";
                    $subcategory = new  Subcategory();
                    $subcategories = $subcategory->where(['parent_cat' => $row['id']])->findAll();
                    return  count($subcategories);
                }
            ),
            array(
                'db' => 'id',
                'dt' => 3,
                'formatter' => function ($d, $row) {
                    return "<div class='btn-group'>
                        <button class='btn btn-sm btn-link p-0 mx-1 editcategorybtn' data-id='" . $row['id'] . "'>Edit</button>
                        <button class='btn btn-sm btn-link p-0 mx-1 deletecategorybtn' data-id='" . $row['id'] . "' > Delete</button>
                        </div>";
                }
            ),
            array(
                'db' => 'ordering',
                'dt' => 4,
            ),
        );
        return json_encode(
            SSP::simple($_GET, $dbdetails, $table, $primaryKey, $columns)
        );
    }
    // getCategory
    public function getCategory()
    {
        $request = \Config\Services::request();
        if ($request->isAJAX()) {
            $id = $request->getVar('category_id');
            $category = new Category();
            $category_data = $category->find($id);
            return $this->response->setJSON(['data' => $category_data]);
        }
    }
    // updateCategory
    public function updateCategory()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $id = $request->getVar('category_id');
        $isvalid = $this->validate([
            'category_name'  => [
                'rules' => 'required|is_unique[categories.name,id,' . $id . ']',
                'errors' => [
                    'required' => 'Category name is required',
                    'is_unique' => 'Category name already exists'
                ]
            ]
        ]);
        if (!$isvalid) {
            return redirect()->route('Categories')->with('error', $validation->listErrors());
        } else {
            $category = new Category();
            $category->update($id, ['name' => $request->getVar('category_name')]);
            return  redirect()->route('Categories')->with('success', 'Category name updated successfully');
        }
    }

    // deleteCategory
    public function deleteCategory()
    {
        $request = \Config\Services::request();
        $id = $request->getVar('category_id');
        $category = new Category();

        $subcategory = new Subcategory();
        $subcategories = $subcategory->where('parent_cat', $id)->findAll();
        if ($subcategories) {
            $msg = count($subcategories) == 1  ? 'There is (' . count($subcategories) . ') sub category related to this parent category, so that it cannot be deleted' : 'There are (' . count($subcategories) . ') sub category related to this parent category, so that it cannot be deleted';
            return  redirect()->route('Categories')->with('error', $msg);
        } else {
            $delete  =  $category->delete($id);
            if ($delete) {
                return redirect()->route('Categories')->with('success', 'Category deleted successfully!')->with('redirect', true);
            } else {
                return  redirect()->route('Categories')->with('error', 'Category not deleted');
            }
        }
    }
    // reorderCategories
   // reorderCategories
public function reorderCategories()
{
    $request = \Config\Services::request();
    $positions = $request->getVar('positions');
    $category = new Category();
    foreach ($positions as  $position) {
        list($index, $newposition) = explode(',', $position);
        $category->update($index, ['ordering' => $newposition]);
    }
    return redirect()->route('Categories')->with('success', 'Categories reordered successfully!');
}
    // getParentCategories
    public function getParentCategories()
    {
        $request = \Config\Services::request();
        if ($request->isAJAX()) {
            $id = $request->getVar('parent_category_id');
            $options =  '<option value="0">Uncategorized</option>';
            $category = new Category();
            $parent_categories = $category->findAll();
            if (count($parent_categories)) {
                $added_options = '';
                foreach ($parent_categories as $parent_category) {
                    $isselected = $parent_category['id'] == $id ? 'selected' : '';
                    $added_options .= '<option value="' . $parent_category['id'] . '"  ' . $isselected . '>' . $parent_category['name'] . '</option>';
                }
                $options .= $added_options;
                return $this->response->setJSON(['status' => 1, 'data' => $options]);
            } else {
                return $this->response->setJSON(['status' => 1, 'data' => $options]);
            }
        }
    }
    // addSubcategory
    public function addSubcategory()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $isvalid = $this->validate([
            'subcategory_name'  => [
                'rules' => 'required|is_unique[sub_categories.name]',
                'errors' => [
                    'required' => 'Please enter subcategory name',
                    'is_unique' => 'Subcategory name already exists'
                ]
            ],
        ]);
        if (!$isvalid) {
            return redirect()->route('Categories')->with('error', $validation->listErrors());
        } else {
            $subcategory =  new Subcategory();
            $subcategory_name = $request->getVar('subcategory_name');
            $subcategory_desc =  $request->getVar('description');
            $subcategory_parent_category = $request->getVar('parent_cat');

            $subcategory_slug = Slugify::model(Subcategory::class)->make($subcategory_name);
            $save = $subcategory->save([
                'name' => $subcategory_name,
                'description' => $subcategory_desc,
                'parent_cat' => $subcategory_parent_category,
                'slug' => $subcategory_slug,
            ]);
            if ($save) {
                return redirect()->route('Categories')->with('success', 'Sub Category Added successfully!');
            } else {
                return redirect()->route('Categories')->with('error', 'Failed to add sub category');
            }
        }
    }
    // getSubcategories
    public function getSubcategories()
    {
        $category =  new Category();
        $subcategory = new  Subcategory();
        $dbdetails = array(
            'host' => $this->db->hostname,
            'user' =>  $this->db->username,
            'pass' => $this->db->password,
            'db'  => $this->db->database,
        );
        $table = 'sub_categories';
        $primaryKey = 'id';
        $columns = array(
            array(
                'db' => 'id',
                'dt' => 0
            ),
            array(
                'db' => 'name',
                'dt' => 1
            ),
            array(
                'db' => 'id',
                'dt' => 2,
                'formatter' => function ($d, $row) use ($category,  $subcategory) {
                    $parent_cat_id = $subcategory->asObject()->where('id', $row['id'])->first()->parent_cat;
                    $parent_cat_name = ' - ';
                    if ($parent_cat_id  != 0) {
                        $parent_cat_name  = $category->asObject()->where('id', $parent_cat_id)->first()->name;
                    }
                    return $parent_cat_name;
                }
            ),
            array(
                'db' => 'id',
                'dt' => 3,
                'formatter' => function ($d, $row) {
                    // return '(x) will be added later';
                    $post = new  Post();
                    $posts = $post->where(['category_id' => $row['id']])->findAll();
                    return count($posts);
                }
            ),
            array(
                'db' => 'id',
                'dt' => 4,
                'formatter' => function ($d, $row) {
                    return "<div class='btn btn-group'>
                        <button class='btn btn-sm btn-link p-0 mx-1 editsubcategorybtn' data-id='" . $row['id'] . "'>Edit</button>
                        <button class='btn btn-sm btn-link p-0 mx-1 deletesubcategorybtn' data-id='" . $row['id'] . "' > Delete</button>
                        </div>";
                }
            ),
            array(
                'db' => 'ordering',
                'dt' => 5,
            ),
        );
        return json_encode(
            SSP::simple($_GET, $dbdetails,  $table, $primaryKey, $columns)
        );
    }

    // getsubCategory
    public function getsubCategory()
    {

        $request = \Config\Services::request();
        if ($request->isAJAX()) {
            $id = $request->getVar('subcategory_id');
            $subcategory = new  Subcategory();
            $subcategory_data = $subcategory->find($id);
            return $this->response->setJSON(['data' => $subcategory_data]);
        }
    }
    // update-subcategory
    public function updatesubCategory()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $id = $request->getVar('subcategory_id');
        $isvalid = $this->validate([
            'subcategory_name'  => [
                'rules' => 'required|is_unique[sub_categories.name,id,' . $id . ']',
                'errors' => [
                    'required' => 'Sub Category name is required',
                    'is_unique' => 'Sub Category name already exists'
                ]
            ]
        ]);
        if (!$isvalid) {
            return redirect()->route('Categories')->with('error', $validation->listErrors());
        } else {
            $subcategory = new Subcategory();
            $subcategory->update($id, ['name' => $request->getVar('subcategory_name'), 'parent_cat' => $request->getVar('parent_cat'), 'description' =>  $request->getVar('description')]);
            return  redirect()->route('Categories')->with('success', 'Sub Category updated successfully');
        }
    }

    // reorderCategories

    public function reordersubCategories()
{
    $request = \Config\Services::request();
    $positions = $request->getVar('positions');
    $subcategory = new  Subcategory();
    foreach ($positions as  $position) {
        list($index, $newposition) = explode(',', $position);
        $subcategory->update($index, ['ordering' => $newposition]);
    }
    return redirect()->route('Categories')->with('success', 'Sub Categories reordered successfully!');
}
    // delete sub Category

public function deletesubCategory()
{
    $request = \Config\Services::request();
    $id = $request->getVar('subcategory_id');
    $subcategory = new  Subcategory();

    $post = new  Post();
    $posts = $post->where('category_id', $id)->findAll();
    if ($posts) {
        $msg = count($posts) == 1  ? 'There is (' . count($posts) . ') post related to this parent sub category, so that it cannot be deleted' : 'There are (' . count($posts) . ') post related to this parent sub category, so that it cannot be deleted';
        return  redirect()->route('Categories')->with('error', $msg);
    } else {
        $delete  =  $subcategory->delete($id);
        if ($delete) {
            return redirect()->route('Categories')->with('success', 'Sub Category deleted successfully!')->with('redirect', true);
        } else {
            return  redirect()->route('Categories')->with('error', 'Sub Category not deleted');
        }
    }
}
    // addpost
    public function addpost()
    {
        $subcategory = new  Subcategory();
        $data = [
            'pagetitle' => 'Add new post',
            'categories' => $subcategory->asObject()->findAll()
        ];
        return view('backend/pages/new-post', $data);
    }

    // create post
    public function createpost()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $isvalid = $this->validate([
            'title' => [
                'rules' => 'required|is_unique[posts.title]',
                'errors' => [
                    'required' => 'Title is required',
                    'is_unique' => 'Title already exists'
                ],
            ],
            'content' => [
                'rules' =>  'required|min_length[20]',
                'errors' => [
                    'required' => 'Content is required',
                    'min_length' => 'Content must be at least 20 characters'
                ],
            ],
            'category'  => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Category is required'
                ],
            ],
            'featured_image' => [
                'rules' =>  'uploaded[featured_image]|is_image[featured_image]|max_size[featured_image, 2048]',
                'errors' => [
                    'uploaded' => 'Please choose a featured image',
                    'is_image' => 'Please choose a valid image',
                    'max_size' => 'Image size must be less than 2MB'
                ],
            ],
        ]);
        if (!$isvalid) {
            $subcategory = new  Subcategory();
            $data = [
                'pagetitle'   => 'Add new post',
                'categories' => $subcategory->asObject()->findAll(),
                'validation'  => $this->validator,
            ];
            return  view('backend/pages/new-post', $data);
        } else {
            $user_id = CIAuth::id();
            $path = 'images/posts/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $file = $request->getFile('featured_image');
            // $filename = $file->getClientName();
            $filename = 'pimg_'.time().$file->getClientName();
            if ($file->move($path, $filename)) {
                \Config\Services::image()->withFile($path . $filename)->fit(150, 150, 'center')->save($path . 'thumb_' . $filename);

                // create resized image
                \Config\Services::image()->withFile($path . $filename)->resize(450, 300, true, 'width')->save($path . 'resized' . $filename);

                // save new post details
                $post = new  Post();
                $data = array(
                    'author_id'  => $user_id,
                    'category_id'  => $request->getVar('category'),
                    'title'  => $request->getVar('title'),
                    'slug'   =>  Slugify::model(post::class)->make($request->getVar('title')),
                    'content'  => $request->getVar('content'),
                    'featured_image'  => $filename,
                    'tags' => $request->getVar('tags'),
                    'meta_keywords' =>  $request->getVar('meta_keywords'),
                    'meta_description' =>  $request->getVar('meta_description'),
                    'visibility' =>   $request->getVar('visibility'),
                );
                $save = $post->insert($data);
                $last_id = $post->getInsertID();
                if ($save) {
                    return  redirect()->route('all-posts')->with('success', 'New blog post has been successfully created.');
                } else {
                    return  redirect()->route('all-posts')->with('error', 'Something  went wrong while creating new blog post.');
                }
            } else {
                return  redirect()->route('all-posts')->with('error', 'Error on uploading featured image.');
            }
        }
    }
    // allposts
    public function allposts()
    {
        $data = [
            'pagetitle'   => 'All Posts',
        ];
        return  view('backend/pages/allposts', $data);
    }
    // getPosts
    public function getPosts()
    {
        $dbdetails = array(
            'host' => $this->db->hostname,
            'user' =>  $this->db->username,
            'pass' => $this->db->password,
            'db'  => $this->db->database,
        );
        $table = 'posts';
        $primaryKey = 'id';
        $columns = array(
            array(
                'db' => 'id',
                'dt' => 0
            ),
            array(
                'db' => 'id',
                'dt' => 1,
                'formatter' => function ($d, $row) {
                    $post = new  post();
                    $image = $post->asObject()->find($row['id'])->featured_image;
                    return "<img src= '/images/posts/thumb_$image' class='img-thumbnail' style='max-width:70px'>";
                }
            ),
            array(
                'db' => 'title',
                'dt' => 2,
            ),
            array(
                'db' => 'id',
                'dt' => 3,
                'formatter' => function ($d, $row) {
                    $post = new  post();
                    $category_id = $post->asObject()->find($row['id'])->category_id;
                    $subcategory =  new  subcategory();
                    $subcategory_data = $subcategory->asObject()->find($category_id);
                    if ($subcategory_data) {
                        return $subcategory_data->name;
                    } else {
                        return 'Unknown';
                    }
                }
            ),
            array(
                'db' => 'id',
                'dt' => 4,
                'formatter' => function ($d, $row) {
                    $post = new  post();
                    $visibility = $post->asObject()->find($row['id'])->visibility;
                    return  $visibility == 1 ? 'Public' : 'Private';
                }
            ),
            array(
                'db' => 'id',
                'dt' => 5,
                'formatter' => function ($d, $row) {
                    return "<div class='btn btn-group'>
                        <a class='btn btn-sm btn-link p-0 mx-1'>View</a>
                        <a class='btn btn-sm btn-link p-0 mx-1 ' href='" . route_to('edit-post', $row['id']) . "'>Edit</a>
                        <button class='btn btn-sm btn-link p-0 mx-1 deletepostbtn' data-id='" . $row['id'] . "' > Delete</button>
                        </div>";
                }
            )
        );
        return json_encode(
            SSP::simple($_GET, $dbdetails, $table, $primaryKey, $columns)
        );
    }
    // editpost
    public function editpost($id)
    {
        $subcategory = new  subcategory();
        $post = new  post();
        $data = [
            'pagetitle'   => 'Edit Post',
            'categories' => $subcategory->asObject()->findAll(),
            'post' => $post->asObject()->find($id)
        ];
        return view('backend/pages/edit-post', $data);
    }
    // Update post
    public function updatepost()
    {
        $request = \Config\Services::request();
        $validation = \Config\Services::validation();
        $post_id = $request->getVar('post_id');
        $user_id = CIAuth::id();
        $post = new post();

        // Validate title and content
        $isvalid = $this->validate([
            'title'  => [
                'rules' => 'required|is_unique[posts.title,id,' . $post_id . ']',
                'errors' => [
                    'required' => 'Title is required',
                    'is_unique' => 'Title already exists'
                ]
            ],
            'content' => [
                'rules' => 'required|min_length[20]',
                'errors' => [
                    'required' => 'Content is required',
                    'min_length' => 'Content must be at least 20 characters long'
                ]
            ],
        ]);

        if (!$isvalid) {
            $subcategory = new Subcategory();
            $data = [
                'pagetitle'   => 'Edit Post',
                'categories' => $subcategory->asObject()->findAll(),
                'post' => $post, // Pass the $post object to the view
                'validation'  => $this->validator,
            ];
            return view('backend/pages/edit-post', $data); // Return the view with validation errors
        }

        // Validate featured image if it's updated
        if (isset($_FILES['featured_image']['name'])  && $_FILES['featured_image']['name'] != '') {
            $isvalid_image = $this->validate([
                'featured_image' => [
                    'rules' =>  'uploaded[featured_image]|is_image[featured_image]|max_size[featured_image, 2048]',
                    'errors' => [
                        'uploaded' => 'Please choose a featured image',
                        'is_image' => 'Please choose a valid image',
                        'max_size' => 'Image size must be less than 2MB'
                    ]
                ]
            ]);

            if (!$isvalid_image) {
                $subcategory = new Subcategory();
                $data = [
                    'pagetitle'   => 'Edit Post',
                    'categories' => $subcategory->asObject()->findAll(),
                    'post' => $post, // Pass the $post object to the view
                    'validation'  => $this->validator,
                ];
                return view('backend/pages/edit-post', $data); // Return the view with validation errors
            }
        }

        // Update post
        if (isset($_FILES['featured_image']['name'])  && $_FILES['featured_image']['name'] != '') {
            $path = 'images/posts/';
            $file = $request->getFile('featured_image');
            // $filename = $file->getClientName();
            $filename = 'pimg_'.time().$file->getClientName();
            $currentPost = $post->asObject()->find($post_id);
            $old_post_image = null;
            if ($currentPost !== null) {
                $old_post_image = $currentPost->featured_image;
            }
            // upload image
            if ($file->move($path, $filename)) {
                \Config\Services::image()->withFile($path . $filename)->fit(150, 150, 'center')->save($path . 'thumb_' . $filename);

                // create resized image
                \Config\Services::image()->withFile($path . $filename)->resize(450, 300, true, 'width')->save($path . 'resized' . $filename);
                if ($old_post_image !=  null && file_exists($path . $old_post_image)) {
                    unlink($path . $old_post_image);
                }
                if (file_exists($path . 'thumb_' . $old_post_image)) {
                    unlink($path . 'thumb_' . $old_post_image);
                }
                if (file_exists($path . 'resized' . $old_post_image)) {
                    unlink($path . 'resized' . $old_post_image);
                }
                // update post details
                $data = array(
                    'author_id'  => $user_id,
                    'category_id'  => $request->getVar('category'),
                    'title'  => $request->getVar('title'),
                    'slug'   =>  Slugify::model(post::class)->make($request->getVar('title')),
                    'content'  => $request->getVar('content'),
                    'featured_image'  => $filename,
                    'tags' => $request->getVar('tags'),
                    'meta_keywords' =>  $request->getVar('meta_keywords'),
                    'meta_description' =>  $request->getVar('meta_description'),
                    'visibility' =>   $request->getVar('visibility'),
                );
                $update = $post->update($post_id, $data);
                if ($update) {
                    return redirect()->route('all-posts')->with('success', 'Post updated successfully')->with('redirect', true);
                } else {
                    return redirect()->route('all-posts')->with('error', 'Failed to update post');
                }
            } else {
                return  redirect()->route('all-posts')->with('error', 'Failed to upload image');
            }
        } else {
            // update post details
            $data = array(
                'author_id'  => $user_id,
                'category_id'  => $request->getVar('category'),
                'title'  => $request->getVar('title'),
                'slug'   =>  Slugify::model(post::class)->make($request->getVar('title')),
                'content'  => $request->getVar('content'),
                'tags' => $request->getVar('tags'),
                'meta_keywords' =>  $request->getVar('meta_keywords'),
                'meta_description' =>  $request->getVar('meta_description'),
                'visibility' =>   $request->getVar('visibility'),
            );
            $update = $post->update($post_id, $data);
            if ($update) {
                return redirect()->route('all-posts')->with('success', 'Post updated successfully')->with('redirect', true);
            } else {
                return redirect()->route('all-posts')->with('error', 'Failed to update post');
            }
        }
    }

    // deletepost
    public function deletepost()
{
    $request = \Config\Services::request();
    $id = $request->getVar('post_id');
    $post =  new post();

    $delete =  $post->delete($id);
    if ($delete) {
        return redirect()->route('all-posts')->with('success', 'Post deleted successfully!');
    } else {
        return  redirect()->route('all-posts')->with('error', 'Something went wrong');
    }
}
}
