<?= $this->extend('/frontend/layouts/pages_layout') ?>
<?= $this->section('content') ?>


<div class="row">
    <div class="col-12">
        <h1 class="mb-4 border-bottom border-primary d-inline-block">Search for: <?= $search ?></h1>
    </div>
    <div class="col-lg-8 mb-5 mb-lg-0">
        <div class="row">
            <?php foreach ($posts as $post) : ?>
                <div class="col-md-6 mb-4">
                    <article class="card article-card article-card-sm h-100">
                        <a href="<?= route_to('read-post', $post->slug) ?>">
                            <div class="card-image">
                                <div class="post-info"> <span class="text-uppercase"><?= date_formatter($post->created_at) ?></span>
                                    <span class="text-uppercase"><?= get_reading_time($post->content) ?></span>
                                </div>
                                <img loading="lazy" decoding="async" src="/images/posts/resized<?= $post->featured_image ?>" alt="Post Thumbnail" class="w-100" width="420" height="280">
                            </div>
                        </a>
                        <div class="card-body px-0 pb-0">

                            <h2><a class="post-title" href="<?= route_to('read-post', $post->slug) ?>"><?= $post->title ?></a></h2>
                            <p class="card-text"><?= limit_words($post->content) ?></p>
                            <div class="content"> <a class="read-more-btn" href="<?= route_to('read-post', $post->slug) ?>">Read Full Article</a>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-12 text-center">

            <?php
            if ($pager->getPageCount() > 1): ?>
                <ul class="pagination">
                    <?php if ($pager->getCurrentPage() > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= route_to('search-posts-pagination', $search, strval($pager->getCurrentPage() - 1)) ?>">Prev</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $pager->getPageCount(); $i++): ?>
                        <li class="page-item <?= $i === $pager->getCurrentPage() ? 'active' : '' ?>">
                            <a class="page-link" href="<?= route_to('search-posts-pagination', $search, $i) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($pager->getCurrentPage() < $pager->getPageCount()): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= route_to('search-posts-pagination', $search, strval($pager->getCurrentPage() + 1)) ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="fw-light fs-italic text-muted text-endc">Showing
                    <?= (($page * $perPage) - $perPage + 1) . "-" . (($page * $perPage) - $perPage + count($posts))  ?> Result out
                    of <?= number_format($total) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="widget-blocks">
            <div class="row">

                <div class="col-lg-12 col-md-6">
                    <?php include('partials/sidebar_latest_posts.php') ?>
                </div>
                <div class="col-lg-12 col-md-6">
                    <?php include('partials/sidebar-categories.php') ?>
                </div>
                <div class="col-lg-12 col-md-6">
                    <?php include('partials/sidebar-tags.php') ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>