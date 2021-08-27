<?php
require_once(ROOT_PATH .'Views/common/session.php');
$s = session::getInstance();
$s->start();
$s->allClear();
?>
    <!DOCTYPE html>

    <html>

    <head>
        <meta charset="UTF-8">
        <title>ちょっとおてつだい | ログアウト</title>
        <link rel="stylesheet" type="text/css" href="/css/base.css">
    </head>

    <body>
        <header><h1><a href="index.php"><img src="img/logo.png" alt="ロゴ" width="70" height="70"></a></h1></header>
        <main>
        <p>ログアウトしました。</p>
        <p><a href="index.php">トップ画面へ戻る。</a></p>
        </main>
    <?php require_once(ROOT_PATH .'/Views/common/footer.php'); ?>
    </body>

    </html>