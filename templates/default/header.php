<?php
/**
* The header for default theme
*
* @package reader
*/
?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" <?php language_attributes(); ?>>
<![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php get_charset(); ?>">
<meta name="viewport" content="width=device-width">
<title><?php echo $config['site_title']; ?> <?php mr_title(); ?></title>

<link rel="stylesheet" href="<?php get_template_directory_uri(); ?>/less/normalize.css">
<link rel="stylesheet" href="<?php get_template_directory_uri(); ?>/less/bootstrap.css">
<link rel="stylesheet" href="<?php get_template_directory_uri(); ?>/less/main.css">
<script src="<?php get_template_directory_uri(); ?>/js/modernizr.js"></script>
<?php mr_head(); ?>

</head>

<body>
<div id="wrapper" class="container-narrow">
      <header class="top">
        <div class="head-top">
          <div class="pull-right">
            <?php if (is_logged_in()) : ?>
            Logged in as <a href="#"><?php get_user_data('username'); ?></a> | <a href="<?php get_logout_uri(); ?>"><?php _e('LOGOUT'); ?></a>
            <?php else : ?>
            <a href="<?php get_login_uri(); ?>"><?php _e('LOGIN'); ?></a> | <a href="<?php get_register_uri(); ?>"><?php _e('REGISTER'); ?></a>
            <?php endif; ?>
          </div>
          <a href="<?php get_home_uri(); ?>" class="logo">MangaReader</a>
        </div>
        <div class="navbar">
          <div class="navbar-inner">
            <div class="input-append pull-right search-top">
              <input class="span2" id="appendedInputButton" type="text" placeholder="<?php _e('SEARCH'); ?>">
              <button class="btn btn-inverse" type="submit"><?php _e('SEARCH_BUTTON'); ?></button>
            </div>
            <ul class="nav">
              <li><a href="#" class="active">Home</a></li>
              <li><a href="#">Latest Releases</a></li>
              <li><a href="#">Advanced Search</a></li>
              <li><a href="#">Contact</a></li>
            </ul>
          </div>
        </div>
      </header>
