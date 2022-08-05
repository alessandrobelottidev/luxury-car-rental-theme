<?php get_header(); ?>
<?php
$context = \Timber\Timber::get_context();

$post = new \Timber\Post();
$context['post'] = $post;

$context['carID'] = $post->ID;
$context['permalink'] = get_permalink($post->ID);
$context['name'] = get_acf_field('name', $post->ID);
$context['rental_price'] = get_acf_field('rental_price', $post->ID);
$context['cover_image'] = get_acf_field('cover_image', $post->ID);
$context['different_featured_image'] = get_acf_field('different_featured_image', $post->ID);
$context['featured_image'] = get_acf_field('featured_image', $post->ID);
$context['description'] = get_acf_field('description', $post->ID);
$context['preview_default_image'] = acf_photo_gallery('gallery', $post->ID)[0]['full_image_url'];
$context['images'] = acf_photo_gallery('gallery', $post->ID);
$context['table'] = get_field('car_specifications', $post->ID);
$context['likes'] = get_acf_field('likes', $post->ID);

include_once dirname(__FILE__) . "/includes/hottest-cars.php";

\Timber\Timber::render("single-car.twig", $context);
?>

<?php get_footer(); ?>
