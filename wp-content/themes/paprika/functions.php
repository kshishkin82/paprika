<?php

declare(strict_types=1);

function paprika_enqueue_assets(): void {
  wp_enqueue_style(
    'paprika-fonts',
    'https://fonts.googleapis.com/css2?family=Merriweather:wght@300&family=Raleway:wght@400&display=swap',
    [],
    null
  );

  wp_enqueue_style(
    'paprika-style',
    get_theme_file_uri('assets/css/style.css'),
    ['paprika-fonts'],
    filemtime(get_theme_file_path('assets/css/style.css'))
  );
}
add_action('wp_enqueue_scripts', 'paprika_enqueue_assets');

function paprika_register_menus(): void {
  register_nav_menus([
    'main-menu' => 'Main Menu',
  ]);
}
add_action('after_setup_theme', 'paprika_register_menus');

final class Paprika_Link_Only_Walker extends Walker_Nav_Menu {
  public function start_lvl(&$output, $depth = 0, $args = null): void {
  }

  public function end_lvl(&$output, $depth = 0, $args = null): void {
  }

  public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0): void {
    $classes = !empty($item->classes) ? array_filter((array) $item->classes) : [];
    $class_attr = $classes ? ' class="' . esc_attr(implode(' ', $classes)) . '"' : '';

    $atts = '';
    if (!empty($item->url)) {
      $atts .= ' href="' . esc_url($item->url) . '"';
    }
    if (!empty($item->target)) {
      $atts .= ' target="' . esc_attr($item->target) . '"';
    }
    if (!empty($item->xfn)) {
      $atts .= ' rel="' . esc_attr($item->xfn) . '"';
    }
    if (!empty($item->title)) {
      $atts .= ' title="' . esc_attr($item->title) . '"';
    }

    $title = apply_filters('the_title', $item->title, $item->ID);
    $output .= '<a' . $class_attr . $atts . '>' . esc_html($title) . '</a>';
  }

  public function end_el(&$output, $item, $depth = 0, $args = null): void {
  }
}

function paprika_customize_register(WP_Customize_Manager $wp_customize): void {
  $wp_customize->add_section('paprika_contacts', [
    'title' => 'Контакты',
    'priority' => 30,
  ]);

  $wp_customize->add_setting('paprika_phones', [
    'default' => '8 (937) 530-10-90, 8 (927) 510-08-53',
    'sanitize_callback' => 'sanitize_text_field',
  ]);
  $wp_customize->add_control('paprika_phones', [
    'label' => 'Телефоны',
    'section' => 'paprika_contacts',
    'type' => 'text',
  ]);
}
add_action('customize_register', 'paprika_customize_register');
