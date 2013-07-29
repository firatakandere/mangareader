<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content=""/>
  <title><?php echo $config['site_title']; ?><?php mr_title(); ?></title>
  <link rel="stylesheet" type="text/css" href="<?php get_admin_template_directory_uri(); ?>/css/bootstrap.css"/>

  <style type="text/css">
    body {
      padding-top: 60px;
      padding-bottom: 40px;
    }
    .sidebar-nav {
      padding: 9px 0;
    }
  </style>

  <!--[if lt IE 9]>
    <script src="<?php get_admin_template_directory_uri(); ?>/js/html5shiv.js"></script>
  <![endif]-->
<?php mr_head(); ?>

</head>
<body>
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container-fluid">
        <a class="brand" href="#">Manga Reader</a>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
