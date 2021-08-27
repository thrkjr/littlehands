<?php
require_once(ROOT_PATH .'/Views/common/head.php');
$disp_type = '';

$hand = array();

$u1_id = $s->isExist('user') ? $s->get('user')['u1_id'] : '';
$h1_id = "";
$a1_id = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : '';
    if(isExistsKeyInArray('regist', $_POST)) {
        $disp_type = 'regist';
    }elseif(isExistsKeyInArray('edit', $_POST)) {
        $disp_type = 'edit';
    }elseif(isExistsKeyInArray('update', $_POST)) {
        $disp_type = 'update';
    }elseif(isExistsKeyInArray('view', $_POST)) {
        $disp_type = 'view';
    }elseif(isExistsKeyInArray('delete', $_POST)) {
        $disp_type = 'delete';
    }
}else{
    $disp_type = isset($_GET['disp_type']) ? $_GET['disp_type'] : '';
}

///////////////////////////■POST
if($_SERVER["REQUEST_METHOD"] == "POST") {

    if($disp_type == 'regist') {
        $results = $littlehand->checkInput();
        if($results->result) {
            $littlehand->initModels();
            $results = $littlehand->registHand();
            $hand = $results->rows[0];
            $h1_id = $hand['h1_id'];
            $a1_id = $hand['a1_id'];
            $disp_type = 'view';
        }else{
            $hand = $_POST;
            $errors = $results->errors;
            $h1_id = $_POST['h1_id'];
            $a1_id = $_POST['a1_id'];
            $disp_type = 'regist';
        }

    }elseif($disp_type == 'edit'){
        $littlehand->initModels();
        $results = $littlehand->selectHand($_POST['h1_id']);
        $hand = $results->rows[0];
        $h1_id = $hand['h1_id'];
        $a1_id = $hand['a1_id'];

    }elseif($disp_type == 'update'){
        $results = $littlehand->checkInput();
        if($results->result) {
            $littlehand->initModels();
            $results = $littlehand->updateHand();
            $hand = $results->rows[0];
            $h1_id = $hand['h1_id'];
            $a1_id = $hand['a1_id'];
            $disp_type = 'view';

        }else{
            $hand = $_POST;
            $errors = $results->errors;
            $h1_id = $_POST['h1_id'];
            $a1_id = $_POST['a1_id'];
            $disp_type = 'edit';
        }

    }elseif($disp_type == 'view'){
        $littlehand->initModels();
        
    }elseif($disp_type == 'delete') {
        $littlehand->initModels();
        $results = $littlehand->deleteHand();
        header( 'Location: hands.php?disp_type=list');
        exit();
    }
///////////////////////////■GET
}else{
    if($disp_type == 'view'){
        $littlehand->initModels();
        $results = $littlehand->selectHand($_GET['id']);
        $add1_pageView_result = $littlehand->add1_pageViewHand($_GET['id']);
        $hand = $results->rows[0];
        $h1_id = $hand['h1_id'];
        $a1_id = $hand['a1_id'];
    }
}


$myself_hand = false;
if(isset($user) && isset($user['u1_id']) && isset($hand) && isset($hand['u1_id'])) {
    if($user['u1_id'] == $hand['u1_id']) {
        $myself_hand = true;
    }
}
?>

    <!DOCTYPE html>

    <html>

    <head>
        <meta charset="UTF-8">
        <title>ちょっとおてつだい | おてつだい投稿</title>
        <link rel="stylesheet" type="text/css" href="/css/base.css">
        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script language="javascript" type="text/javascript">
            // 画面の各コントロールの値の変更時イベント

            // ログインユーザの情報を取得
            var user_info = <?php echo json_encode($s->get('user')); ?>;
        </script>
    </head>

    <body>
    <header>
    <h1><a href="index.php"><img src="img/logo.png" alt="ロゴ" width="70" height="70"></a></h1>
    <br>
        <a href="index.php">みんなのおてつだいを見る</a></li>
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
<?php if ($disp_type == 'list') : ?>
        <div id="search">
            <div id="hands_type-div">
                <ul>
                    <li><input type="radio" name="hand_type" value="0" checked>すべて</li>
                    <li><input type="radio" name="hand_type" value="1">てつだって</li>
                    <li><input type="radio" name="hand_type" value="2">てつだうよ</li>
                </ul>
            </div>
            <input type="text" id="freeword" name="freeword" placeholder="フリーワードを入力して検索">
            <input type="text" id="fee_lower" name="fee_lower" placeholder="報酬下限">～
            <input type="text" id="fee_upper" name="fee_upper" placeholder="報酬上限">
        </div>
        <div id="order_no-div">
            <select name="order_no" id="order_no" class="order_no">
                <option value="0" selected>新着</option>
                <option value="1">報酬</option>
                <option value="2">お気に入り数</option>
                <option value="3">閲覧数</option>
                <option value="4">登録日時</option>
            </select>
        </div>
        <div id="display-div">
            <i class="fas fa-list cursor-pointer" id="display_list" onclick="clickDisplay('display_list')"></i>
            <i class="fas fa-th-large cursor-pointer" id="display_block" onclick="clickDisplay('display_block')"></i>
            <i class="fas fa-map-marked-alt cursor-pointer" id="display_map" onclick="clickDisplay('display_map')"></i>
            <i class="fas fa-user cursor-pointer" id="display_user" onclick="clickDisplay('display_user')"></i>
        </div>
<?php endif; ?>
    </header>
    <main style="overflow: scroll;">


<?php if ($disp_type == 'list') : ?>

    <!--おてつだい数表示位置-->
    <div id ='handCount'></div>

    <!--テーブル生成位置-->
    <div id ='maintable'></div>
    
    <!--The div element for the map -->
    <div id="map"></div>

    <?php else : ?>

            <form action="<?php print basename(__FILE__); ?>" method="post">
            <input type="hidden" name="file_name" value="<?php print basename(__FILE__); ?>">
            <p><span class="required">*</span>は必須項目</p>
                <input type="hidden" name="u1_id" value="<?=$u1_id; ?>">
                <input type="hidden" name="h1_id" value="<?=$h1_id; ?>">
                <input type="hidden" name="a1_id" value="<?=$a1_id; ?>">
                <p id="error_common" class="error_message_font_style"><?=$errors['common'] ?? ''; ?></p>

                <?php $hhh = $hand ?>
                <?php $aaa = "" ?>

                <table class="registhand_table">                                                                                                                                
                    <tbody>
                        <tr>
                            <th>投稿内容</th>
                            <td>
                                <?php $key = 'h1_hand_type' ?>
                                <div id="hand_type">
                                    <ul>
                                        <?php if ($disp_type == 'view') : ?>
                                            <?php if (isExistsKeyInArray($key, $hand)) : ?>
                                                <?php if ($hand[$key] == 1): ?>
                                                    <li><input type="radio" name="<?=$key; ?>" value="1" checked>てつだって</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="2" disabled="disabled">てつだうよ</li>
                                                <?php else : ?>
                                                    <li><input type="radio" name="<?=$key; ?>" value="1" disabled="disabled">てつだって</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="2" checked>てつだうよ</li>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <?php if (isExistsKeyInArray($key, $hand)) : ?>
                                                <?php if ($hand[$key] == 1): ?>
                                                    <li><input type="radio" name="<?=$key; ?>" value="1" checked>てつだって</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="2">てつだうよ</li>
                                                <?php else : ?>
                                                    <li><input type="radio" name="<?=$key; ?>" value="1">てつだって</li>
                                                    <li><input type="radio" name="<?=$key; ?>" value="2" checked>てつだうよ</li>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <li><input type="radio" name="<?=$key; ?>" value="1" checked>てつだって</li>
                                                <li><input type="radio" name="<?=$key; ?>" value="2">てつだうよ</li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>タイトル<span class="required">*</span></th>
                            <td>
                                <?php $key = 'h1_title' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $hand) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $hand) ?? ''; ?>" placeholder="例) 草むしり手伝って">
                                    <label class="error-message"><?=$errors[$key] ?? ''; ?></label>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>報酬<span class="required">*</span></th>
                            <td>
                                <?php $key = 'h1_fee' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $hand) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $hand) ?? ''; ?>" placeholder="例) 10000">
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
                                <?php $zip_code_1 = h('a1_zip_code_1', $hand) ?? ''; ?>
                                <?php $zip_code_2 = h('a1_zip_code_2', $hand) ?? ''; ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?php if ($zip_code_1 != '' && $zip_code_2 != '') : ?>
                                        <?php print $zip_code_1.'-'.$zip_code_2; ?>
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
                                    <?=h($key, $hand) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $hand) ?? ''; ?>" placeholder="例) 東京都">
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>市区町村</th>
                            <td>
                                <?php $key = 'a1_city' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $hand) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $hand) ?? ''; ?>" placeholder="例) 港区">
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>その他住所</th>
                            <td>
                                <?php $key = 'a1_address1' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $hand) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $hand) ?? ''; ?>" placeholder="例) 青山">
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>番地</th>
                            <td>
                                <?php $key = 'a1_address2' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $hand) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $hand) ?? ''; ?>" placeholder="例) 1-1-1">
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>建物名</th>
                            <td>
                                <?php $key = 'a1_address3' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $hand) ?? ''; ?>
                                <?php else : ?>
                                    <input type="text" name="<?=$key; ?>" value="<?=h($key, $hand) ?? ''; ?>" placeholder="例) おてつだいビル101">
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>本文<span class="required">*</span></th>
                            <td>
                                <?php $key = 'h1_body' ?>
                                <?php if ($disp_type == 'view') : ?>
                                    <?=h($key, $hand) ?? ''; ?>
                                <?php else : ?>
                                    <!-- textarea内の改行ありのプレイスホルダー（chrome以外は未確認）：placeholder="本文&#13;&#10;はこちらに&#13;&#10;お書きください。" -->
                                    <textarea name="<?=$key; ?>" cols="30" rows="10" placeholder="例) 一緒に&#13;&#10;草むしりを&#13;&#10;手伝って下さい。"><?=h($key, $hand) ?? ''; ?></textarea>
                                    <label class="error-message"><?=$errors[$key] ?? ''; ?></label>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <!-- <tr>
                            <th>写真</th>
                            <td> -->
                                <?php $key = 'h1_photos_path' ?>
                                <input type="hidden" name="<?=$key; ?>" value="img/1">
                                <label class="error-message"><?=$errors[$key] ?? ''; ?></label>
                            <!-- </td>
                        </tr> -->
                    </tbody>
                </table>


                <br><br>
            <?php if ($authenticateStatus) : ?>
                    <?php if ($disp_type == 'regist') : ?>
                        <button type="submit" name="regist">登録</button>
                    <?php elseif ($disp_type == 'view') : ?>

                            <?php if ($myself_hand) : ?>
                                <button type="submit" name="edit">編集</button>
                                <button type="submit" name="delete">削除</button>
                            <?php else : ?>
                                <!-- http://localhost/exchanges.php?disp_type=view&user_type=host&id=1 -->
                                <!-- <button type="submit" name="delete">メッセージを送る</button> -->
                                <a href="exchanges.php?disp_type=view&user_type=guest&host_user_id=<?=$hand['u1_id']; ?>&hand_id=<?=$hand['h1_id']; ?>">メッセージを送る</a>
                            <?php endif; ?>

                    <?php elseif ($disp_type == 'edit') : ?>
                        <button type="submit" name="update">更新</button>
                    <?php endif; ?>
            <?php endif; ?>



            <br><br><a href="javascript:history.back();">前のページへ戻る</a>
            </form>


<?php endif; ?>

        </main>
        <?php require_once(ROOT_PATH .'/Views/common/footer.php'); ?>
    </body>

    </html>
    <?php require_once(ROOT_PATH .'/Views/common/foot.php'); ?>