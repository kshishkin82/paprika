<?php

declare(strict_types=1);

require get_theme_file_path('includes/header.php');
?>

    <section class="hero">
      <div>
        <?php the_content() ?>
        <div class="cta">
          <a class="btn btn-hot" href="/request/">Записаться</a>
          <a class="btn" href="#cert">Сертификат</a>
        </div>
      </div>
      <div class="visual" id="schedule">
          <?php
          $params = array(
              'where'   => 'showindex.meta_value = 1',
              'limit'   => 3,
              'orderby' => 'post_date DESC'
          );
          $mypods = pods( 'post', $params );
          while ( $mypods->fetch() ) {
          $link  = get_permalink( $mypods->id() );
          ?>
            <a class="block" style="background-image:url(<?=$mypods->display('heroimage._src.medium_large') ?>)" href="<?=$link ?>">
                <span class="title"><?=$mypods->display( 'post_title' ) ?></span>
                <span class="date"><?=$mypods->display('nearest_date') ?></span>
            </a>
          <?php
          }
          ?>

        <a class="block calendar-link" href="/raspisanie/">
          <span class="title">Все мастер классы смотрите в нашем календаре</span>
           <span class="date">Открыть</span>
        </a>
      </div>
    </section>

    <section class="section" id="formats">
      <div class="card ornament">
        <h2>Наша студия</h2>
        <p>Paprika - современная кулинарная студия. Теория, практика и дегустация в одном вечере. Мы проводим события для взрослых и детей.</p>
        <h2>Форматы</h2>
        <ul class="list">
          <li>Мастер-классы и курсы</li>
          <li>Корпоративные мероприятия</li>
          <li>Праздники и семейные события</li>
          <li>VIP и индивидуальные программы</li>
        </ul>
      </div>
      <div class="card">
        <h2>Кухни</h2>
        <p>Русская, японская, паназиатская, средиземноморская, французская, испанская, итальянская кухня, десерты и выпечка.</p>
        <div class="cta" style="margin-top:18px;">
          <a class="btn btn-hot" href="#contacts">Забронировать</a>
        </div>
      </div>
    </section>

    <section class="section" id="gallery">
      <div class="card">
        <h2>Фотогалерея</h2>
        <p>Детали, подача, эмоции гостей и работа шефа. Здесь вкус выглядит так же ярко, как ощущается.</p>
      </div>
      <div class="grid">
          <?php
          $category = get_term_by('name', 'Галерея', 'category');
          $posts_query = new WP_Query([
              'cat' => (int) $category->term_id,
              'post_type' => 'post',
              'post_status' => 'publish',
              'posts_per_page' => 6,
              'ignore_sticky_posts' => true,
            ]);

          while ($posts_query->have_posts()) {
            $posts_query->the_post();
            $image_value = pods_field_display("heroimage._src.medium_large");
          ?>

          <div class="tile" style="background-image:url('<?=esc_url($image_value) ?>')"><span><?=the_title() ?></span></div>
        <?php }
          wp_reset_postdata();
        ?>        
      </div>
    </section>

    <section id="cert" class="banner">
      <h2 style="font-family: 'Merriweather', serif; font-weight: 300; font-size: 34px; margin: 0;">Подарочный сертификат</h2>
      <p>Лучший подарок - впечатления. Сертификат действует на любой мастер-класс.</p>
      <div class="cert-image" aria-hidden="true"></div>
      <div class="cta center-block">
        <a class="btn btn-cert" href="/request/?course=Сертификат">Отправить заявку</a>
      </div>
      <p class="banner-call">или позвоните по номеру:<br><a class="banner-phone" href="tel:491829">49-18-29</a></p>
    </section>

<?php require get_theme_file_path('includes/footer.php'); ?>
