<?php

declare(strict_types=1);

require get_theme_file_path('includes/header.php');
?>

<main class="page-content">
  <?php
  if (have_posts()) {

    while (have_posts()) {
      the_post();
      echo '<article class="post-preview">';
      echo '<h2><a class="text-link" href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h2>';
      the_excerpt();
      echo '</article>';
    }
  } else {
    echo '<p>Посты не найдены.</p>';
  }
  ?>
</main>

<?php require get_theme_file_path('includes/footer.php'); ?>
