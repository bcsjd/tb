<!DOCTYPE html>
          <html lang="ja"> 
          <head> 
          <meta charset="UTF-8"> 
          <title>m5-1.php</title> 
          </head> 
          <body>
          
          <?php 
          //データーベースへ接続
          $dsn = 'データーベース名';
          $user = 'ユーザー名';
          $password = 'パスワード';
          $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
          
          //テーブル作成
           $sql = "CREATE TABLE IF NOT EXISTS mission"
           ." ("
           . "id INT AUTO_INCREMENT PRIMARY KEY,"
           . "name char(32),"
           . "comment TEXT,"
           . "passdesu char(10),"
           . "date TEXT"
           .");";
           $stmt = $pdo->query($sql);
           
           
          //投稿機能と編集書き込み
          if(!empty($_POST["comment"])&& !empty($_POST["name"]) && !empty($_POST["pass"])){
           $name =$_POST['name'];
           $comment = $_POST['comment'];
           $date = date ("Y年m月d日　H時i分s秒");
           $pass = $_POST['pass'];
           if (empty($_POST['matter'])) {
               
               //投稿機能
               $sql = $pdo -> prepare("INSERT INTO mission (name, comment, passdesu, date) VALUES (:name, :comment, :passdesu, :date)");
               $sql -> bindParam(':name', $name, PDO::PARAM_STR);
               $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
               $sql -> bindParam(':passdesu', $pass, PDO::PARAM_STR);
               $sql -> bindParam(':date', $date, PDO::PARAM_STR);
               $sql -> execute();
           }
           else{
               
               //編集書き込み
               $matter = $_POST["matter"];
                   $id=$matter;
                   $name =$_POST['name'];
                   $comment = $_POST['comment'];
                   $date = date ("Y年m月d日　H時i分s秒");
                   $pass = $_POST['pass'];
                   $sql = 'UPDATE mission SET name=:name,comment=:comment,date=:date, passdesu=:passdesu WHERE id=:id';
                   $stmt = $pdo->prepare($sql);
                   $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                   $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                   $stmt->bindParam(':passdesu', $pass, PDO::PARAM_STR);
                   $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                   $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                   $stmt->execute();
               }
          }
       
           
           //削除機能
           if(!empty($_POST["dis"])&&!empty($_POST["pass1"])){
               $pass1 = $_POST['pass1'];
               $delete = $_POST["dis"]; 
               $id = $delete;
               $sql = 'SELECT * FROM mission WHERE id=:id';
               $stmt = $pdo->prepare($sql);
               $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
               $stmt->execute();
               $results = $stmt->fetchAll();
               foreach ($results as $row) {
                    if ($pass1 == $row['passdesu']){
                       $sql = 'DELETE FROM mission WHERE id=:id';
                       $stmt = $pdo->prepare($sql);
                       $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                       $stmt->execute();
                    }
               }
           }
        
        //編集選択
         if(!empty($_POST["edit"]) && !empty($_POST["pass2"])){
             $pass2 = $_POST["pass2"];
             $edi = $_POST["edit"];
             $id=$edi;
             $sql = 'SELECT * FROM mission WHERE id=:id';
             $stmt = $pdo->prepare($sql);
             $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
             $stmt->execute();
             $results = $stmt->fetchAll();
             foreach ($results as $row) {
                   if ($pass2 == $row['passdesu']){
                      $ediname=$row['name'];
                      $edicomment=$row['comment'];
                   }
             }
         }
         
         ?>
          
         
              <form action="m5-1.php" method="post">
                  <input type="text" name="name" value="<?php if (!empty($_POST["edit"]) &&!empty($_POST["pass2"])){
                  echo $ediname;}
                  else {echo "";
                  }
                  ?>" placeholder="名前">
                  <br>
                  <input type="text" name="comment" value="<?php if (!empty($_POST["edit"])&&!empty($_POST["pass2"])){
                  echo $edicomment;
                  }else {echo "";
                  }?>" placeholder="コメント">
                  <br>
                  <input type="hidden" name="matter" value="<?php if (!empty($_POST["edit"])){
                  echo $edi;
                  }else {echo "";
                  }?>">
                  <input type="text" name="pass" placeholder="パスワード">
                  <input type="submit" name="submit" value="送信">
                  <br>
                  <br>
                  <input type="text" name="dis" placeholder="削除対象番号">
                  <br>
                  <input type="text" name="pass1" placeholder="パスワード">
                  <input type="submit" name="submit" value="削除">
                  <br>
                  <br>
                  <input type="text" name="edit" placeholder="編集対象番号">
                  <br>
                  <input type="text" name="pass2" placeholder="パスワード">
                  <input type="submit" name="submit" value="編集">
                  </form>
                  
      
          <?php
                
                
           //表示
           $sql = 'SELECT * FROM mission';
           $stmt = $pdo->query($sql);
           $results = $stmt->fetchAll();
           foreach ($results as $row){
               //$rowの中にはテーブルのカラム名が入る
               echo $row['id'].' ';
               echo $row['name'].' ';
               echo $row['comment'].' ';
               echo $row['date'].'<br>';
               echo "<hr>";
            }
           ?>
           </body> 
           </html>