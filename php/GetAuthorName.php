<?php
/**
 * Get the GetAuthorName from the post array
 */
require_once 'ParameterInterface.php';
class GetAuthorName implements ParameterInterface { // return GetAuthorName

    public $author;

    function __construct($posts) {
        $this->author = get_the_author_meta('display_name', $posts[0]->post_author);
    }

    function getData() {

        return $this->author;
    }
}

?>
