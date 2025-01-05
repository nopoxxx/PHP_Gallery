<?php
function getPage(array $pages) {
    $pageLink = "gallery";

    if (!empty($_GET['page'])) {

        $pageLink = (string) $_GET['page'];

    }

    if (empty($pages[$pageLink])) {

        $pageLink = "gallery";

    }

    return  $pages[$pageLink];

}

?>