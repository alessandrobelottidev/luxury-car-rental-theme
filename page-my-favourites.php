<?php get_header(); ?>

<?php

$context = \Timber\Timber::get_context();

\Timber\Timber::render("my-favourites.twig", $context);

?>

<?php get_footer(); ?>