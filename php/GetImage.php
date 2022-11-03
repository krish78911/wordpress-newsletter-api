<?php
/**
 * Get the image from the post array
 */
require_once 'ParameterInterface.php';
class GetImage implements ParameterInterface { // return image

    public $image;

    function __construct($posts) {
        $this->image = get_the_post_thumbnail_url( $posts[0]->ID, 'large' );;
    }

    function getData() {

        return $this->image;
    }
}

?>
