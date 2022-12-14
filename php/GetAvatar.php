<?php
/**
 * Get the avatar from the post array
 */
require_once 'ParameterInterface.php';
class GetAvatar implements ParameterInterface { // return avatar

    public $avatar;

    function __construct($posts) {
        $this->avatar = get_avatar_url($posts[0]->post_author, array('size' => 450));
    }

    function getData() {

        return $this->avatar;
    }
}

?>
