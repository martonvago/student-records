<?php
    # Due to the small scale of the project all dependencies are imported here
    require_once 'includes/functions.php';
    require_once 'includes/statistics.php';
    require_once 'includes/builders.php';
    require_once 'includes/parse_directory.php';
    require_once 'includes/parse_file.php';
    require_once 'includes/parse_field.php';
    require_once 'includes/validator_functions.php';
    require_once 'includes/display_helpers.php';
    require_once 'includes/constants.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="styles/index.css">
    <title>WP TMA</title>
</head>
<body>
    <h1>WEB PROGRAMMING USING PHP TMA 2020</h1>
    <?php
        $files = parse_files_in_folder('data');
        foreach ($files as $file) {
            echo display_file($file);
        }
    ?>
</body>
</html>
