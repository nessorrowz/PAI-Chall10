<?php
$articles = ['welcome.txt', 'tips.txt', 'secret.txt'];
$basePath = '/var/www/html/articles/';

echo "<h1>📰 Welcome to Article Search</h1>";

echo "<h3>Available Articles:</h3><ul>";
foreach ($articles as $article) {
    echo "<li><a href='?query=$article'>$article</a></li>";
}
echo "</ul>";

// Handle search
if (isset($_GET['query'])) {
    $query = $_GET['query'];

    if (str_contains($query, '..')) {
        die("Uhh nope");
    }

    // Redirect to article if it matches a known one
    if (in_array($query, $articles)) {
        $fullPath = $basePath . $query;
    } elseif (substr($query, 0, 1) === '/') {
        $fullPath = $query;
    } else {
        die("Only valid article names are allowed.");
    }

    echo "<h2>Result for: $query</h2>";
    if (file_exists($fullPath)) {
        echo "<pre>";
        echo htmlspecialchars(file_get_contents($fullPath));
        echo "</pre>";
    } else {
        echo "File not found.";
    }
}
?>
