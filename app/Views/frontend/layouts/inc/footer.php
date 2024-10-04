<footer class="bg-dark mt-5">
  <div class="container section">
    <div class="row">
      <div class="col-lg-10 mx-auto text-center">
        <a class="d-inline-block mb-4 pb-2" href="/">
          <img loading="prelaod" decoding="async" class="img-fluid" src="/images/blogs/<?= get_settings()->blog_logo ?>" alt="<?= get_settings()->blog_title ?>" style="max-width: 170px;">
        </a>
        <ul class="p-0 d-flex navbar-footer mb-0 list-unstyled">
          <li class="nav-item my-0"> <a class="nav-link" href="<?= route_to('/') ?>">Home</a></li>
          
          <li class="nav-item my-0"> <a class="nav-link" href="<?= route_to('contact-us') ?>">Contact Us</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="copyright bg-dark content">
  Blog Site Using Codeigniter 4 - Designed By
    <a href="https://www.linkedin.com/in/muazam-ali-252987327/" target="_blank">Muazam Ali</a>
    <div class="mb-0">
    &copy; copyright <script>document.write(new Date().getFullYear());</script>
        </div>
</div>
</footer>