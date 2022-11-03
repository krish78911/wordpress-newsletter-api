<?php
/**
 * Plugin Name: WP BA Newsletter API
 * Description: Get Post Image, Title and URL
 */

function newsletter_posts($data) {
    // include classes
    require_once plugin_dir_path( __FILE__ ) . '/php/DataFactory.php';
    require_once plugin_dir_path( __FILE__ ) . '/php/Posts.php';
    require_once plugin_dir_path( __FILE__ ) . '/php/GetImage.php';
    require_once plugin_dir_path( __FILE__ ) . '/php/GetUrl.php';
    require_once plugin_dir_path( __FILE__ ) . '/php/GetTitle.php';

    $client = new DataFactory();

    // get the post
    $client->setDataType(new Posts($data['tag'], $data['index']));
    $posts = $client->getDataType();

    if($data['return'] == 'post-url') { // get url
        $client->setDataType(new GetUrl($posts));
        return $client->getDataType();
    }
    elseif($data['return'] == 'post-image') { // get image
        $client->setDataType(new GetImage($posts));
        return $client->getDataType();
    }
    elseif($data['return'] == 'post-title') { // get title
        $client->setDataType(new GetTitle($posts));
        return $client->getDataType();
    }
    else { // default
        $errMsg = "no post found.....";
        return $errMsg;
    }
    
 }

 add_action('rest_api_init', function() {
    register_rest_route('newsletter-api', 'tag=(?P<tag>[a-zA-Z0-9-]+)&index=(?P<index>[a-zA-Z0-9-]+)&return=(?P<return>[a-zA-Z0-9-]+)', [
        'methods' => 'GET',
        'callback' => 'newsletter_posts',
    ]);
 });
 
 
?>
