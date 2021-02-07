<?php

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

if(isset($_POST['name'])) {
    $name = $_POST['name'];
}
if(isset($_POST['comment'])){
    $comment = $_POST['comment'];
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
}
if (isset($_POST['check1'])) {
    $check1 = $_POST['check1'];
}
if (isset($_POST['check2'])) {
   $check2 = $_POST['check2']; 
}
if (isset($_POST['delete'])) {
    $delete = $_POST['delete'];
}
$date =date('Y-m-d H:i:s');

//名前、コメント、パスワードの３つが入力されている（新規or編集）
if(!empty($name) && !empty($comment) && !empty($password)){
    
        if(!empty($_POST['mark'])){
            //編集
            $id = $_POST['mark']; //変更する投稿番号
        	$sql = 'UPDATE post SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
        	$stmt = $pdo->prepare($sql);
        	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
        	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
        	$stmt->bindParam(':date', $date, PDO::PARAM_STR);
        	$stmt->bindParam(':password', $password, PDO::PARAM_STR);
        	$stmt->execute();
        }else{
            //新規投稿
            $sql = "INSERT INTO post (name,comment,date,password) VALUES ('$name','$comment','$date','$password')";
            $stmt = $pdo -> query($sql);
        }
    
}

//削除
if(!empty($_POST['delete'])){
    $delete = $_POST['delete'];
    $id = $delete;
    
    //削除したい投稿のパスワードの取得
    $sql = 'SELECT password FROM post WHERE id=:id ';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
    $stmt->execute();                             
    $results = $stmt->fetchAll(); 
    foreach($results as $row){
            $password = $row['password'];
    }
    
    if($password == $check1 ){
    $sql = 'delete from post where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
    }
}




//編集対象番号が入力されている→投稿フォームに表示させる
if(!empty($_POST['edit'])){
    $edit = $_POST['edit'];
    $id = $edit;
    
    //編集したい投稿のパスワードの取得
    $sql = 'SELECT password FROM post WHERE id=:id ';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
    $stmt->execute();                             
    $results = $stmt->fetchAll(); 
    foreach($results as $row){
            $password = $row['password'];
    }
	//パスワードが一致したら処理
	if($password == $check2 ){
        $sql = 'SELECT * FROM post WHERE id = :id ';
        $stmt = $pdo -> prepare($sql);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll();
        foreach($results as $row){
            $value0 = $row['id'];
            $value1 = $row['name'];
            $value2 = $row['comment'];
        }
	}
    
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset ="UTF=8">
    <title>mission</title>
</head>    
<body>
    
       
    

  <form action="" method="post">
        <input type="text" name="name" value="<?php if(isset($value1)){echo $value1;}?>" placeholder="名前"><br>
        <input type="text" name="comment" value="<?php if(isset($value2)){echo $value2;}?>" placeholder="コメント"><br>
        <input type="hidden" name=mark value="<?php if(isset($value0)){echo $value0;}?>">
        <input type="password" name="password" placeholder=パスワード>
        <input type="submit" value="送信"><br><br>
        <input type="text" name="delete" placeholder="削除対象番号"><br>
        <input type="password" name="check1" placeholder=パスワード(確認)>
        <input type="submit" value="削除"><br><br>
        <input type="text" name="edit" placeholder="編集対象番号"><br>
        <input type="password" name="check2" placeholder=パスワード(確認)>
        <input type="submit" value="編集"><br><br>
        
    </form>
                
</body>    
    
</html>

<?php
    //SELECT(投稿の取得)
	$sql = 'SELECT * FROM post';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date']."<br>";
	    echo "<hr>";
	}
?>