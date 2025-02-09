<?php
    // include '../includes/header.php';
    include '../helpers/common.php';
    //get query string value và tạo 1 url
    $url = get_current_domain() . "/pages/reviewbyqr.php?" . $_SERVER['QUERY_STRING'];
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agent</title>
    </head>
    <body>
        <h1>Agent</h1>
        <a href="<?php echo $url; ?>">>Open Device Browser</a>
</html>
