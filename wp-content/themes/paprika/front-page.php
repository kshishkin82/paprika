<?php

declare(strict_types=1);

require get_theme_file_path('includes/header.php');
?>

    <section class="hero">
      <div>
        <h1>Приглашаем Вас в нашу в кулинарную студию</h1>
        <p>Программа мастер-классов интересна и новичкам, и опытным кулинарам. Здесь можно получить навыки приготовления разнообразных блюд, почерпнуть интересные идеи для кулинарных экспериментов, узнать новые оригинальные рецепты и зарядиться вдохновением для создания собственных гастрономических шедевров.</p>
        <p>Паприка - первая кулинарная школа Волгограда. Мы проводим обучение поваров и кондитеров, научим как любителей так и профессионалов.</p>
        <h2>Готовить с нами легко!!!</h2>
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
            <a class="block" style="background-image:url(<?=$mypods->display('heroimage._src.medium') ?>)" href="<?=$link ?>">
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
        <div class="tile photo-1"><span>Гурманские вечера</span></div>
        <div class="tile photo-2"><span>Командные форматы</span></div>
        <div class="tile photo-3"><span>Школа со вкусом</span></div>
        <div class="tile photo-4"><span>Сладкий стол</span></div>
        <div class="tile photo-5"><span>Сезонные блюда</span></div>
        <div class="tile photo-6"><span>Детские праздники</span></div>
      </div>
    </section>

    <section id="cert" class="banner">
      <h2 style="font-family: 'Merriweather', serif; font-weight: 300; font-size: 34px; margin: 0;">Подарочный сертификат</h2>
      <p>Лучший подарок - впечатления. Сертификат действует на любой мастер-класс.</p>
      <div class="cert-image" aria-hidden="true"></div>
      <div class="cta center-block">
        <a class="btn btn-cert" href="#contacts">Отправить заявку</a>
      </div>
      <p class="banner-call">или позвоните по номеру:<br><a class="banner-phone" href="tel:491829">49-18-29</a></p>
    </section>

<?php require get_theme_file_path('includes/footer.php'); ?>
