# 2022システム開発 後期最終課題
1. Docker起動<br>
```
docker compose build
docker compose up
```
2. テーブルの作成<br>
```
CREATE TABLE `bbs_entries` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `body` TEXT NOT NULL,
    `image_filename` TEXT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);
```
```
CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` TEXT NOT NULL,
    `email` TEXT NOT NULL,
    `password` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
    `icon_filename` TEXT DEFAULT NULL;
);
```

3. <a href="http://54.82.89.25/timeline.php">http://54.82.89.25/timeline.php</a>

4. Login<br>
```
メールアドレス: test@test.com, test2@test.com, test3@test.com
パスワード: 123456 (同じ)
```
