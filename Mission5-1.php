<?php
//DB用接続設定
$dsn ='mysql:dbname=データーベース名';host=localhost';
$user = 'ユーザー名';
$password = 'パスワード';
// デバック機能も追加
$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_WARNING));



;?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ただの掲示板</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
	crossorigin="anonymous"></script>

</head>
<body>
	<div id="page">
		<header>
			<img src="https://pbs.twimg.com/profile_images/1185485639942164480/O7hVgtVN_400x400.jpg" alt="ロゴ">

		<h1>ただの掲示板</h1>	



<?php // セッション管理開始
session_start();?>

	<?php if(empty($_SESSION['name']) or empty($_SESSION['password']) ) :?>
				<ul>
		
				<li id="login">ログイン</li>
				<li id="newc">新規会員登録</li>
				</ul>
	<?php endif;?>
		</header>
		<main>
<div class="container">		
<?php if(empty($_SESSION['name']) and empty($_SESSION['password'])) :?>
<h2>会員登録やログインをすると書き込めます</h2>
<?php endif;?>


<?php
if(!empty($_SESSION['name']) and !empty($_SESSION['password'])){
	$name = $_SESSION['name'];
	$password=$_SESSION['password'];
};



?>

<?php //echo $name,$password;?>


<?php if(( htmlspecialchars(!empty($_POST["name"])) and htmlspecialchars(!empty($_POST["password"])) or (!empty($name) and !empty($password)))):?>
<?php if(( htmlspecialchars(!empty($_POST["name"])) and htmlspecialchars(!empty($_POST["password"])) and (empty($name) and empty($password)))):?>

<?php $name = htmlspecialchars($_POST["name"]);
$password = htmlspecialchars($_POST["password"]);?>
<?php endif;?>

<?php

$sql = 'SELECT distinct newname,newpassword from customer order by newname';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	//echo"<h2> 投稿者:".$row['newname']."</h2>";
	//echo $row['id'].',';
	//echo $row['time'].',';
	//echo $row['comment'].'<br>';
	//echo "<p>コメント".$row['newpassword']."</p>";
	if($name==$row['newname'] and $password == $row['newpassword']){
		$realname = $row['newname'];
		$realpassword = $row['newpassword'];

		
	}

	
}


;?>




<?php if($name == isset($realname)  and $password == isset($realpassword)):?>

<?php

$_SESSION['name'] = $name;
$_SESSION['password'] = $password;



?>

<h3>ようこそ、<?php echo "$name"?>様</h3>

<p>コメントを書く</p>

<form action="" method="post">
<input type="text" name="comment">	
<input type="submit" value="コメントする">
</form>
<p>コメントを削除する</p>


<?php//削除用フォーム?>
<form action="" method="post">


<select name=deletecoment>
<?php
//ログイン者は削除したい投稿を選べる

	$sql = 'SELECT * FROM tbtest WHERE name=:name ';
	$stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
	$stmt->bindParam(':name', $name, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
	$stmt->execute();                             // ←SQLを実行する。
	$results = $stmt->fetchAll(); 
		foreach ($results as $row):?>
	
			<option value="<?php echo $row['id']?>"><?php echo$row['comment'] ?></option>
		<?php endforeach;?>
		</select>

<input type="submit" value="削除する">
</form>
<?php if(!empty( $_POST["deletecoment"])):?>
<?php 

	//4-1で書いた「// DB接続設定」のコードの下に続けて記載する。
	$id = $_POST["deletecoment"];

	$sql = 'delete from tbtest where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();


	//続けて、4-6の SELECTで表示させる機能 も記述し、表示もさせる。
	//※ データベース接続は上記で行っている状態なので、その部分は不要
;?>

<?php endif;?>
<?php//編集用フォーム?>
<p>コメントを編集する</p>
<form action="" method="post">


<select name=editcomment>
<?php
//ログイン者は編集したい投稿を選べる

	$sql = 'SELECT * FROM tbtest WHERE name=:name ';
	$stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
	$stmt->bindParam(':name', $name, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
	$stmt->execute();                             // ←SQLを実行する。
	$results = $stmt->fetchAll(); 
		foreach ($results as $row):?>
	
			<option value="<?php echo $row['id']?>"><?php echo$row['comment'] ?></option>
		<?php endforeach;?>
		</select>
<p>書き換える言葉を入力してください</p>
<input type="text" name="editword">
<input type="submit" value="編集する">
</form>
<?php



;?>

<?php if(!empty( $_POST["editcomment"]) and !empty($_POST["editword"])):?>
<?php 

	//4-1で書いた「// DB接続設定」のコードの下に続けて記載する。
	$id = htmlspecialchars($_POST["editcomment"]);
	$comment = htmlspecialchars($_POST["editword"]);
	$sql = 'UPDATE tbtest SET name=:name,comment=:comment WHERE id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
;?>
<?php endif;?>
<?php
if(isset($_POST['logout'])){
	session_destroy();
}

;?>

<form action="" method="post">

	<button type="submit" name="logout">ログアウト</button>

</form>

	
<?php else:?>
	<div class="error">
	<h3>ログイン情報が間違っています</h3>
</div>
<?php endif;?>

<?php endif;?>


<div class="contains">

<?php





//テーブルの作製

$sql = "CREATE TABLE IF NOT EXISTS tbtest"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "time TEXT"
.");";
$stmt = $pdo->query($sql);

if (!empty($_POST["comment"])) {
    //レコードの挿入
    $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment,time) VALUES (:name, :comment,:time)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':time', $time, PDO::PARAM_STR);
    //$name = '（好きな名前）';
$comment=htmlspecialchars($_POST["comment"]) ;//好きな名前、好きな言葉は自分で決めること

$time = date("Y/m/d H:i:s");
    $sql -> execute();
}



$sql = 'SELECT * FROM tbtest order by id desc';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo "<div class='contain'>";
	echo"<h2> 投稿者:".$row['name']."</h2>";
	//echo $row['id'].',';
	//echo $row['time'].',';
	//echo $row['comment'].'<br>';
	echo "<p>コメント".$row['comment']."</p>";
	echo"<p class='time'>"."投稿時刻".$row['time']."</p>";
	echo "<hr>";
	echo "</div>";
}

?>

<?php if(!empty($_POST["newname"])and (!empty($_POST["newpassword"])and htmlspecialchars($_POST["newpassword"])===htmlspecialchars($_POST["repassword"]))):?>

<?php


//会員登録情報


//テーブルの作製





$sql = "CREATE TABLE IF NOT EXISTS customer"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "newname char(32),"
. "newpassword TEXT"
.");";
$stmt = $pdo->query($sql);

$newname = htmlspecialchars($_POST["newname"]);
$newpassword = htmlspecialchars($_POST["newpassword"]);



 //レコードの挿入
 $sql = $pdo -> prepare("INSERT INTO customer (newname, newpassword) VALUES (:newname, :newpassword)");
 $sql -> bindParam(':newname', $newname, PDO::PARAM_STR);
 $sql -> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
 $sql -> execute();

;?>


<?php endif;?>


</div>
  </div>


<div class="forms" id="login-dialog">
  
  <div class="forms-content">
	<h1>ログインフォーム</h1>
      <form name='form-login' action='' method='post'>
      <h2>名前</h2>
          <input type="text" id="user" placeholder="名前" name="name">
       <h2>パスワード</h2>

        <input type="password" id="pass" placeholder="パスワード" name="password"> <br>
        
				<input type="submit" class="forms-send" value="ログイン">
				
				<button type="button" class="forms-close">キャンセル</button>
			 </form>
	</div>

</div>

<div class="forms" id="newc-dialog">
  
  <div class="forms-content">
	<h1>新規会員登録</h1>
      <form name='form-login' action='' method='post'>
      <h2>名前</h2>
          <input type="text" id="user" placeholder="名前" name="newname">
       <h2>パスワード</h2>

          <input type="password" id="pass" placeholder="パスワード" name="newpassword"> <br>
          <input type="password" id="pass" placeholder="確認用パスワード" name ="repassword"> <br>
        
				<input type="submit" class="forms-send" value="ログイン">
				
				<button type="button" class="forms-close">キャンセル</button>
			 </form>
	</div>

</div>

	 </main>

		<footer>
			<p>&copy; イルカ</p>
		</footer>

<script src="script.js"></script>
</body>
</html>



























