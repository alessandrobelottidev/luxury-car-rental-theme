<?php get_header(); ?>

<?php
$context = \Timber\Timber::get_context();

$query = '';

if (isset($_GET['query'])) {
    $query = $_GET['query'];
}

$args = array(
    'post_type' => 'car',
);

$cars = \Timber\Timber::get_posts($args);

$context['cars'] = array();
foreach ($cars as $car) {
    if (str_contains($car->post_title, $query)) {
        array_push($context['cars'], array(
            'ID' => $car->ID,
            'name' => get_field('name', $car->ID),
            'rental_price' => get_field('rental_price', $car->ID),
            'cover_image' => get_field('cover_image', $car->ID),
            'link' => get_permalink($car->ID)
        ));
    }
}

$context['title'] = "This is what we found";

\Timber\Timber::render("car-collection.twig", $context);
?>

<?php get_footer(); ?>
