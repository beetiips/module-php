<?php

require_once 'inc/page.inc.php';
require_once 'inc/database.inc.php';

$id = $_GET['id'];

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

$allAlbums = [];

try {
    $allAlbums = $db->executeQuery("SELECT a.*, t.name AS artist_name, t.id AS artist_id FROM album a INNER JOIN artist t ON a.artist_id = t.id WHERE a.id = ?", [$id]);
} catch (PDOException $ex) {
    echo "Error while executing query: " . $ex->getMessage();
    exit;
}

$album = $allAlbums[0];
$albumID = $album['id'];
$albumName = $album['name'];
$albumCover = $album['cover'];
$artistID = $album['artist_id'];
$artistName = $album['artist_name'];

$allArtists = [];

try {
    $allArtists = $db->executeQuery("SELECT * FROM artist WHERE id = ?", [$artistID]);
} catch (PDOException $ex) {
    echo "Error while executing query: " . $ex->getMessage();
    exit;
}

$albumSongs = [];

try {
    $albumSongs = $db->executeQuery("SELECT * FROM song WHERE album_id = ?", [$id]);
} catch (PDOException $ex) {
    echo "Error while executing query: " . $ex->getMessage();
    exit;
}

$albumSongsAsHTML = "";

foreach ($albumSongs as $song) {
    $songName = $song['name'];
    $songDuration = $song['duration'];
    $songNote = number_format($song['note'], 1) . '/5';
    $songID = $song['id'];

    $albumSongsAsHTML .= <<<HTML
    <div class="album-song">
    <img src="{$albumCover}" alt="{$albumName}">
    <div>{$songName}</div>
    <div>{$songNote}</div>
    <div>{$songDuration}</div>
HTML;

}

$cssLink = '<link rel="stylesheet" href="style.css">';

$html = <<<HTML
<div class="container">
    <a href="index.php" class="back-link"> <- Back to home page</a>
    <div class="album-detail">
    <img src="{$album['cover']}" alt="{$album['name']}">
    <a href="artist.php?id={$artistID}">{$artistName}</a> <div>{$albumName}</div>
    <div>{$album['release_date']}</div>
    <div class="album-songs-list">
        <h3>Tracklist</h3>
        {$albumSongsAsHTML}
    </div>
</div>
HTML;

$page = new HTMLPage(title: "Lowify - $albumName");
$page->addContent($cssLink . $html);
echo $page->render();