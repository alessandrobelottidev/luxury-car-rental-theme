<?php get_header(); ?>

<?php

$context = \Timber\Timber::get_context();

// LOAD HOTTEST CARS
include_once dirname(__FILE__) . '/includes/hottest-cars.php';

\Timber\Timber::render("front-page.twig", $context);

?>

<?php get_footer(); ?>