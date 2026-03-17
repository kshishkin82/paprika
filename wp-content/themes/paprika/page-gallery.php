<?php
declare(strict_types=1);
require get_theme_file_path('includes/header.php');
?>

<main class="page-content">
<h1><?=get_the_title() ?></h1>
<?=the_content() ?>
<section class="event-grid" style="padding-top:20px">
 <?php
    $category = get_term_by('name', 'Галерея', 'category');
  
    $posts_query = new WP_Query([
        'cat' => (int) $category->term_id,
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'ignore_sticky_posts' => true,
      ]);

    while ($posts_query->have_posts()) {
      $posts_query->the_post();

      $post_id = get_the_ID();
      $image_value = pods_field_display("heroimage._src.medium_large");
      $title = get_the_title();
      $style_attr = '';
      $image_url = is_string($image_value) ? trim($image_value) : '';
      if ($image_url !== '' && filter_var($image_url, FILTER_VALIDATE_URL)) {
        $style_attr = ' style="background-image:url(' . esc_url($image_url) . ');"';
      }
    ?>
    <article class="event-block"<?php echo $style_attr; ?>>
      <a class="event-block__link" href="<?php echo esc_url(get_permalink()); ?>">
        <div class="event-block__content">
          <h2 class="event-block__title"><?php echo esc_html($title); ?></h2>          
        </div>
      </a>
    </article>
    <?php
    }
    wp_reset_postdata()
  ?>
  </section>
</main>

<?php require get_theme_file_path('includes/footer.php'); ?>

