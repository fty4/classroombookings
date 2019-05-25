<?php

$title_tags = array(html_escape($title), 'classroombookings');

$school_name = html_escape(setting('name'));
if (strlen($school_name))
{
	$title_tags[] = $school_name;
}

$crbs_svg = file_get_contents(APPPATH . 'assets/dist/img/crbs-logo.svg');

$grid_size = 'lg';

$active = NULL;
if (isset($menu_active)) {
	$items = explode('/', $menu_active);
	$active = current($items);
}

$main_menu = render_menu(array(
	'active' => $active,
	'active_tag' => 'link',
	'items' => $menus['main'],
	'link_class' => 'btn btn-link',
	'item_template' => '{link}',
));

$user_menu = render_menu(array(
	'active' => $active,
	'active_tag' => 'link',
	'items' => $menus['user'],
	'link_class' => 'btn btn-link',
	'item_template' => '{link}',
));

?>

<!DOCTYPE html>
<html lang="en-GB">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex, nofollow">
		<meta name="author" content="Craig A Rodway">
		<meta name="csrf_token_name" content="<?= $this->security->get_csrf_token_name() ?>">
		<meta name="csrf_token_value" content="<?= $this->security->get_csrf_hash() ?>">
		<title><?= implode(' | ', $title_tags) ?></title>
		<?php
		foreach ($css as $css_url) {
			echo "<link rel='stylesheet' type='text/css' media='screen' href='{$css_url}'>\n";
		}
		?>
		<link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('application/assets/dist/brand/apple-touch-icon.png') ?>">
		<link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('application/assets/dist/brand/favicon-32x32.png') ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('application/assets/dist/brand/favicon-16x16.png') ?>">
		<link rel="manifest" href="<?= base_url('application/assets/dist/brand/site.webmanifest') ?>">
		<link rel="mask-icon" href="<?= base_url('application/assets/dist/brand/safari-pinned-tab.svg') ?>" color="#ff6400">
		<link rel="shortcut icon" href="<?= base_url('application/assets/dist/brand/favicon.ico') ?>">
		<meta name="msapplication-TileColor" content="#ff6400">
		<meta name="msapplication-config" content="<?= base_url('application/assets/dist/brand/browserconfig.xml') ?>">
		<meta name="theme-color" content="#ff6400">
		<script>
		var h = document.getElementsByTagName("html")[0];
		(h ? h.classList.add('js') : h.className += ' ' + 'js');
		var BASE_URL = "<?= base_url() ?>";
		</script>
	</head>

	<body class="theme-<?= setting('theme') ?>">

		<header class="header">

			<section class="container grid-lg">
				<div class="navbar">

					<div class="navbar-section">
						<a href="<?= site_url() ?>" class="header-logo-crbs">
							<?= $crbs_svg ?>
						</a>
						<?php
						echo $main_menu;
						?>
					</div>

					<div class="navbar-section header-menu-user">
						<?php
						echo $user_menu;
						?>
					</div>

				</div>
			</section>

		</header>

		<div class="content">

			<?php
			$breadcrumb_html = render_breadcrumbs($breadcrumbs);
			if ( ! empty($breadcrumb_html)) {
				echo "<section class='bread-wrapper'>";
				echo "<div class='container grid-{$grid_size}'>";
				echo $breadcrumb_html;
				echo "</section>";
			}

			$notices = render_notices();
			if ( ! empty($notices)) {
				echo "<section class='notice-wrapper'>";
				echo "<div class='container grid-{$grid_size}'>";
				echo $notices;
				echo "</div>";
				echo "</section>";
			}
			?>

			<section class="body">
				<div class="container grid-<?= $grid_size ?>">
					<?php
					if (isset($blocks['tabs'])) {
						echo $blocks['tabs'];
					}
					?>
					<?= $body ?>
				</div>
			</section>

		</div>

		<footer class="footer">
			<div class="container grid-lg">
				<div class="columns">

					<div class="column col-6">
						<ul class="nav nav-footer">
							<li class='nav-heading'><?= $school_name ?></li>
							<?php
							echo render_menu(array(
								'items' => $menus['main'],
								'link_class' => 'btn btn-link',
								'item_class' => 'nav-item',
							));
							?>
						</ul>
					</div>

					<div class="column col-6 text-right">
						<div class="footer-brand">
							<p><a href="<?= site_url() ?>">
								<?= $crbs_svg; ?>
							</a></p>
							<p><a href="https://www.classroombookings.com/" target="_blank">classroombookings</a>. &copy; Craig A Rodway.</p>
							<p>Version <?= VERSION; ?>. Load time <?php echo $this->benchmark->elapsed_time() ?>s.</p>
						</div>
					</div>

				</div>
			</div>
		</footer>

		<?php
		foreach ($js as $js_url) {
			echo "<script type='text/javascript' src='{$js_url}'></script>\n";
		}
		?>

	</body>

</html>
