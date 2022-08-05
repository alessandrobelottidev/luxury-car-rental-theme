<?php

function get_acf_field(string $field_name)
{
    $field = get_field($field_name);

    if ($field) {
        return $field;
    } else {
        return '';
    }
}

/*** 
 * Activate wp-admin theme
 ***/
add_action('admin_enqueue_scripts', 'luxury_car_rental_theme_my_admin_theme_style');
add_action('login_enqueue_scripts', 'luxury_car_rental_theme_my_admin_theme_style');
function luxury_car_rental_theme_my_admin_theme_style()
{
    wp_enqueue_style('my-admin-theme',  get_template_directory_uri() . '/wp-admin-theme/wp-admin.css');
    wp_enqueue_style('my-login-theme', get_template_directory_uri() . '/wp-admin-theme/wp-login.css');
}

/***
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 ***/
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($composer_autoload)) {
    require_once $composer_autoload;
    $timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if (!class_exists('Timber')) {

    add_action(
        'admin_notices',
        function () {
            echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
        }
    );

    add_filter(
        'template_include',
        function ($template) {
            return get_stylesheet_directory() . '/static/no-timber.html';
        }
    );
    return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('templates', 'views');

/***
 * Disable default media organization Media
 ***/
$uploads_use_yearmonth_folders = get_option('uploads_use_yearmonth_folders', 1);
if ($uploads_use_yearmonth_folders) {
    update_option('uploads_use_yearmonth_folders', 0);
}

/***
 * Security settings
 ***/
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', 'wp_remove_version');
function wp_remove_version()
{
    return '';
}

// Remove ?ver=1.0.0 from css and js
add_filter('style_loader_src', 'at_remove_wp_ver_css_js', 9999);
add_filter('script_loader_src', 'at_remove_wp_ver_css_js', 9999);
function at_remove_wp_ver_css_js($src)
{
    if (strpos($src, 'ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}

/***
 * Reduce numbers of images produced by the Wordpress Upload Media
 ***/
add_filter('intermediate_image_sizes_advanced', 'luxury_car_rental_theme_filter_image_sizes');
function luxury_car_rental_theme_filter_image_sizes($sizes)
{
    unset($sizes['medium']);          // Remove Thumbnail (150 x 150 hard cropped)
    unset($sizes['medium']);          // Remove Medium resolution (300 x 300 max height 300px)
    unset($sizes['medium_large']);    // Remove Medium Large (added in WP 4.4) resolution (768 x 0 infinite height)
    unset($sizes['large']);           // Remove Large resolution (1024 x 1024 max height 1024px)
    /* With WooCommerce */
    unset($sizes['shop_thumbnail']);  // Remove Shop thumbnail (180 x 180 hard cropped)
    unset($sizes['shop_catalog']);    // Remove Shop catalog (300 x 300 hard cropped)
    unset($sizes['shop_single']);     // Shop single (600 x 600 hard cropped)
    return $sizes;
}

/*** 
 * Activate SVG media upload
 ***/
add_filter('upload_mimes', 'cc_mime_types');
function cc_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

/***
 * Adding custom post type: CAR
 ***/
add_action('init', 'luxury_car_rental_theme_car_init');
function luxury_car_rental_theme_car_init()
{
    $labels = array(
        'name'               => _x('Cars', 'post type general name', 'luxury_car_rental_theme'),
        'singular_name'      => _x('Car', 'post type singular name', 'luxury_car_rental_theme'),
        'menu_name'          => _x('Cars', 'admin menu', 'luxury_car_rental_theme'),
        'name_admin_bar'     => _x('Car', 'add new on admin bar', 'luxury_car_rental_theme'),
        'add_new'            => _x('Add New', 'Car', 'luxury_car_rental_theme'),
        'add_new_item'       => __('Add New Car', 'luxury_car_rental_theme'),
        'new_item'           => __('New Car', 'luxury_car_rental_theme'),
        'edit_item'          => __('Edit Car', 'luxury_car_rental_theme'),
        'view_item'          => __('View Car', 'luxury_car_rental_theme'),
        'all_items'          => __('All Cars', 'luxury_car_rental_theme'),
        'search_items'       => __('Search Cars', 'luxury_car_rental_theme'),
        'parent_item_colon'  => __('Parent Cars:', 'luxury_car_rental_theme'),
        'not_found'          => __('No Cars found.', 'luxury_car_rental_theme'),
        'not_found_in_trash' => __('No Cars found in Trash.', 'luxury_car_rental_theme')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Description.', 'luxury_car_rental_theme'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'car'),
        'capability_type'    => 'post',
        'has_archive'        => false, // TOCHECK: may need to turn this one on
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title')
    );

    register_post_type('car', $args);
}

/***
 * Add theme support for custom logo
 ***/
add_action('after_setup_theme', 'luxury_car_rental_theme_custom_logo_setup');
function luxury_car_rental_theme_custom_logo_setup()
{
    $defaults = array(
        'height'               => 100,
        'width'                => 400,
        'flex-height'          => true,
        'flex-width'           => true,
        'header-text'          => array('site-title', 'site-description'),
        'unlink-homepage-logo' => true,
    );

    add_theme_support('custom-logo', $defaults);
}

/* CUSTOM ADMIN LOGIN HEADER LOGO */
function luxury_car_rental_theme_custom_login_logo()
{
    echo '<style  type="text/css"> h1 a {  background-image:url(\'' . get_stylesheet_directory_uri()  . '/assets/images/logo.png\')  !important; } </style>';
}
add_action('login_head',  'luxury_car_rental_theme_custom_login_logo');

/***
 * Enqueue CSS & JS
 ***/
add_action('wp_enqueue_scripts', 'luxury_car_rental_theme_assets');
function luxury_car_rental_theme_assets()
{
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css');
    wp_enqueue_style('poppins', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,500&display=swap');
    wp_enqueue_style('globals', get_template_directory_uri() . '/assets/styles/globals.min.css');

    wp_enqueue_script('jquery', 'https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js', array(), false, true);
}

/***
 * Register my header menu
 */
function register_header_menu()
{
    register_nav_menus(
        array(
            'header-menu' => __('Header Menu'),
        )
    );
}
add_action('init', 'register_header_menu');

/***
 * Taxonomy template precedence
 ***/
/*
function luxury_car_rental_theme_doc_help_topic_template($template)
{
    if (is_tax('genre') && is_post_type_archive('car'))
        return get_taxonomy_template();

    return $template;
}

add_filter('template_include', 'luxury_car_rental_theme_doc_help_topic_template');
*/

/**
 * Simple like feature, not really secure and doesn't guarantee uniqueness of data
 */
function update_like()
{
    if (isset($_POST['id']) && isset($_POST['like'])) {
        $id = $_POST['id'];
        $like = $_POST['like'];

        // Check if car ID is valid
        if ('publish' == get_post_status($id)) {
            if ('car' == get_post_type($id)) {
                $current_likes = get_field('likes', $id);

                // Update database ACF value
                if ($like == 'true') {
                    update_field('likes', $current_likes + 1, $id);
                } else {
                    update_field('likes', $current_likes - 1, $id);
                }

                // Send request status to the client
                echo json_encode(true);
                exit;
            }
        }
    }

    // Send request status to the client
    echo json_encode(false);
    exit;
}

add_action('wp_ajax_update_like', 'update_like');
add_action('wp_ajax_nopriv_update_like', 'update_like');


/**
 * Get all cars based on array of IDs
 */
function retrieve_cars($arr)
{
    if (isset($_POST['arr'])) {
        $arr = $_POST['arr'];
        $cars = array();

        for ($i = 0; $i < count($arr); $i++) {
            if ('publish' == get_post_status($arr[$i]) && 'car' == get_post_type($arr[$i])) {
                array_push($cars, array(
                    'ID' => $arr[$i],
                    'name' => get_field('name', $arr[$i]),
                    'rental_price' => get_field('rental_price', $arr[$i]),
                    'cover_image' => get_field('cover_image', $arr[$i]),
                    'link' => get_permalink($arr[$i])
                ));
            }
        }

        echo json_encode($cars);
        exit;
    }

    echo json_encode(null);
    exit;
}

add_action('wp_ajax_retrieve_cars', 'retrieve_cars');
add_action('wp_ajax_nopriv_retrieve_cars', 'retrieve_cars');

/**
 * Retrive name and image of search query
 */
function retrieve_basic_info_of_cars_query()
{
    if (isset($_POST['query'])) {
        $query = $_POST['query'];

        $cars = get_posts([
            'post_type' => 'car',
            'post_status' => 'publish',
            'numberposts' => -1
        ]);

        $count = 0;
        $filtered_cars = array();

        foreach ($cars as $car) {
            if ($count == 3) break;

            if (str_contains($car->post_title, $query)) {
                array_push($filtered_cars, array(
                    'name' => get_field('name', $car->ID),
                    'cover_image' => get_field('cover_image', $car->ID),
                    'link' => get_permalink($car->ID)
                ));

                $count++;
            }
        }

        echo json_encode($filtered_cars);
        exit;
    }

    echo json_encode(null);
    exit;
}

add_action('wp_ajax_retrieve_basic_info_of_cars_query', 'retrieve_basic_info_of_cars_query');
add_action('wp_ajax_nopriv_retrieve_basic_info_of_cars_query', 'retrieve_basic_info_of_cars_query');
