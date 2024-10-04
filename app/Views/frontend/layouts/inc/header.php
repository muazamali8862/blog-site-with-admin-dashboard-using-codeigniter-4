<header class="navigation">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light px-0">
      <a class="navbar-brand order-1 py-0" href="/">
        <img loading="prelaod" decoding="async" class="img-fluid" src="/images/blogs/<?= get_settings()->blog_logo ?>" alt="<?= get_settings()->blog_title ?>" style="max-width: 170px;">
      </a>
      <div class="navbar-actions order-3 ml-0 ml-md-4">
        <button aria-label="navbar toggler" class="navbar-toggler border-0" type="button" data-toggle="collapse"
          data-target="#navigation"> <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <form action="<?= route_to('search-posts') ?>" class="search order-lg-3 order-md-2 order-3 ml-auto"  method="get">

        <input id="search-query" name="q" type="search" placeholder="Search..." autocomplete="off" value="<?= isset($search) ? $search  : '' ?>">

      </form>
      
      <div class="collapse navbar-collapse text-center order-lg-2 order-4" id="navigation">
        <ul class="navbar-nav mx-auto mt-3 mt-lg-0">
          <li class="nav-item"> <a class="nav-link" href="<?= route_to('/') ?>">Home</a>
          </li>

          <?php foreach(get_parent_categories() as $parent_category): ?>

          <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="#" role="button"
              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?= $parent_category->name ?>
            </a>
            <div class="dropdown-menu"> 
              <?php foreach(get_subcategories_by_parent_id($parent_category->id) as $subcategory): ?>

              <a class="dropdown-item" href="<?= route_to('category-posts', $subcategory->slug) ?>"><?= $subcategory->name ?></a>
              <?php  endforeach; ?>

            </div>
          </li>
          <?php endforeach ?>
          <?php foreach(get_dependent_subcategories() as $subcategory): ?>
          <li class="nav-item"> <a class="nav-link" href="<?= route_to('category-posts', $subcategory->slug) ?>"><?= $subcategory->name ?></a>
          </li>
          <?php endforeach ?>
          <li class="nav-item"> <a class="nav-link" href="<?= route_to('contact-us') ?>">Contact</a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</header>
