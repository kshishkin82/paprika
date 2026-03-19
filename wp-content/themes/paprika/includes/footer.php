<?php

declare(strict_types=1);
?>
    <footer id="contacts">

      <div class="footer-row">

      <div class="contacts-block">              
                <h3>Контакты</h3>
                <div class="l f">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="26" viewBox="0 0 14 19" xml:space="preserve"><path d="M14 18.5H0v-1h6.7l-.1-.1C6.3 17.1.5 11.1.5 6.7.5 3.3 3.4.5 7 .5s6.5 2.8 6.5 6.2c0 4.4-5.9 10.5-6.1 10.7l-.1.1H14zm-7-17c-3 0-5.5 2.3-5.5 5.2 0 3.4 4.2 8.3 5.5 9.7 1.3-1.4 5.5-6.2 5.5-9.7 0-2.9-2.5-5.2-5.5-5.2m0 7.8c-1.4 0-2.5-1.1-2.5-2.5S5.6 4.3 7 4.3s2.5 1.1 2.5 2.5S8.4 9.3 7 9.3m0-4c-.8 0-1.5.7-1.5 1.5S6.2 8.3 7 8.3s1.5-.7 1.5-1.5S7.8 5.3 7 5.3"/></svg>
                  <p>                
                    <?= wp_kses_post( get_theme_mod('paprika_address') ) ?>
                  </p>
                </div>
                <div class="l s">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="26" viewBox="0 0 19 15" xml:space="preserve"><path d="M16.3 14.5H2.7c-1.2 0-2.2-1-2.2-2.1V2.6C.5 1.4 1.5.5 2.7.5h13.6c1.2 0 2.2 1 2.2 2.1v9.8c0 1.1-1 2.1-2.2 2.1M1.5 4.3v8.1c0 .6.5 1.1 1.2 1.1h13.6c.7 0 1.2-.5 1.2-1.1V4.3l-8 4.6zm0-1.1 8 4.6 8-4.6v-.6c0-.6-.5-1.1-1.2-1.1H2.7c-.7 0-1.2.5-1.2 1.1z"/></svg>
                  <p><a href="mailto:<?=get_theme_mod('paprika_email')?>" rel="nofollow"><?=get_theme_mod('paprika_email')?></a></p>
                </div>

                <div class="l f">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="26" viewBox="0 0 16 16" xml:space="preserve"><path d="M14.2 16C6.4 16 0 9.6 0 1.8 0 .8.8 0 1.8 0h2.7c1 0 1.8.8 1.8 1.8 0 .8.1 1.7.4 2.5.2.6 0 1.3-.4 1.8L5.1 7.3C6 8.8 7.3 10.1 8.8 11L10 9.8c.5-.5 1.2-.6 1.8-.4.8.3 1.6.4 2.5.4 1 0 1.8.8 1.8 1.8v2.7c-.1.9-.9 1.7-1.9 1.7M1.8 1c-.5 0-.8.3-.8.8C1 9.1 6.9 15 14.2 15c.4 0 .8-.3.8-.8v-2.7c0-.4-.3-.8-.8-.8-1 0-1.9-.1-2.8-.4-.3-.1-.6 0-.8.2l-1.7 1.7-.3-.2c-2-1.1-3.5-2.7-4.6-4.6l-.2-.3 1.7-1.7c.2-.2.3-.5.2-.8-.3-.9-.4-1.9-.4-2.8 0-.5-.4-.8-.8-.8z"/></svg>
                  <p><?= wp_kses_post(get_theme_mod('paprika_phones')) ?></p>
                </div>              
            </div>

        <div class="footer-left">
          <div class="footer-title"><?php echo esc_html((string) get_bloginfo('name')); ?>.</div>        
          <nav class="footer-menu">
            <?php
            wp_nav_menu( [
              'theme_location' => 'footer_location',
              'menu'           => 'footer-menu',
              'container'      => 'nav',
              'menu_class'     => 'footer-nav-list',        
              'walker' => new Paprika_Link_Only_Walker(),
              'fallback_cb' => false,
            ]);            
            ?>
          </nav>        
          <div>Copyright © 2015 - <?php echo esc_html((string) gmdate('Y')); ?></div>
          
        
        </div>
        
      </div>
    </footer>
  </div>
  <?php wp_footer(); ?>
</body>
</html>
