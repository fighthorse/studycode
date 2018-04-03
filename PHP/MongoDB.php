<?php

//
  header("Content-type: text/html; charset=utf-8");
 echo "<pre>";
//连接 mysql
$data = array();
$data1 = array();
try {
    $dbh = new PDO('mysql:host=localhost;dbname=test', "root", "root");
    $sql1 = 'SELECT * from create_key order by id desc  limit 1' ;

    $rs = $dbh->query($sql1);
    $rs->setFetchMode(PDO::FETCH_ASSOC);
    $data1 = $rs->fetchAll();

    $start = $data1[0]['val'];
    $start++;
    $sql2 = "insert into create_key(name,val) values('".$start."','".$start."')" ;
    $dbh->exec($sql2);

    $startf = $start*10000;
    $sql = 'SELECT * from address limit '.$startf.',10000' ;
    $rse = $dbh->query($sql);
    $rse->setFetchMode(PDO::FETCH_ASSOC);
    $data = $rse->fetchAll();

    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

//连接mongodb
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");  

if(!empty($data)){
    // 插入数据
    $bulk = new MongoDB\Driver\BulkWrite;

    foreach($data as $val){
        $bulk->insert($val);
    }
    $manager->executeBulkWrite('test.address', $bulk);
}

//查找
echo "查找数据<br/>";
$filter = ['addressID' => '245810'];
$options = [];
$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('test.address', $query);
foreach ($cursor as $document) {
    print_r($document);
}

//修改
echo "修改数据<br/>";
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update(
    ['addressID' => '245810' ],
    ['$set' => ['companyName' => '菜鸟工具'.$start, 'name' => 'toolrunoob'.$start]],
    ['multi' => false, 'upsert' => false]
);
$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
$result = $manager->executeBulkWrite('test.address', $bulk, $writeConcern);
print_r($result);

//查找
echo "查找数据<br/>";
$filter = ['addressID' => '245810'];
$options = [];
$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('test.address', $query);
foreach ($cursor as $document) {
    print_r($document);
}

$find = "张江路";
echo $find;
echo "匹配数据<br/>".$find."<br/>";

/*    //选择数据库
    $db = $manager->selectDB('test')->selectCollection("user");
    //查询所有数据，按age字段升序排序
    $cursor = $db->find('detail' => new MongoRegex("/".$find."/"))->skip(0)->limit(30);;

    $array = array();
    while($cursor->hasNext()) {
        $array[] = $cursor->getNext();
    }
    echo "<pre>";
    print_r($array);*/
//[new \MongoDB\BSON\Regex($find,'i')] { detail: { $regex: /杨高中路/, $options: "si" } } 
//{ name: { $regex: /acme.*corp/, $options: "si" } }   "/".$find."/"

$filter = ['detail' => ['$regex' => $find]];

//$filter = ['addressID' => ['$lt' => '250000']];
$options = [
    'sort' => ['addressID' => -1],
    'limit'=>10
];
// 查询数据
$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('test.address', $query);

foreach ($cursor as $document) {
    print_r($document);
}


?>