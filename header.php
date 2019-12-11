<?php
use DevLog\View;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
<?php wp_head() ?>
</head>
<body <?php body_class() ?>>
<div class="l-wrapper">
	<header class="l-header<?php echo is_admin_bar_showing() ? ' l-header--with-admin-bar' : '' ?>">
		<h1 class="l-header__title"><a href="<?php echo get_home_url() ?>"><?php echo View::point_text( get_bloginfo( 'name' ) ) ?></a></h1>
		<div class="l-header__search">
			<a href="<?php echo home_url() ?>?s=" class="l-header__search-btn">
				<svg>
					<use xlink:href="#svg-search"></use>
				</svg>
			</a>
			<form action="<?php echo home_url() ?>" class="l-header__search-form">
				<input type="text" class="l-header__search-field" value="<?php echo get_search_query() ?>" placeholder="<?php echo __( 'Search Keyword', 'devlog' ) ?>" name="s">
			</form>
		</div>
	</header>