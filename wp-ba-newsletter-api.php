<?php
/**
 * Plugin Name: WP BA Newsletter API
 * Description: Get Post Image, Title and URL
 * Version: 1.0.0
 * Author: KP Family
 */

function newsletter_posts($data) {
    // include classes
    require_once plugin_dir_path( __FILE__ ) . '/php/DataFactory.php';
    require_once plugin_dir_path( __FILE__ ) . '/php/Posts.php';
    require_once plugin_dir_path( __FILE__ ) . '/php/LatestPost.php';
    require_once plugin_dir_path( __FILE__ ) . '/php/GetImage.php';
    require_once plugin_dir_path( __FILE__ ) . '/php/GetUrl.php';
    require_once plugin_dir_path( __FILE__ ) . '/php/GetTitle.php';
    require_once plugin_dir_path( __FILE__ ) . '/php/TextImage.php';
    require_once plugin_dir_path( __FILE__ ) . '/php/GetAvatar.php';
    require_once plugin_dir_path( __FILE__ ) . '/php/GetAuthorName.php';

    $client = new DataFactory(); // load the factory class
    
    // get the post
    $tag = urldecode($data['tag']);
    //echo $tag;
    $getIndex = $data['index']-1;
    //$client->setDataType(new Posts($tag, $data['index']));
    $client->setDataType(new Posts($tag, $getIndex));
    $posts = $client->getDataType();

    $defaultTag     = 0;
    $defaultTagName = '';
    if($posts == 0) { // if no post found by tag name or index then show latest post
        $client->setDataType(new LatestPost($getIndex));
        $posts = $client->getDataType();
        $tags = wp_get_post_terms($posts[0]->ID, 'category');
        if(empty($tags)) {
            $tags = wp_get_post_terms($posts[0]->ID, 'tag');
            if(empty($tags)) {
                $tags = wp_get_post_terms($posts[0]->ID, 'post_tag');
            }
        }
        $defaultTag = 1;
        $defaultTagName = $tags[0]->name;
    }

    
    if($data['return'] == 'post-url') { // return url
        $client->setDataType(new GetUrl($posts));
        header("Location: ".$client->getDataType()."");
        die();
    }
    elseif($data['return'] == 'post-image') { // return image
        $client->setDataType(new GetImage($posts));
        
        $filename = $client->getDataType();
        $percent = 0.85;

        // Content type
        header('Content-Type: image/jpeg');

        // Get new sizes
        list($width, $height) = getimagesize($filename);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;

        // Load
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        $source = imagecreatefromjpeg($filename);

        // Resize
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        // Output
        imagejpeg($thumb);

    }
    elseif($data['return'] == 'post-title') { // return title and convert to image
        $client->setDataType(new GetTitle($posts)); // get title
        $text = $client->getDataType();
        $client->setDataType(new GetAuthorName($posts)); // get author name
        $author = $client->getDataType();
        $client->setDataType(new GetAvatar($posts)); // get avatar
        $avatar = $client->getDataType();
        if($defaultTag != 0) {
            $client->setDataType(new TextImage($text, $author, $avatar, $defaultTagName, $defaultTag)); // convert text to image
        } else {
            $client->setDataType(new TextImage($text, $author, $avatar, $tag)); // convert text to image
        }
        
        $client->getDataType();
        die();
    }
    else { // default
        $errMsg = "no post found.....";
        return $errMsg;
    }
 }

 add_action('rest_api_init', function() {
    register_rest_route('newsletter-api', 'tag=(?P<tag>([a-zA-Z0-9ÄÖÜäöüß-]|%20|%C3%B6|%C3%96|%C3%BC|%C3%9C|%C3%A4|%C3%84|%C3%9F)+)&index=(?P<index>[a-zA-Z0-9-]+)&return=(?P<return>[a-zA-Z0-9-]+)', [
        'methods' => 'GET',
        'callback' => 'newsletter_posts',
    ]);
 });
 
?>
