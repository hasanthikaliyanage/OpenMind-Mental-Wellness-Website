<?php
require 'db.php';

$result = $collection->insertOne([
  'name' => 'Test User',
  'email' => 'test@example.com'
]);

echo "Inserted with ID: " . $result->getInsertedId();
