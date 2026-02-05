<?php
session_start();
require 'vendor/autoload.php';

$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->OMP;
$dreamsCollection = $db->dreamAnalyzer;

// Form values
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$created_at = date("Y-m-d H:i:s");

// Simple dream keyword analyzer
function analyzeDream($desc) {
    $desc = strtolower($desc);
    if (strpos($desc, 'water') !== false) {
        return "Dreaming of water often symbolizes emotions, calmness or deep thoughts.";
    } elseif (strpos($desc, 'flying') !== false) {
        return "Flying dreams usually mean freedom, confidence or escaping limitations.";
    } elseif (strpos($desc, 'snake') !== false) {
        return "Snakes in dreams may represent hidden fears, transformation, or rebirth.";
    } elseif (strpos($desc, 'death') !== false) {
        return "Death dreams rarely mean actual death. They often represent change, endings, or new beginnings.";
    } else {
        return "Your dream reflects your subconscious. Focus on how it made you feel for deeper meaning.";
    }
}

$analysis = analyzeDream($description);

// Save dream with analysis
$dreamsCollection->insertOne([
    'title' => $title,
    'description' => $description,
    'analysis' => $analysis,
    'created_at' => $created_at
]);

header("Location: dreamAnalyzer.php?success=1");
exit;
?>
