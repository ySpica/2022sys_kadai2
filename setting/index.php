<?php
session_start();

if (empty($_SESSION['login_user_id'])) {
  header("HTTP/1.1 302 Found");
  header("Location: ./login.php");
  return;
}

// DBに接続
$dbh = new PDO('mysql:host=mysql;dbname=techc', 'root', '');
// セッションにあるログインIDから、ログインしている対象の会員情報を引く
$select_sth = $dbh->prepare("SELECT * FROM users WHERE id = :id");
$select_sth->execute([
    ':id' => $_SESSION['login_user_id'],
]);
$user = $select_sth->fetch();
?>

<style>
body {
  background-color: azure;
}
dt {
  font-size: 18px;
  text-decoration: underline;
}
dd {
  font-size: 14px;
}
.field {
  margin-top: 40px;
}
</style>

<a href="/timeline.php">タイムラインはこちら</a>

<h1>設定画面</h1>

<p>
  現在の設定
</p>
<dl> <!-- 登録情報を出力する際はXSS防止のため htmlspecialchars() を必ず使いましょう -->
  <dt>ID</dt>
  <dd><?= htmlspecialchars($user['id']) ?></dd>
  <dt>メールアドレス</dt>
  <dd><?= htmlspecialchars($user['email']) ?></dd>
  <dt>名前</dt>
  <dd><?= htmlspecialchars($user['name']) ?></dd>
</dl>

<ul class="field">
  <li><a href="./name.php">名前設定</a></li>
  <li><a href="./icon.php">アイコン設定</a></li>
  <li><a href="./introduction.php">自己紹介文設定</a></li>
  <li><a href="./cover.php">カバー設定</a></li>
  <li><a href="./dateofbirth.php">生年月日設定</a></li>
  <li><a href="../follow_list.php">フォロー一覧</a></li>
</ul>
