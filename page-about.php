<?php
/**
 * The template for displaying the about page.
 * the only difference between this and page.php
 * is the query that picks up the people posts
 * it's hardcoded for page-about.twig
 */

$context = Timber::context();

$timber_post = new Timber\Post();



$context['people'] = $posts = Timber::get_posts([
  'post_type' => 'person',
  'post_status' => 'publish',
  'numberposts' => -1,
  'orderby' => 'ID',
  'order' => 'ASC',

]);
$context['teams'] = Timber::get_terms('team', array( 'orderby' => 'ID','order' => 'DESC',  ));



$context['post'] = $timber_post;
Timber::render( array( 'page-about.twig' ), $context );

