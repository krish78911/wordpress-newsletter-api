<?php
/**
 * Get the url from the post array
 */
require_once 'ParameterInterface.php';
class GetUrl implements ParameterInterface { // return url

    public $url;

    function __construct($posts) {
        $this->url = "https://www.babyartikel.de/magazin/".$posts[0]->post_name;
    }

    function getData() {

        return $this->url;
    }
}

?>
