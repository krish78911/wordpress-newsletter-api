<?php
/**
 * Get 1 post by offset/index entered in url 
 * and pass the post to get URL, Title or Image
 */
require_once 'ParameterInterface.php';
class Posts implements ParameterInterface {
    public $tag;
    public $index;

    function __construct($tag, $index) {
        $this->tag = $tag;
        $this->index = $index;
    }

    function getData() {

        $isTagName = 0;
        if (strpos($this->tag, ' ') !== false) {
            $isTagName = 1;
        }
        
        if($isTagName == 0) {
            $args = [
                //'numberposts'   => -1,
                'posts_per_page' => 1,
		        'offset'         => $this->index,
                'post_type'     => 'post',
                'post_status' => 'publish',
                'tag'           => $this->tag,
                'order'         => 'DESC',
            ];
        } else {
            $args = [
                //'numberposts'   => -1,
                'posts_per_page' => 1,
		        'offset'         => $this->index,
                'post_type'     => 'post',
                'post_status' => 'publish',
                'tax_query' => array(
                    array( 'taxonomy' => 'post_tag', 'field' => 'name', 'terms' => $this->tag )
                ),
                'order'         => 'DESC',
            ];
        }

        return (!empty(get_posts($args))) ? get_posts($args) : 0;
    }
}

?>
