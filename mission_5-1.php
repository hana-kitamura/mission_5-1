<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        <?php
        $dsn='データベース名';
        $user='ユーザー名';
        $password="パスワード";
        $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
       
       $sql="CREATE TABLE IF NOT EXISTS dbm51"."(
       id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
       name char(32) NOT NULL,
       comment text NOT NULL,
       day text NOT NULL,
       pass char(4)
       );";
       $stmt=$pdo->query($sql);
       
        //新規投稿
        if(isset($_POST["submit"]) && empty($_POST["fe_num"])){
           $sql=$pdo->prepare("INSERT INTO dbm51(name, comment, day, pass) VALUES(:name, :comment, :day, :pass)");
           $sql->bindParam(':name', $name, PDO::PARAM_STR);
           $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
           $sql->bindParam(':day', $day, PDO::PARAM_STR);
           $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
           $name=filter_input(INPUT_POST, "name");
           $comment=filter_input(INPUT_POST, "str");
           $day=date("Y/m/d H:i:s");
           $pass=filter_input(INPUT_POST, "pass");
           $sql->execute();
        }
        
       //投稿の削除処理
       if(!empty($_POST["d_num"]) || !empty($_POST["d_pass"])){
           $id=filter_input(INPUT_POST, "d_num");
           $d_pass=filter_input(INPUT_POST,"d_pass");
           $sql = 'SELECT * FROM dbm51 WHERE id=:id';
           $stmt = $pdo->prepare($sql);
           $stmt->bindParam(':id', $id, PDO::PARAM_INT);
           $stmt->execute();
           $results = $stmt->fetchAll();
           foreach ($results as $row){
               $pass=$row['pass'];
           }
           if($pass==$d_pass){
               $sql = 'delete from dbm51 where id=:id';
               $stmt = $pdo->prepare($sql);
               $stmt->bindParam(':id', $id, PDO::PARAM_INT);
               $stmt->execute();
           }
       }

       //編集番号を取得する処理
       if(!empty($_POST["e_num"]) || !empty($_POST["e_pass"])){
           $id=filter_input(INPUT_POST,"e_num");
           $e_pass=filter_input(INPUT_POST,"e_pass");
           $sql = 'SELECT * FROM dbm51 WHERE id=:id';
           $stmt = $pdo->prepare($sql);
           $stmt->bindParam(':id', $id, PDO::PARAM_INT);
           $stmt->execute();
           $results = $stmt->fetchAll();
           foreach ($results as $row){
               $pass=$row['pass'];
               if($pass==$e_pass){
                   $e_name=$row['name'];
                   $e_str=$row['comment'];
               }
           }
       }
       
       //投稿の編集処理
       if(!empty($_POST["fe_num"])){
           $id=filter_input(INPUT_POST, "fe_num");
           $name = filter_input(INPUT_POST, "name");
           $comment = filter_input(INPUT_POST, "str");
           $day=date("Y/m/d H:i:s");
           $sql = 'UPDATE dbm51 SET name=:name,comment=:comment,day=:day WHERE id=:id';
           $stmt = $pdo->prepare($sql);
           $stmt->bindParam(':name', $name, PDO::PARAM_STR);
           $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
           $stmt->bindParam(':day', $day, PDO::PARAM_STR);
           $stmt->bindParam(':id', $id, PDO::PARAM_INT);
           $stmt->execute();
       }
       ?>
        
        <form action="" method="post">
           〈投稿送信フォーム〉<br>
           <input type="text" name="name" placeholder="名前" value="<?php if(isset($_POST["edit"])){echo $e_name;}?>"><br>
           <input type="text" name="str" placeholder="コメント" value="<?php if(isset($_POST["edit"])){echo $e_str;}?>"><br>
           <input type="text" name="pass" placeholder="パスワード">
           <input type="submit" name="submit"><br>
           <input type="hidden" name="fe_num" value="<?php if(isset($_POST["edit"])){$e_num=filter_input(INPUT_POST, "e_num"); echo $e_num;}?>">
           <br>
           〈削除フォーム〉<br>
           <input type="number" name="d_num" placeholder="コメントの投稿番号"><br>
           <input type="text" name="d_pass" placeholder="パスワード">
           <input type="submit" name="delete" value="削除"><br>
           <br>
           〈編集フォーム〉<br>
           <input type="number" name="e_num" placeholder="コメントの投稿番号"><br>
           <input type="text" name="e_pass" placeholder="パスワード">
           <input type="submit" name="edit" value="編集">
           
           <br><hr><br>
           
           【投稿一覧】<br><br>
        <?php
        $sql = 'SELECT * FROM dbm51';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['day'].'<br>';
        echo "<hr>";
        }
        ?>
    
        </form>
    </body>
</html>