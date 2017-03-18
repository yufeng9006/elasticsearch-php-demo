<?php
require_once('app/init.php');

/**
 * @link http://127.0.0.1:4321/page.php?q=%E8%B1%86%E7%93%A3&f=2&s=5
 */

if( isset($_GET['q']) ) {
    $q = $_GET['q'];
    $from = isset($_GET['f']) ? intval($_GET['f']) : 0; // 开始条数
    $size = isset($_GET['s']) ? intval($_GET['s']) : 10; // 显示条数

    $queryArray['body']['query'] = [
        'bool' => [
            'should' => [
                'match' => ['title'=>$q],
                'match' => ['body'=>$q],
                'match' => ['keywords'=>$q]
            ]
        ]
    ];


    if($from>0) {
        $queryArray['body']['from'] = $from;
        $queryArray['body']['size'] = $size;
    }

    $queryArray['body']['highlight'] = array(
        'fields' => [
            'title' => (object)[],
            'body' => (object)[]
        ],
        'require_field_match' => false
    );
    $query = $es->search($queryArray);

//    echo "<pre>" , print_r($query) ,"</pre>";
//    die();

    if($query['hits']['total']>=1) {
        $results = $query['hits']['hits'];
        foreach($results as $k=>$r) {
            if(isset($r['highlight']['title'])){
                $results[$k]['_source']['title'] = $r['highlight']['title'][0];
            }
        }

    }
}

if( isset($_GET['p']) ) {
    $p = $_GET['p'];
    if($p==='true'){
        $id = isset($_GET['_id']) ? trim($_GET['_id']) : '';
        $title = isset($_GET['title']) ? trim($_GET['title']) : '';
        $updateArray = [
            'index' =>'articles',
            'type'=>'article',
            'id' => $id,
            'body'=>[
                'script' => 'ctx._source.title = "'.$title.'"',
//                'params' => [
//                    'keywords' => $keywords
//                ]
            ]
        ];
        $results = $es->update($updateArray);
        echo "<pre>" , print_r($results) ,"</pre>";
        die();
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
<form method="get" action="update.php" autocomplete="off">
    <label>
        输入需要查找的内容
        <input type="text" name="q" <?php if(isset($q)){echo "value='{$q}'";} ?>>
    </label>
    <input type="submit" value="搜索" />
</form>

<?php
if(isset($results)){
    echo '<label>共： '. $query['hits']['total'] .' 条数据</label>';
    foreach($results as $r) {

        ?>
        <div class="result">
            <form method="get" action="update.php" autocomplete="off">
                <a href="#<?php echo $r['_id'];?>"><input type="text" name="title" value="<?php echo $r['_source']['title']; ?>"></a>
                <div class="result-keywords"><?php echo $r['_source']['body']; ?></div>
                <div class="result-keywords"><?php echo implode(', ', $r['_source']['keywords']) ?></div>
                <input type="hidden" value="<?php echo $r['_id'] ?>" name="_id">
                <input type="hidden" value="true" name="p">
                <input type="submit" value="提交修改" />
            </form>
        </div>
        <br>

        <?php
    }
}
?>

</body>
</html>

