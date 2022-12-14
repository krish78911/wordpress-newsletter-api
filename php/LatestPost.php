<?php
/**
 * Get 1 post by offset/index entered in url 
 * and pass the post to get URL, Title or Image
 */
require_once 'ParameterInterface.php';
class LatestPost implements ParameterInterface {

    public $index;

    function __construct($index) {
        $this->index = $index;
    }

    function getData() {

        $args = [
            'posts_per_page' => 1,
            'offset'         => $this->index,
            'post_type'     => 'post',
            'post_status' => 'publish',
            'order'         => 'DESC',
        ];

        return (!empty(get_posts($args))) ? get_posts($args) : 0;
    }
}

?>
