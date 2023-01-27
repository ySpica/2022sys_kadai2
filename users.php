<?php
// DBに接続
$dbh = new PDO('mysql:host=mysql;dbname=techc', 'root', '');
// 会員データを取得
$sql = 'SELECT * FROM users';
$where_sql_array = [];
$prepare_params = [];

if (!empty($_GET['name'])) {
  $where_sql_array[] = ' name LIKE :name';
  $prepare_params[':name'] = '%' . $_GET['name'] . '%';
}
if (!empty($_GET['year_from'])) {
  $where_sql_array[] = ' birthday >= :year_from';
  $prepare_params[':year_from'] = $_GET['year_from'] . '-01-01'; // 入力年の1月1日
}
if (!empty($_GET['year_until'])) {
  $where_sql_array[] = ' birthday <= :year_until';
  $prepare_params[':year_until'] = $_GET['year_until'] . '-12-31'; // 入力年の12月31日
}

if (!empty($where_sql_array)) {
  $sql .= ' WHERE ' . implode(' AND', $where_sql_array);
}

$sql .= ' ORDER BY id DESC';

$select_sth = $dbh->prepare($sql);
$select_sth->execute($prepare_params);

session_start();
?>

<body>
  <h1>会員一覧</h1>

  <div style="margin-bottom: 1em;">
    <a href="/setting/index.php">設定画面</a>
    /
    <a href="/timeline.php">タイムライン</a>
  </div>

  <div style="margin-bottom: 1em;">
    絞り込み<br>
    <form method="GET">
      名前: <input type="text" name="name" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>"><br>
      生まれ年:
      <input type="number" name="year_from" value="<?= htmlspecialchars($_GET['year_from'] ?? '') ?>">年
      ~
      <input type="number" name="year_until" value="<?= htmlspecialchars($_GET['year_until'] ?? '') ?>">年
      <br>
      <button type="submit">決定</button>
    </form>
  </div>

  <?php foreach($select_sth as $user): ?>
    <?php
      // フォロー状態を取得
      $relationship = null;
      if (!empty($_SESSION['login_user_id'])) { // ログインしている場合
      // フォロー状態をDBから取得
        $select_sth = $dbh->prepare(
          "SELECT * FROM user_relationships"
        . " WHERE follower_user_id = :follower_user_id AND followee_user_id = :followee_user_id"
        );
      $select_sth->execute([
        ':followee_user_id' => $user['id'], // フォローされる側は閲覧しようとしているプロフィールの会員
        ':follower_user_id' => $_SESSION['login_user_id'], // フォローする側はログインしている会員
        ]);
       $relationship = $select_sth->fetch();
      }
      // フォローされている状態を取得
      $follower_relationship = null;
      if (!empty($_SESSION['login_user_id'])) { // ログインしている場合
       // フォローされている状態をDBから取得
        $select_sth = $dbh->prepare(
          "SELECT * FROM user_relationships"
        . " WHERE follower_user_id = :follower_user_id AND followee_user_id = :followee_user_id"
        );
        $select_sth->execute([
          ':follower_user_id' => $user['id'], // フォローしている側は閲覧しようとしているプロフィールの会員
          ':followee_user_id' => $_SESSION['login_user_id'], // フォローされる側はログインしている会員
        ]);
        $follower_relationship = $select_sth->fetch();
      }

    ?>
    <div style="display: flex; justify-content: start; align-items: center; padding: 1em 2em;">
      <?php if(empty($user['icon_filename'])): ?>
        <!-- アイコン無い場合は同じ大きさの空白を表示して揃えておく -->
        <div style="height: 2em; width: 2em;"></div>
      <?php else: ?>
        <img src="/image/<?= $user['icon_filename'] ?>"
          style="height: 2em; width: 2em; border-radius: 50%; object-fit: cover;">
      <?php endif; ?>
      <a href="/profile.php?user_id=<?= $user['id'] ?>" style="margin-left: 1em; margin-right: 1em;">
        <?= htmlspecialchars($user['name']) ?>
      </a>
      <?php if($user['id'] === $_SESSION['login_user_id']): // 自分自身の場合 ?>
      <div style="margin: 1em 0;">
        これはあなたです！<br>
      <a href="/setting/index.php">設定画面はこちら</a>
      </div>
      <?php else: // 他人の場合 ?>
      <div style="margin: 1em 0;">
      <?php if(empty($relationship)): // フォローしていない場合 ?>
      <div>
        <a href="./follow.php?followee_user_id=<?= $user['id'] ?>">フォローする</a>
      </div>
      <?php else: // フォローしている場合 ?>
      <div>
        <?= $relationship['created_at'] ?> にフォローしました。
      </div>
      <?php endif; ?>
      <?php if(!empty($follower_relationship)): // フォローされている場合 ?>
      <div>
        フォローされています。
      </div>
      <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <hr style="border: none; border-bottom: 1px solid gray;">
  <?php endforeach; ?>
</body>
