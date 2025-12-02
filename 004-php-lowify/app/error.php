<?php

require_once'inc/page.inc.php';

$errorMessage = $_GET['getmessage()'];

$cssLink = '<link rel="stylesheet" href="style.css">';

$html = <<<HTML
<a href="index.php" class="backlink"> <- Back to home page</a>
    <div class="error">{$errorMessage}</div>
HTML;


$page = new HTMLPage(title: "Lowify - ERROR");
$page->addContent($cssLink . $html);
echo $page->render();