<?php
    $version = get_version();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'My Community'; ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo $version; ?>">
</head>
<body>