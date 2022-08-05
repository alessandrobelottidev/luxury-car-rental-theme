<?php
$cars = get_posts(array(
    'posts_per_page'    => -1,
    'post_type'         => 'car',
    'fields' => 'ids'
));

$cars_map = array();
for ($i = 0; $i < count($cars); $i++) {
    $cars_map[$cars[$i]] = get_field('likes', $cars[$i]);
}

if (count($cars_map) >= 3) {
    $top_cars = array();

    for ($i = 0; $i < 3; $i++) {
        $max_key = -1;
        $mav_val = -1;

        foreach ($cars_map as $ID => $likes) {
            if ($likes > $max_val) {
                $max_val = $likes;
                $max_key = $ID;
            }
        }

        $top_cars[$i] = $max_key;
        unset($cars_map[$max_key]);
        unset($max_key);
        unset($max_val);
    }

    $context['hottest_cars'] = array();
    for ($i = 0; $i < 3; $i++) {
        array_push($context['hottest_cars'], array(
            'ID' => $top_cars[$i],
            'name' => get_field('name', $top_cars[$i]),
            'rental_price' => get_field('rental_price', $top_cars[$i]),
            'cover_image' => get_field('cover_image', $top_cars[$i]),
            'link' => get_permalink($top_cars[$i])
        ));
    }
} else {
    $context['hottest_cars'] = array();
    foreach ($cars_map as $ID => $likes) {
        array_push($context['hottest_cars'], array(
            'ID' => $ID,
            'name' => get_field('name', $ID),
            'rental_price' => get_field('rental_price', $ID),
            'cover_image' => get_field('cover_image', $ID),
            'link' => get_permalink($ID)
        ));
    }
}
