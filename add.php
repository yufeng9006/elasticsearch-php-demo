<?php
require_once('app/init.php');

if(!empty($_POST)) {
    if(isset($_POST['title'], $_POST['body'], $_POST['keywords'])) {

        $title = $_POST['title'];
        $body = $_POST['body'];
        $keywords= explode(',', $_POST['keywords']);

        $indexed = $es->index([
            'index' => 'articles',
            'type' => 'article',
            'body' => [
                'title' => $title,
                'body' => $body,
                'keywords' => $keywords,
            ]
        ]);

        if($indexed) {
            print_r($indexed);
        }
    }
}

?>


<html>
<head>
    <meta charset="utf-8" />
    <title>add | ES</title>
    <link href="css/main.css" rel="stylesheet">
</head>
<body>
    <form method="post" action="add.php" autocomplete="off">
        <label>
            标题
            <input type="text" name="title" />
        </label>
        <label>
            内容
            <textarea name="body" rows="8"></textarea>
        </label>
        <label>
            关键字
            <input type="text" name="keywords" />
        </label>

        <input type="submit" value="添加" />
    </form>

</body>
</html>