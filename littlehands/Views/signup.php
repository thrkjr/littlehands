<?php
require_once(ROOT_PATH .'/Views/common/head.php');
?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>ちょっとおてつだい | 新規登録</title>
        <link rel="stylesheet" type="text/css" href="/css/base.css">
    </head>

    <body>
        <header>
        <h1><a href="index.php"><img src="img/logo.png" alt="ロゴ" width="70" height="70"></a></h1>
        </header>
        <main>
    
            <form action="index.php" method="post">
            <input type="hidden" name="file_name" value="<?php print basename(__FILE__); ?>">
                <p><span class="required">*</span>は必須項目</p>
                <label class="error-message"><?=$errors['common'] ?? ''; ?></label>
                <dl>
                    <dt>
                        <label for="email">email</label><span class="required">*</span>
                    </dt>
                    <dd>
                        <input type="email" name="u1_email" value="<?=$user['u1_email'] ?? ''; ?>" placeholder="aaa@aaa.com">
                        <label class="error-message"><?=$errors['u1_email'] ?? ''; ?></label>
                    </dd>
                    <dt>
                        <label for="password">password</label><span class="required">*</span>
                    </dt>
                    <dd>
                        <input type="password" name="u1_password" value="">
                        <label class="error-message"><?=$errors['u1_password'] ?? ''; ?></label>
                    </dd>
                    <dd>
                        <button type="submit">新規登録</button>
                    </dd>
                </dl>
            </form>
            <p class="to_login"><a href="login.php">ログインはこちら</a></p>        
        </main>

        <?php require_once(ROOT_PATH .'/Views/common/footer.php'); ?>
    </body>

    </html>
    <?php require_once(ROOT_PATH .'/Views/common/foot.php'); ?>