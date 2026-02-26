<?php

declare(strict_types=1);

require get_theme_file_path('includes/header.php');
?>

    <main class="page-content">
      <?php

      if (have_posts()) {

        while (have_posts()) {
          the_post();
          echo '<h1>' . esc_html(get_the_title()) . '</h1>';
          the_content();
        }
      }
      ?>
    </main>

<?php require get_theme_file_path('includes/footer.php'); ?>
