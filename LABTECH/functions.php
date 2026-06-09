<?php
function redirectToUrl($url) {
    header('Location: ' . $url);
    exit;
}
?>
