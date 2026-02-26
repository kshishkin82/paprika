<?php

declare(strict_types=1);
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo esc_html(wp_get_document_title()); ?></title>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <div class="wrap">
    <div class="header-banner">
      <div class="header-banner__side header-banner__side--left"></div>
      <div class="header-banner__center">
        <img class="header-banner__rabbit" src="<?php echo esc_url(get_theme_file_uri('assets/banner/rabbit.png')); ?>" alt="" />
        <a class="header-banner__logo-link" href="<?php echo esc_url(home_url('/')); ?>">
          <img class="header-banner__logo" src="<?php echo esc_url(get_theme_file_uri('assets/banner/logo.svg')); ?>" alt="Paprika Studio" />
        </a>
      </div>
      <div class="header-banner__side header-banner__side--right"></div>
    </div>
    <nav class="nav header-banner__nav">
      <?php
      wp_nav_menu([
        'theme_location' => 'main-menu',
        'container' => false,
        'items_wrap' => '%3$s',
        'walker' => new Paprika_Link_Only_Walker(),
        'fallback_cb' => false,
      ]);
      ?>
    </nav>
