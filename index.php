<?php
require_once('app/init.php');

if( isset($_GET['q']) ) {
    $q = $_GET['q'];

    $query = $es->search([
        'body' => [
            'query' => [
                'bool' => [
                    'should' => [
                        'match' => ['title'=>$q],
                        'match' => ['body'=>$q],
                        'match' => ['keywords'=>$q]
                    ]
                ]
            ]
        ]
    ]);

//    echo "<pre>" , print_r($query) ,"</pre>";
//
//    die();
    if($query['hits']['total']>=1) {
        $results = $query['hits']['hits'];

    }
}

?>


<html>
<head>
    <meta charset="utf-8" />
    <title>search | ES</title>
    <link href="css/main.css" rel="stylesheet">
</head>
<body>
    <form method="get" action="index.php" autocomplete="off">
        <label>
            输入需要查找的内容
            <input type="text" name="q">
        </label>
        <input type="submit" value="搜索" />
    </form>

    <?php
        if(isset($results)){
            foreach($results as $r) {

    ?>
                <div class="result">
                    <a href="#<?php echo $r['_id'];?>"><?php echo $r['_source']['title']; ?></a>
                    <div class="result-keywords"><?php echo implode(', ', $r['_source']['keywords']) ?></div>
                </div>

    <?php
            }
        }
    ?>

</body>
</html>

