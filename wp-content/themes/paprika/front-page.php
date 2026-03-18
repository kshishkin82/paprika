<?php

declare(strict_types=1);

require get_theme_file_path('includes/header.php');
?>

    <main class="front-page-content">
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
        <a class="btn" href="/fotogalereya/">Посмотреть все фото</a>
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

    <section class="reviews-section" id="reviews">
      <div class="reviews-section__head">
        <h2>Отзывы гостей</h2>
        <p>Коротко о том, как проходят мастер-классы в Paprika Studio.</p>
      </div>

      <div class="reviews-slider js-reviews-slider">
        <button class="reviews-slider__nav reviews-slider__nav--prev" type="button" aria-label="Предыдущие отзывы">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M169.4 297.4C156.9 309.9 156.9 330.2 169.4 342.7L361.4 534.7C373.9 547.2 394.2 547.2 406.7 534.7C419.2 522.2 419.2 501.9 406.7 489.4L237.3 320L406.6 150.6C419.1 138.1 419.1 117.8 406.6 105.3C394.1 92.8 373.8 92.8 361.3 105.3L169.3 297.3z"/></svg>
        </button>
        <div class="reviews-slider__viewport">
          <div class="reviews-slider__track">
            <article class="review-card">
              <img class="review-card__avatar" src="https://i.pravatar.cc/160?img=5" alt="Алина" loading="lazy" />
              <h3 class="review-card__name">Алина Ш.</h3>
              <p class="review-card__date">Март 2025</p>
              <p class="review-card__text">Очень уютная атмосфера и понятная подача. За один вечер приготовили 3 блюда и сразу повторили дома.</p>
            </article>
            <article class="review-card">
              <img class="review-card__avatar" src="https://i.pravatar.cc/160?img=12" alt="Михаил" loading="lazy" />
              <h3 class="review-card__name">Михаил К.</h3>
              <p class="review-card__date">Февраль 2025</p>
              <p class="review-card__text">Пришли компанией на корпоратив. Все вовлечены, много практики, и в итоге реально вкусный ужин.</p>
            </article>
            <article class="review-card">
              <img class="review-card__avatar" src="https://i.pravatar.cc/160?img=20" alt="Екатерина" loading="lazy" />
              <h3 class="review-card__name">Екатерина Л.</h3>
              <p class="review-card__date">Январь 2025</p>
              <p class="review-card__text">Понравилось, что шеф объясняет без сложных терминов. Наконец-то разобралась с соусами и временем жарки.</p>
            </article>
            <article class="review-card">
              <img class="review-card__avatar" src="https://i.pravatar.cc/160?img=28" alt="Игорь" loading="lazy" />
              <h3 class="review-card__name">Игорь П.</h3>
              <p class="review-card__date">Декабрь 2024</p>
              <p class="review-card__text">Ходил на мастер-класс по итальянской кухне. Отличная организация и классные продукты, все на уровне.</p>
            </article>
            <article class="review-card">
              <img class="review-card__avatar" src="https://i.pravatar.cc/160?img=33" alt="Марина" loading="lazy" />
              <h3 class="review-card__name">Марина В.</h3>
              <p class="review-card__date">Ноябрь 2024</p>
              <p class="review-card__text">Брала сертификат в подарок, потом пришли вдвоем. Время пролетело незаметно, получили море эмоций.</p>
            </article>
            <article class="review-card">
              <img class="review-card__avatar" src="https://i.pravatar.cc/160?img=41" alt="Олег" loading="lazy" />
              <h3 class="review-card__name">Олег Д.</h3>
              <p class="review-card__date">Октябрь 2024</p>
              <p class="review-card__text">Хороший формат для новичков: все инструменты есть, рецепт четкий, и шеф всегда рядом, если нужна помощь.</p>
            </article>
            <article class="review-card">
              <img class="review-card__avatar" src="https://i.pravatar.cc/160?img=47" alt="Светлана" loading="lazy" />
              <h3 class="review-card__name">Светлана М.</h3>
              <p class="review-card__date">Сентябрь 2024</p>
              <p class="review-card__text">Очень понравилась подача и детали сервировки. Даже дети были в восторге от семейного мастер-класса.</p>
            </article>
            <article class="review-card">
              <img class="review-card__avatar" src="https://i.pravatar.cc/160?img=51" alt="Антон" loading="lazy" />
              <h3 class="review-card__name">Антон Р.</h3>
              <p class="review-card__date">Август 2024</p>
              <p class="review-card__text">Было динамично и без пауз. Отличный баланс между теорией и практикой, плюс дружелюбная команда.</p>
            </article>
            <article class="review-card">
              <img class="review-card__avatar" src="https://i.pravatar.cc/160?img=58" alt="Юлия" loading="lazy" />
              <h3 class="review-card__name">Юлия Т.</h3>
              <p class="review-card__date">Июль 2024</p>
              <p class="review-card__text">Удобно, что можно выбрать программу под уровень. После занятия перестала бояться сложных блюд.</p>
            </article>
            <article class="review-card">
              <img class="review-card__avatar" src="https://i.pravatar.cc/160?img=65" alt="Роман" loading="lazy" />
              <h3 class="review-card__name">Роман Н.</h3>
              <p class="review-card__date">Июнь 2024</p>
              <p class="review-card__text">Лучшая идея для свидания: вкусно, интересно и с результатом. Обязательно вернемся на новый курс.</p>
            </article>
          </div>
        </div>
        <button class="reviews-slider__nav reviews-slider__nav--next" type="button" aria-label="Следующие отзывы">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M471.1 297.4C483.6 309.9 483.6 330.2 471.1 342.7L279.1 534.7C266.6 547.2 246.3 547.2 233.8 534.7C221.3 522.2 221.3 501.9 233.8 489.4L403.2 320L233.9 150.6C221.4 138.1 221.4 117.8 233.9 105.3C246.4 92.8 266.7 92.8 279.2 105.3L471.2 297.3z"/></svg>
        </button>
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
    </main>

<?php require get_theme_file_path('includes/footer.php'); ?>
