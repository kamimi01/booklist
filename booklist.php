<?php
  // mysqlサーバ接続に必要な値を変数に代入
  $username = "test";
  $password = "";

  // PDOのインスタンスを精製して、Mysqlサーバに接続
  $database = new PDO("mysql:host=localhost;dbname=booklist;charset=UTF8;", $username, $password);

  // フォームから書籍タイトルが送信されていればデータベースに保存する
  if (array_key_exists("book_title", $_POST)) {
    // 実行するSQLを作成
    $sql = "INSERT INTO books (book_title) VALUES(:book_title)";
    // ユーザー入力に依存するSQLを実行するので、セキュリティ対策をする
    $statement = $database->prepare($sql);
    // ユーザー入力データ
    $statement->bindParam(":book_title", $_POST["book_title"]);
    // SQLを実行する
    $statement->execute();
    // ステートメントを破棄する
    $statement = null;
  }

  // 実行するSQLを作成
  $sql = 'SELECT * FROM books ORDER BY created_at DESC';
  // SQLを実行する
  $statement = $database->query($sql);
  // 結果レコード（ステートメントオブジェクト）を配列に格納する
  $records = $statement->fetchAll();

  // ステートメントを破棄する
  $statement = null;
  // Mysqlを使った処理が終わると、接続は不要なので切断する
  $databse = null;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>Booklist</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  </head>
  <body class="mt-4">

  <?php 
    // フォームデータ送受信確認用コード（本番では削除）
    // print "<div style='background-color: skyblue;'>";
    // print "<p>動作確認用:</p>";
    // print_r($_POST);
    // print "</div>"; 
  ?>

    <div class="container">
      <h1><a href="booklist.php">Booklist</a></h1>
      
      <h2>書籍の登録フォーム</h2>
      <form action="booklist.php" method="POST" class="form-inline mb-2">
        <div class="form-group mr-2">
          <input type="text" name="book_title" class="form-control" placeholder="書籍タイトルを入力" required> 
        </div>
        <button type="submit" name="submit_add_book" class="btn btn-primary">登録</button>
      </form>

      <hr>

      <h2>登録された書籍一覧</h2>
      <ul>
        <li>初めてのWebアプリケーション</li>
        <li>簡単!phpプログラミング</li>
        <li>PHP+MYSQLで作るWebアプリケーション</li>
        <?php // 登録された書籍タイトルの数だけループ開始
          if ($records) {
            // $recordsのレコードを一つずつ取り出して、$recordに代入する
            foreach ($records as $record) {
              // $recordからbook_titleのカラムを取得して$book_titleに代入する
              $book_title = $record["book_title"];
        ?>
          <!-- li要素内に$book_titleを出力する -->
          <li><?php print htmlspecialchars($book_title, ENT_QUOTES, "UTF-8"); ?></li>
        <?php 
            }
          }
        ?>
      </ul>
    </div>

    <!-- BootstrapなどのJavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.2.0/js/all.js" integrity="sha384-4oV5EgaV02iISL2ban6c/RmotsABqE4yZxZLcYMAdG7FAPsyHYAPpywE9PJo+Khy" crossorigin="anonymous"></script>
  </body>
</html>