<?php

declare(strict_types=1);

function paprika_setup_editor_styles(): void {
  add_theme_support('editor-styles');
  add_editor_style('assets/css/editor-style.css');
}
add_action('after_setup_theme', 'paprika_setup_editor_styles');

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

  wp_enqueue_script(
    'paprika-gallery',
    get_theme_file_uri('assets/js/gallery.js'),
    [],
    filemtime(get_theme_file_path('assets/js/gallery.js')),
    true
  );

  if (is_page('request')) {
    wp_enqueue_script(
      'paprika-request',
      get_theme_file_uri('assets/js/request.js'),
      [],
      filemtime(get_theme_file_path('assets/js/request.js')),
      true
    );
  }
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

function paprika_request_get_telegram_token(): string {
  if (defined('PAPRIKA_TG_BOT_TOKEN')) {
    return (string) PAPRIKA_TG_BOT_TOKEN;
  }
  return (string) getenv('PAPRIKA_TG_BOT_TOKEN');
}

function paprika_request_get_telegram_chat_id(): string {
  if (defined('PAPRIKA_TG_CHAT_ID')) {
    return (string) PAPRIKA_TG_CHAT_ID;
  }
  return (string) getenv('PAPRIKA_TG_CHAT_ID');
}

function paprika_request_redirect_url(string $status, string $fallback = ''): string {
  $base_url = $fallback !== '' ? $fallback : home_url('/request');
  return add_query_arg('request_status', $status, $base_url);
}

function paprika_normalize_ru_mobile_phone(string $raw_phone): string {
  $digits = preg_replace('/\D+/', '', $raw_phone);
  if (!is_string($digits) || $digits === '') {
    return '';
  }
  if ($digits[0] === '8') {
    $digits = '7' . substr($digits, 1);
  }
  if ($digits[0] !== '7') {
    $digits = '7' . $digits;
  }
  if (strlen($digits) !== 11 || $digits[1] !== '9') {
    return '';
  }
  return sprintf('+7 (%s) %s-%s-%s', substr($digits, 1, 3), substr($digits, 4, 3), substr($digits, 7, 2), substr($digits, 9, 2));
}

function formatEventDate($date): string {
  if (!$date instanceof DateTimeInterface) {
    return '';
  }

  $fmtDate = new IntlDateFormatter(
    'ru_RU',
    IntlDateFormatter::NONE,
    IntlDateFormatter::NONE,
    $date->getTimezone()->getName(),
    IntlDateFormatter::GREGORIAN,
    'd MMMM'
  );

  $fmtWeekday = new IntlDateFormatter(
    'ru_RU',
    IntlDateFormatter::NONE,
    IntlDateFormatter::NONE,
    $date->getTimezone()->getName(),
    IntlDateFormatter::GREGORIAN,
    'EE'
  );

  $result = $fmtDate->format($date) . ' (' . mb_strtoupper($fmtWeekday->format($date), 'UTF-8') . ')';

  if ($date->format('H:i') !== '00:00') {
    $result .= ' [в ' . $date->format('H:i') . ']';
  }

  return $result;
}

function paprika_handle_request_form_submit(): void {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    wp_safe_redirect(home_url('/request'));
    exit;
  }

  $redirect_base = '';
  if (!empty($_POST['request_redirect']) && is_string($_POST['request_redirect'])) {
    $redirect_base = esc_url_raw(wp_unslash($_POST['request_redirect']));
  }

  if (!isset($_POST['paprika_request_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash((string) $_POST['paprika_request_nonce'])), 'paprika_request_submit')) {
    wp_safe_redirect(paprika_request_redirect_url('invalid', $redirect_base));
    exit;
  }

  $honeypot = isset($_POST['website']) ? trim((string) wp_unslash($_POST['website'])) : '';
  if ($honeypot !== '') {
    wp_safe_redirect(paprika_request_redirect_url('spam', $redirect_base));
    exit;
  }

  $started_at = isset($_POST['started_at']) ? (int) $_POST['started_at'] : 0;
  if ($started_at <= 0 || (time() - $started_at) < 3) {
    wp_safe_redirect(paprika_request_redirect_url('spam', $redirect_base));
    exit;
  }

  $ip = isset($_SERVER['REMOTE_ADDR']) ? (string) $_SERVER['REMOTE_ADDR'] : '';
  if ($ip !== '') {
    $rate_key = 'paprika_request_rl_' . md5($ip);
    if (get_transient($rate_key)) {
      wp_safe_redirect(paprika_request_redirect_url('spam', $redirect_base));
      exit;
    }
    set_transient($rate_key, '1', 20);
  }

  $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash((string) $_POST['name'])) : '';
  $email = isset($_POST['email']) ? sanitize_email(wp_unslash((string) $_POST['email'])) : '';
  $raw_phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash((string) $_POST['phone'])) : '';
  $phone = paprika_normalize_ru_mobile_phone($raw_phone);
  $course = isset($_POST['course']) ? sanitize_text_field(wp_unslash((string) $_POST['course'])) : '';

  if ($name === '' || $phone === '') {
    wp_safe_redirect(paprika_request_redirect_url('invalid', $redirect_base));
    exit;
  }

  $token = paprika_request_get_telegram_token();
  $chat_id = paprika_request_get_telegram_chat_id();

  if ($token === '' || $chat_id === '') {
    wp_safe_redirect(paprika_request_redirect_url('config_error', $redirect_base));
    exit;
  }

  $lines = [
    '📩 <b>Новая заявка с сайта Paprika</b>',
    '',
    '👤 <b>Имя:</b> ' . esc_html($name),
    '📞 <b>Телефон:</b> ' . esc_html($phone),
    '✉️ <b>Email:</b> ' . ($email !== '' ? esc_html($email) : 'не указан'),
  ];
  if ($course !== '') {
    $lines[] = '🍽️ <b>Курс:</b> ' . esc_html($course);
  }
  $lines[] = '🕒 <b>Время:</b> ' . esc_html(wp_date('d.m.Y H:i'));

  $response = wp_remote_post(
    'https://api.telegram.org/bot' . $token . '/sendMessage',
    [
      'timeout' => 10,
      'body' => [
        'chat_id' => $chat_id,
        'text' => implode("\n", $lines),
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => 'true',
      ],
    ]
  );

  if (is_wp_error($response)) {
    error_log('Paprika request Telegram error: ' . $response->get_error_message());
    wp_safe_redirect(paprika_request_redirect_url('send_error', $redirect_base));
    exit;
  }

  $code = wp_remote_retrieve_response_code($response);
  $body = json_decode((string) wp_remote_retrieve_body($response), true);
  if ($code < 200 || $code >= 300 || !is_array($body) || empty($body['ok'])) {
    error_log('Paprika request Telegram bad response: code=' . $code . ' body=' . wp_json_encode($body));
    wp_safe_redirect(paprika_request_redirect_url('send_error', $redirect_base));
    exit;
  }

  wp_safe_redirect(paprika_request_redirect_url('ok', $redirect_base));
  exit;
}
add_action('admin_post_nopriv_paprika_request_submit', 'paprika_handle_request_form_submit');
add_action('admin_post_paprika_request_submit', 'paprika_handle_request_form_submit');
