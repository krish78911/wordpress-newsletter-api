<?php
/**
 * Get the title from the post array
 */
require_once 'ParameterInterface.php';
class GetTitle implements ParameterInterface { // return title

    public $title;

    function __construct($posts) {
        $this->title = $posts[0]->post_title;
    }

    function getData() {

        return $this->title;
    }
}

?>
