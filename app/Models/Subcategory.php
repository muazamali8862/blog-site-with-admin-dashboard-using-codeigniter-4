<?php

namespace App\Models;

use CodeIgniter\Model;
use SawaStacks\CodeIgniter\Slugify;
class Subcategory extends Model
{
    protected $DBGroup = 'default';
    protected $table            = 'sub_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['name','slug','parent_cat','description','ordering'];

}
