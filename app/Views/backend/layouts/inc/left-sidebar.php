<div class="left-side-bar">
	<div class="brand-logo">
		<a href="<?= route_to('admin.home') ?>">
		<img src="<?= get_settings()->blog_logo == null ? '/backend/vendors/images/deskapp-logo.svg' : '/images/blogs/' . get_settings()->blog_logo ?>" alt="" class="avatar-photo ci-avatar-photo" style="background-size: cover;height: 100%; width: 100%;">
			<!-- <img src="/backend/vendors/images/deskapp-logo.svg" alt="" class="dark-logo" /> -->
		</a>
		<div class="close-sidebar" data-toggle="left-sidebar-close">
			<i class="ion-close-round"></i>
		</div>
	</div>
	<div class="menu-block customscroll">
		<div class="sidebar-menu">
			<ul id="accordion-menu">
				<li>
					<a href="<?= route_to('admin.home') ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'admin.home' ?  'active' : '' ?>">

						<span class="micon dw dw-home"></span><span class="mtext">Home</span>
					</a>
				</li>
				<li>
					<a href="<?= route_to('Categories') ?>" class="dropdown-toggle no-arrow  <?= current_route_name() == 'Categories' ?  'active' : '' ?>">

						<span class="micon dw dw-list"></span><span class="mtext">Catogeries</span>
					</a>
				</li>
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle <?= current_route_name() == 'all-posts' || current_route_name() == 'new_post' ?  'active' : '' ?>">

						<span class="micon dw dw-newspaper"></span><span class="mtext">Posts</span>
					</a>
					<ul class="submenu">
						<li><a href="<?= route_to('all-posts') ?>" class="<?= current_route_name()  == 'all-posts' ?  'active' : '' ?>">All Posts</a></li>

						<li><a href="<?= route_to('new_post') ?>" class="<?= current_route_name() == 'new_post' ?  'active' : ''  ?>">Add New</a></li>
					</ul>
				</li>

				<li>
					<div class="dropdown-divider"></div>
				</li>
				<li>
					<div class="sidebar-small-cap">Settings</div>
				</li>

				<li>
					<a
						href="<?= route_to('admin.profile') ?>"
						class="dropdown-toggle no-arrow <?= current_route_name() == 'admin.profile' ? 'active' : '' ?>">
						<span class="micon dw dw-user"></span>
						<span class="mtext">Profile
							</span>
					</a>
				</li>
				<li>
					<a
						href="<?= route_to('settings') ?>"
						class="dropdown-toggle no-arrow  <?= current_route_name() == 'settings' ? 'active' : '' ?>">

						<span class="micon dw dw-settings"></span>
						<span class="mtext">General
							</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>

<style>
	.left-side-bar{
		position: fixed !important;
		display: block !important;
	}
</style>