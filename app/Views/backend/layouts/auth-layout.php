<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title><?= isset($pagetitle)  ? $pagetitle : 'New Page Title' ?></title>

		<!-- Site favicon -->
		<link
		rel="icon"
		type="image/png"
		
		href="<?= get_settings()->blog_favicon == null ? '/backend/vendors/images/favicon-16x16.png' : '/images/blogs/' . get_settings()->blog_favicon ?>" />

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="/backend/vendors/styles/icon-font.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/style.css" />
        <?= $this->renderSection('stylesheets') ?>
	</head>
	<body class="login-page">
		<div class="login-header box-shadow">
			<div
				class="container-fluid d-flex justify-content-between align-items-center"
			>
				<div class="brand-logo">
					<a href="<?= route_to('admin.login.form') ?>">
					<img src="<?= get_settings()->blog_logo == null ? '/backend/vendors/images/deskapp-logo.svg' : '/images/blogs/' . get_settings()->blog_logo ?>" alt="" class="avatar-photo ci-avatar-photo" style="background-size: cover;height: 100%; width: 100%;">
					</a>
				</div>
				<div class="login-menu">
				
					
				</div>
			</div>
		</div>
		<?= $this->renderSection('content') ?>

		<!-- js -->
		<script src="/backend/vendors/scripts/core.js"></script>
		<script src="/backend/vendors/scripts/script.min.js"></script>
		<script src="/backend/vendors/scripts/process.js"></script>
		<script src="/backend/vendors/scripts/layout-settings.js"></script>
		<?= $this->renderSection('scripts') ?>
	</body>
</html>
