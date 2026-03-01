<?php
$conn->query("
    INSERT INTO views (movie_id)
    VALUES ($movie_id)
");
