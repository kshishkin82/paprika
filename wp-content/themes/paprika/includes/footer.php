<?php

declare(strict_types=1);
?>
    <footer id="contacts">
      <div class="footer-row">
        <div class="footer-left">
          <div class="footer-title">Кулинарная студия Paprika. Волгоград.</div>
          <a class="footer-link" href="/privacy-policy/">Политика конфиденциальности</a>
          <div>Copyright © 2015 - <?php echo esc_html((string) gmdate('Y')); ?></div>
          <div>Телефоны: <?php echo esc_html((string) get_theme_mod('paprika_phones', '8 (937) 530-10-90, 8 (927) 510-08-53')); ?></div>

        </div>

        <div class="footer-right">
          <nav class="footer-menu">
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
        </div>
      </div>
    </footer>
  </div>
  <?php wp_footer(); ?>
</body>
</html>
