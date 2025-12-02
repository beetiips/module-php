<?php

require_once 'inc/page.inc.php';
require_once 'inc/database.inc.php';

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

$topTrending = [];

try {
    $topTrending = $db->executeQuery("SELECT id as artist_id, name as artist_name, monthly_listeners, cover as artist_cover FROM artist ORDER BY monthly_listeners DESC LIMIT 5");
} catch (PDOException $ex) {
    echo "Error while executing query: " . $ex->getMessage();
    exit;
}

$topTrendingAsHTML = "";

foreach ($topTrending as $trending) {
    $artistName = $trending['artist_name'];
    $artistID = $trending['artist_id'];
    $monthlyListeners = $trending['monthly_listeners'];
    $cover = $trending['artist_cover'];

    if ($monthlyListeners >= 1000000) {
        $monthlyListeners = $monthlyListeners / 1000000;
        $monthlyListeners = number_format($monthlyListeners, 1) . "M";
    } elseif ($monthlyListeners >= 1000) {
        $monthlyListeners = $monthlyListeners / 1000;
        $monthlyListeners = number_format($monthlyListeners, 1) . "K";
    }

    $topTrendingAsHTML .= <<<HTML
    <div class="top_trending_artist">
    <a href="artist.php?id=$artistID">
        <img src="$cover" alt="$artistName">
        <div>{$artistName}</div>
        <div>{$monthlyListeners}</div>
        </a>
    </div>
HTML;

}

$topNewAlbums = [];

try {
    $topNewAlbums = $db->executeQuery("SELECT id as album_id, name as album_name, artist_id as alb_artist_id, cover as album_cover, release_date FROM album ORDER BY release_date DESC LIMIT 5");
} catch (PDOException $ex) {
    echo "Error while executing query: " . $ex->getMessage();
    exit;
}

$topNewAlbumsAsHTML = "";

foreach ($topNewAlbums as $newAlbums) {
    $albumName = $newAlbums['album_name'];
    $artistID = $newAlbums['alb_artist_id'];
    $albumCover = $newAlbums['album_cover'];
    $albumReleaseDate = $newAlbums['release_date'];
    $albumID = $newAlbums['album_id'];

    $topNewAlbumsAsHTML .= <<<HTML
    <div class="top_new_albums">
    <a href="album.php?id=$albumID">
        <img src="$albumCover" alt="$albumName">
        <div>{$albumName}</div>
        <div>{$albumReleaseDate}</div>
        </a>
    </div>
HTML;

}

$topBestAlbums = [];

try {
    $topBestAlbums = $db->executeQuery("SELECT l.id as album_id, l.name as album_name, l.artist_id as alb_artist_id, l.cover as album_cover, AVG(s.note) as avg_note FROM album l INNER JOIN song s on l.id = s.album_id GROUP BY l.id, l.name, l.artist_id, l.cover ORDER BY avg_note DESC LIMIT 5");
} catch (PDOException $ex) {
    echo "Error while executing query: " . $ex->getMessage();
    exit;
}

$topBestAlbumsAsHTML = "";

foreach ($topBestAlbums as $bestAlbums) {
    $albumName = $bestAlbums['album_name'];
    $artistID = $bestAlbums['alb_artist_id'];
    $albumNote = number_format($bestAlbums['avg_note'], 1) . '/5';
    $albumCover = $bestAlbums['album_cover'];
    $albumID = $bestAlbums['album_id'];

    $topBestAlbumsAsHTML .= <<<HTML
    <div class="top_best_albums">
    <a href="album.php?id=$albumID">
        <img src="$albumCover" alt="$albumName">
        <div>{$albumName}</div>
        <div>{$albumNote}</div>
        </a>
    </div>
HTML;
}


$cssLink = '<link rel="stylesheet" href="style.css">';

$html = <<<HTML
    <div class="container">
        <div class="index"> <h1>Home page</h1> </div>
        <form> 
        </form>
        <div class="content-flex-wrapper">
            <h3>Top trending</h3>
            <a>{$topTrendingAsHTML}</a>
        </div>
        <div class="content-flex-wrapper">
            <h3>Top New Releases</h3>
            <a>{$topNewAlbumsAsHTML}</a>
        </div>
        <div class="content-flex-wrapper">
            <h3>Top albums</h3>
            <a>{$topBestAlbumsAsHTML}</a>
        </div>
    </div>
HTML;


$page = new HTMLPage(title: "Lowify - Home page");
$page->addContent($cssLink . $html);
echo $page->render();