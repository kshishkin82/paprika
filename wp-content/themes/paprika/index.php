<?php

declare(strict_types=1);

require get_theme_file_path('includes/header.php');
?>
    <style>
        .center-header {
            display: flex;
            align-items: center;
            column-gap: 10px;
        }

        .center-header .request-link {
          align-self: center;
          width: fit-content;
          margin-top:4px;
          }
    </style>
    <main class="page-content">
      <?php

      if (have_posts()) {

        while (have_posts()) {

          $categories = get_the_category(); 
          $category_name = $categories[0]->name;

          the_post();
          
          ?>

          <div class="center-header">
            <h1><?=esc_html(get_the_title()) ?></h1>
            <?php 
              if (is_single() && $category_name == 'Мастер класс') {
              $nearest_date = pods_field_display("nearest_date");
                if ($nearest_date) $nearest_date = " ".$nearest_date;
            ?>
            <a class="request-link" href="/request/?course=<?=get_the_title().$nearest_date ?>">Записаться</a>
            <?php } ?>
        </div>
        <?php

        the_content();
        
        }
      }
      ?>
    </main>

<?php require get_theme_file_path('includes/footer.php'); ?>
