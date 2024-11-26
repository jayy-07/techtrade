<?php

require_once '../classes/User.php';

function fetch_regions() {
    $user = new User();
    return $user->get_regions();
}
?>
