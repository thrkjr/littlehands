<?php
require_once(ROOT_PATH .'/Views/common/head.php');
$disp_type = '';


$u1_id = $s->isExist('user') ? $s->get('user')['u1_id'] : '';
$a1_id = $s->isExist('user') ? $s->get('user')['a1_id'] : '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : '';
    if(isExistsKeyInArray('edit', $_POST)) {
        $disp_type = 'edit';
    }elseif(isExistsKeyInArray('update', $_POST)) {
        $disp_type = 'update';
    }elseif(isExistsKeyInArray('view', $_POST)) {
        $disp_type = 'view';
    }
}else{
    $disp_type = isset($_GET['disp_type']) ? $_GET['disp_type'] : '';
}

///////////////////////////■POST
if($_SERVER["REQUEST_METHOD"] == "POST") {

    if($disp_type == 'update'){
        $results = $littlehand->checkInput();
        if($results->result) {
            $littlehand->initModels();
            $results = $littlehand->updateUser();
            $user = $results->rows[0];
            $s->set('user',$user);
            $a1_id = $user['a1_id'];
            $disp_type = 'view';
        }else{
            $user = $_POST;
            $errors = $results->errors;
            $a1_id = $_POST['a1_id'];
            $disp_type = 'edit';
        }
    }
}
?>

    <!DOCTYPE html>

    <html>

    <head>
        <meta charset="UTF-8">
        <title>ちょっとおてつだい | プロフィール</title>
        <link rel="stylesheet" type="text/css" href="/css/base.css">
        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
        <script language="javascript" type="text/javascript">
            // 画面の各コントロールの値の変更時イベント
            document.addEventListener('change', function(e) {
                if (e.target.name == 'zip_code') {
                    //http://zip2.cgis.biz/xml/zip.php?zn=1340088
                }
            })
            // ログインユーザの情報を取得
            var user_info = <?php echo json_encode($s->get('user')); ?>;
        </script>
    </head>

    <body>
    <header>
    <h1><a href="index.php"><img src="img/logo.png" alt="ロゴ" width="70" height="70"></a></h1>
    <nav>
            <ul>
                <?php if ($authenticateStatus) : ?>
                    <li><a href="hands.php?disp_type=list">おてつだい一覧</a></li>
                    <li><a href="hands.php?disp_type=regist">おてつだい投稿</a></li>
                    <li><a href="exchanges.php?disp_type=list&user_type=host">やりとり</a></li>
                    <li><a href="profile.php?disp_type=view"">プロフィール</a></li>
                    <li><a href="logout.php">ログアウト</a></li>
                <?php else : ?>
                    <li><a href="login.php">ログイン</a></li>
                    <li><a href="signup.php">新規登録</a></li>
                    <li><a href="login.php">おてつだい投稿</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main style="overflow: scroll;">



            <form action="<?php print basename(__FILE__); ?>" method="post">
            <p><span class="required">*</span>は必須項目</p>
                <input type="hidden" name="file_name" value="<?php print basename(__FILE__); ?>">
                <input type="hidden" name="u1_id" value="<?=$u1_id; ?>">
                <input type="hidden" name="h1_id" value="<?=$h1_id; ?>">
                <input type="hidden" name="a1_id" value="<?=$a1_id; ?>">
                <p id="error_common" class="error_message_font_style"><?=$errors['common'] ?? ''; ?></p>

                <?php $hhh = $hand ?>
                <?php $aaa = "" ?>

                <table class="registhand_table">                                                                                                                                
                    <tbody>
                        <tr>
                            <th>ニックネーム</th>
                            <td>
                                <?php $key = 'u1_nickname' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $user) ?? ''; ?>" placeholder="例) おてつだいタロウ">
                                    <label class="error-message"><?=$errors[$key] ?? ''; ?></label>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>名前（姓）</th>
                            <td>
                                <?php $key = 'u1_firstname' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $user) ?? ''; ?>" placeholder="例) 山田">
                                    <label class="error-message"><?=$errors[$key] ?? ''; ?></label>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>名前（名）</th>
                            <td>
                                <?php $key = 'u1_lastname' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $user) ?? ''; ?>" placeholder="例) 太郎">
                                    <label class="error-message"><?=$errors[$key] ?? ''; ?></label>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>フリガナ（姓）</th>
                            <td>
                                <?php $key = 'u1_firstname_kana' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $user) ?? ''; ?>" placeholder="例) ヤマダ">
                                    <label class="error-message"><?=$errors[$key] ?? ''; ?></label>
                                <?php endif; ?>
                            </td>
                        </tr>                        
                        <tr>
                            <th>フリガナ（名）</th>
                            <td>
                                <?php $key = 'u1_lastname_kana' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $user) ?? ''; ?>" placeholder="例) タロウ">
                                    <label class="error-message"><?=$errors[$key] ?? ''; ?></label>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>性別</th>
                            <td>
                                <?php $key = 'u1_gender' ?>
                                <ul>
                                        <?php if ($disp_type == 'view') : ?>
                                            <?php if (isExistsKeyInArray($key, $user)) : ?>
                                                <?php if ($user[$key] == 0): ?>
                                                    <li><input type="radio" name="<?=$key; ?>" value="0" checked>指定なし</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="1" disabled="disabled">男</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="2" disabled="disabled">女</li>
                                                <?php elseif($user[$key] == 1) : ?>
                                                    <li><input type="radio" name="<?=$key; ?>" value="0" disabled="disabled">指定なし</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="1" checked>男</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="2" disabled="disabled">女</li>
                                                <?php elseif($user[$key] == 2) : ?>
                                                    <li><input type="radio" name="<?=$key; ?>" value="0" disabled="disabled">指定なし</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="1" disabled="disabled">男</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="2" checked>女</li>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <?php if (isExistsKeyInArray($key, $user)) : ?>
                                                <?php if ($user[$key] == 0): ?>
                                                    <li><input type="radio" name="<?=$key; ?>" value="0" checked>指定なし</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="1">男</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="2">女</li>
                                                <?php elseif($user[$key] == 1) : ?>
                                                    <li><input type="radio" name="<?=$key; ?>" value="0">指定なし</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="1" checked>男</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="2">女</li>
                                                <?php elseif($user[$key] == 2) : ?>
                                                    <li><input type="radio" name="<?=$key; ?>" value="0">指定なし</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="1">男</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="2" checked>女</li>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <li><input type="radio" name="<?=$key; ?>" value="0" checked>指定なし</li>
                                                <li><input type="radio" name="<?=$key; ?>" value="1">男</li>
                                                <li><input type="radio" name="<?=$key; ?>" value="2">女</li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>誕生日</th>
                            <td>
                                <?php $key = 'u1_birth' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $user) ?? ''; ?>" placeholder="例) 1990-01-01">
                                    <label class="error-message"><?=$errors[$key] ?? ''; ?></label>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>電話番号</th>
                            <td>
                                <?php $key = 'u1_tel' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $user) ?? ''; ?>" placeholder="例) 09012345678">
                                    <label class="error-message"><?=$errors[$key] ?? ''; ?></label>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>自己紹介</th>
                            <td>
                                <?php $key = 'u1_self_introduction' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <!-- textarea内の改行ありのプレイスホルダー（chrome以外は未確認）：placeholder="本文&#13;&#10;はこちらに&#13;&#10;お書きください。" -->
                                    <textarea name="<?=$key; ?>" cols="30" rows="10" placeholder="例) はじめまして。&#13;&#10;宜しくお願いします。"><?=h($key, $user) ?? ''; ?></textarea>
                                    <label class="error-message"><?=$errors[$key] ?? ''; ?></label>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!--【　ajaxzip3 : 世界一、簡単に設置できる郵便番号検索ライブラリを目指して！　】
                             郵便番号から住所を自動入力するプラグインは
                             https://github.com/ajaxzip3/ajaxzip3.github.io
                             https://ajaxzip3.github.io/sample-page/
                             オープンソースのMITライセンス。商用、非商用を問わず無償にてご利用いただけるため、2008年の公開以来、様々なプロダクトに採用されています。 -->
                        <tr>
                            <th>郵便番号</th>
                            <td>
                                <?php $zip_code_1 = h('a1_zip_code_1', $user) ?? ''; ?>
                                <?php $zip_code_2 = h('a1_zip_code_2', $user) ?? ''; ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?php if ($zip_code_1 != '' || $zip_code_2 != '') : ?>
                                        <?php print $zip_code_1.' - '.$zip_code_2; ?>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <input type="text" name="a1_zip_code_1" value="<?=$zip_code_1; ?>" placeholder="例) 123" size="3" maxlength="3" onkeyup="AjaxZip3.zip2addr('a1_zip_code_1','a1_zip_code_2','a1_state','a1_city','a1_address1');"> -
                                    <input type="text" name="a1_zip_code_2" value="<?=$zip_code_2; ?>" placeholder="例) 4567" size="4" maxlength="4" onkeyup="AjaxZip3.zip2addr('a1_zip_code_1','a1_zip_code_2','a1_state','a1_city','a1_address1');">
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>都道府県</th>
                            <td>
                                <?php $key = 'a1_state' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $user) ?? ''; ?>" placeholder="例) 東京都">
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>市区町村</th>
                            <td>
                                <?php $key = 'a1_city' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $user) ?? ''; ?>" placeholder="例) 港区">
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>その他住所</th>
                            <td>
                                <?php $key = 'a1_address1' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $user) ?? ''; ?>" placeholder="例) 青山">
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>番地</th>
                            <td>
                                <?php $key = 'a1_address2' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $user) ?? ''; ?>" placeholder="例) 1-1-1">
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>建物名</th>
                            <td>
                                <?php $key = 'a1_address3' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $user) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $user) ?? ''; ?>" placeholder="例) おてつだいビル101">
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>


                    <?php if ($disp_type == 'view') : ?>
                        <button type="submit" name="edit">編集</button>
                    <?php elseif ($disp_type == 'edit') : ?>
                        <button type="submit" name="update">更新</button>
                    <?php endif; ?>
                





            </form>


        </main>
        <?php require_once(ROOT_PATH .'/Views/common/footer.php'); ?>
    </body>

    </html>
    <?php require_once(ROOT_PATH .'/Views/common/foot.php'); ?>