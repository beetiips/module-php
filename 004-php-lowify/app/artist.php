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
$allArtists = [];

try {
    $allArtists = $db->executeQuery("SELECT * FROM artist WHERE id = ?", [$id]);
} catch (PDOException $ex) {
    echo "Error while executing query: " . $ex->getMessage();
    exit;
}

$artist = $allArtists[0];

$monthlyListeners = $artist['monthly_listeners'];
if ($monthlyListeners >= 1000000) {
    $monthlyListeners = $monthlyListeners / 1000000;
    $monthlyListeners = number_format($monthlyListeners, 1) . "M";
} elseif ($monthlyListeners >= 1000) {
    $monthlyListeners = $monthlyListeners / 1000;
    $monthlyListeners = number_format($monthlyListeners, 1) . "K";
}

$artistName = $artist['name'];

$topSongs = [];

try {
    $topSongs = $db->executeQuery("SELECT s.id as song_id, s.name as song_name, s.duration as song_duration, s.note as song_note, a.cover as album_cover, a.name as album_name FROM song s INNER JOIN album a ON s.album_id = a.id WHERE s.artist_id = ? ORDER BY s.note DESC LIMIT 5", [$id]);
} catch (PDOException $ex) {
    echo "Error while executing query: " . $ex->getMessage();
    exit;
}

$topSongsAsHTML = "";

foreach ($topSongs as $song) {
    $songName = $song['song_name'];
    $songDuration = $song['song_duration'];
    $albumName = $song['album_name'];
    $albumCover = $song['album_cover'];
    $songNote = number_format($song['note'], 1) . '/5';

    $songDuration = $song['song_duration'];
    $minutes = intval($rawDuration / 60);
    $seconds = $rawDuration % 60;
    $songDuration = sprintf("%d:%02d", $minutes, $seconds);

    $topSongsAsHTML .= <<<HTML
    <div class="top-song-item">
        <img src="$albumCover" alt="Album cover for $albumName">
        <div class="song-details">
            <div class="song-title">{$songName}</div>
            <div class="song-album-info">
                <span>Album: {$albumName}</span> | 
                <span>Duration: {$songDuration}s</span>
            </div>
            <div class="song-note">Note: {$songNote}</div>
        </div>
    </div>
HTML;
}

$albums = [];

try {
    $albums = $db->executeQuery("SELECT * FROM album WHERE artist_id = ? ORDER BY release_date DESC", [$id]);
} catch (PDOException $ex) {
    echo "Error while executing query: " . $ex->getMessage();
    exit;
}

$albumsAsHTML = "";

foreach ($albums as $album) {
    $albumName = $album['name'];
    $albumCover = $album['cover'];
    $albumDate = substr($album['release_date'], 0, 10);
    $albumID = $album['id'];

    $albumsAsHTML .= <<<HTML
    <div class="album-item">  
    <img src="$albumCover" alt="Album cover for $albumName"> 
    <div>{$albumName}</div>
    <div>{$albumDate}</div>
    </div> 
HTML;
}

$cssLink = '<link rel="stylesheet" href="style.css">';

$html = <<<HTML
    <div class="container">
        <a href="index.php" class="back-link"> <- Back to home page</a>
        <div class="artist-detail">
    <img src="{$artist['cover']}" alt="Image de l'artiste {$artist['name']}">
    <div class="artist-name">{$artist['name']}</div>
    <div class="monthly-listeners">Monthly Listeners: {$monthlyListeners}</div>
    <div class="artist-biography">{$artist['biography']}</div>
    
    <div class="content-flex-wrapper"> 
        <div class="top-songs-container"> <h3>Top 5 Songs:</h3>
            {$topSongsAsHTML}
        </div>
        
        <div class="albums-section">
        <a href="album.php?id=$albumID">
            <h3>Albums : </h3>
            <div class="album-grid">
                {$albumsAsHTML}
                </a>
            </div>
        </div>
    </div>
    </div>
    </div>
HTML;

$page = new HTMLPage(title: "Lowify - $artistName");
$page->addContent($cssLink . $html);
echo $page->render();