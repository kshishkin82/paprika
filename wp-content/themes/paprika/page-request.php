<?php

declare(strict_types=1);

require get_theme_file_path('includes/header.php');

$selected_course = '';
if (isset($_GET['course'])) {
  $selected_course = sanitize_text_field(wp_unslash((string) $_GET['course']));
}

$request_status = '';
if (isset($_GET['request_status'])) {
  $request_status = sanitize_key(wp_unslash((string) $_GET['request_status']));
}

$status_map = [
  'ok' => ['type' => 'success', 'text' => 'Заявка отправлена. Мы скоро свяжемся с вами.'],
  'invalid' => ['type' => 'error', 'text' => 'Проверьте обязательные поля: имя и телефон.'],
  'spam' => ['type' => 'error', 'text' => 'Не удалось отправить заявку. Попробуйте еще раз.'],
  'config_error' => ['type' => 'error', 'text' => 'Заявка не отправлена: не настроен Telegram.'],
  'send_error' => ['type' => 'error', 'text' => 'Ошибка отправки. Попробуйте снова через минуту.'],
];

$request_page_url = get_permalink();
if ($selected_course !== '') {
  $request_page_url = add_query_arg('course', $selected_course, $request_page_url);
}
?>
<main class="page-content">
    <h1>Оставить заявку</h1>
  <section class="section">
    <div class="card">
      <?php if (isset($status_map[$request_status])) { ?>
        <div class="request-notice request-notice--<?php echo esc_attr($status_map[$request_status]['type']); ?>">
          <?php echo esc_html($status_map[$request_status]['text']); ?>
        </div>
      <?php } ?>

      <form class="request-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="paprika_request_submit" />
        <input type="hidden" name="request_redirect" value="<?php echo esc_url($request_page_url); ?>" />
        <input type="hidden" name="started_at" value="<?php echo esc_attr((string) time()); ?>" />
        <?php wp_nonce_field('paprika_request_submit', 'paprika_request_nonce'); ?>
        <input class="request-honeypot" name="website" type="text" value="" autocomplete="off" tabindex="-1" />

        <?php if ($selected_course !== '') { ?>
          <input type="hidden" name="course" value="<?php echo esc_attr($selected_course); ?>" />
        <?php } ?>
        <p>
          <label for="request-name">Имя *</label><br />
          <input id="request-name" name="name" type="text" required />
        </p>

        <p>
          <label for="request-email">Email</label><br />
          <input id="request-email" name="email" type="email" />
        </p>

        <p>
          <label for="request-phone">Телефон *</label><br />
          <input
            id="request-phone"
            name="phone"
            type="tel"
            inputmode="tel"
            autocomplete="tel-national"
            placeholder="+7 (900) 000-00-00"
            pattern="^\+7 \([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}$"
            required
          />
        </p>

        <p>
          <button class="request-link" type="submit">Отправить</button>
        </p>
      </form>
    </div>

    <div class="card">
      <?php if ($selected_course !== '') { ?>
        <h1>Запись на мастер-класс <?php echo esc_html($selected_course); ?></h1>

        <p class="request-motivation">
          Отличный выбор. Оставьте контакты, и мы свяжемся с вами, чтобы подтвердить участие
          и уточнить детали по выбранному мастер-классу.
        </p>

      <?php } else { ?>
        <h1>Оставьте заявку на мастер-класс</h1>
        <p class="request-motivation">
          Сделайте первый шаг к новым вкусам и ярким впечатлениям. Мы свяжемся с вами,
          подберем удобный формат и поможем выбрать ближайшее занятие именно для вас.
        </p>
      <?php } ?>
    </div>
  </section>
</main>
<?php require get_theme_file_path('includes/footer.php'); ?>
