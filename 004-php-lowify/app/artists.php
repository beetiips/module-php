<?php

require_once'inc/page.inc.php';
require_once'inc/database.inc.php';

try {
    $db = new DatabaseManager(
        dsn: "mysql:host=mysql;dbname=lowify;charset=utf8mb4",
        username: 'lowify',
        password: 'lowifypassword'
    );
} catch (PDOException $ex) {
    echo "Error while connecting to database: " . $ex->getMessage();
    exit;
}

$allArtists = [];

try {
    $allArtists = $db->executeQuery("SELECT id, name, cover FROM artist");
} catch (PDOException $ex) {
    echo "Error while executing query: " . $ex->getMessage();
    exit;
}

$artistsAsHTML = "";

foreach ($allArtists as $artist) {
    $artistName = $artist['name'];
    $artistID = $artist['id'];
    $artistCover = $artist['cover'];

    $artistsAsHTML .= <<<HTML
        <div>
            <a href="artist.php?id=$artistID">
            <img src="$artistCover">
            <h2>{$artistName}</h2>
            </a>
        </div>
HTML;
}

$cssLink = '<link rel="stylesheet" href="style.css">';

$html = <<<HTML
    <div class="container">
        <a href="index.php" class="back-link"> <- Back to home page</a>
        <h1>Artists</h1>
        <div class="artist-grid">{$artistsAsHTML}</div>
    </div>
HTML;

$page = new HTMLPage(title: "Lowify - Artists");
$page->addContent($cssLink . $html);
echo $page->render();