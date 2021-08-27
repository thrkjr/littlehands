<?php
require_once(ROOT_PATH .'/Views/common/head.php');
$disp_type = 'list';

print "<pre>";
print "■post■";print "<br />";
print_r($_POST);
print "<br />";print "<br />";
print "■get■";print "<br />";
print_r($_GET);
print "</pre>";

$littlehand->initModels();
$user_type = '';
$exchanges = null;

$e1_hand_id = '';
$e1_host_user_id = '';
$e1_guest_user_id = '';
$e1_body_host_id = '';

$disp_type = isset($_GET['disp_type']) ? $_GET['disp_type'] : '';
$user_type = isset($_GET['user_type']) ? $_GET['user_type'] : '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : '';
    if(isExistsKeyInArray('edit', $_POST)) {
        $disp_type = 'edit';
    }elseif(isExistsKeyInArray('update', $_POST)) {
        $disp_type = 'update';
    }elseif(isExistsKeyInArray('view', $_POST)) {
        $disp_type = 'view';
    }elseif(isExistsKeyInArray('list', $_POST)) {
        $disp_type = 'list';
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $exchanges = $littlehand->checkInput();
    if(!$exchanges->result) {
        $errors = $exchanges->errors;
    }else{
        $exchanges = $littlehand->insertExchange($user_type);
    }

//     array(6)
// e1_body:"dddd"
// e1_body_host_id:"1"
// e1_guest_user_id:"1"
// e1_host_user_id:"2"
// e1_hand_id:"36"

$id = '';
        $minID = $littlehand->selectExchangesMinID
        ($_POST['e1_hand_id'], $_POST['e1_host_user_id'], $_POST['e1_guest_user_id']);
        if(isset($minID) && isset($minID[0]['MIN_e1_id'])) {
            $id = $minID[0]['MIN_e1_id'];
        }

    
    $exchanges = $littlehand->selectExchanges($id);

    if(count($exchanges) != 0) {
        $e1_hand_id = $exchanges[0]['e1_hand_id'];
        $e1_host_user_id = $exchanges[0]['e1_host_user_id'];
        $e1_guest_user_id = $exchanges[0]['e1_guest_user_id'];
        $e1_body_host_id = $exchanges[0]['e1_host_user_id'];
    }

    
}else{
    if($disp_type == 'list') {
        if($user_type == 'host') {
            $exchanges = $littlehand->selectExchangesWithHands($user['u1_id'], null);
        }else{
            $exchanges = $littlehand->selectExchangesWithHands(null, $user['u1_id']);
        }
    }elseif($disp_type == 'view') {

        $id = '';

        if(isset($_GET['host_user_id']) && isset($_GET['hand_id'])){
            $minID = $littlehand->selectExchangesMinID
            ($_GET['hand_id'], $_GET['host_user_id'], $user['u1_id']);
            if(isset($minID) && isset($minID[0]['MIN_e1_id'])) {
                $id = $minID[0]['MIN_e1_id'];
            }
        }else{
            $id = $_GET['e1_id'];
        }

//http://localhost/exchanges.php?disp_type=view&user_type=guest&e1_id=11
//http://localhost/exchanges.php?disp_type=view&user_type=guest&host_user_id=2&hand_id=26

       
        $exchanges = $littlehand->selectExchanges($id);

        if(count($exchanges) != 0) {
            $e1_hand_id = $exchanges[0]['e1_hand_id'];
            $e1_host_user_id = $exchanges[0]['e1_host_user_id'];
            $e1_guest_user_id = $exchanges[0]['e1_guest_user_id'];
            $e1_body_host_id = $exchanges[0]['e1_host_user_id'];
        }else{
            // $user['u1_id'] げすと
            // $_GET['u1_id'] ほすと
            // $_GET['h1_id'] handID
            $e1_hand_id = $_GET['hand_id'];
            $e1_host_user_id = $_GET['host_user_id'];
            $e1_guest_user_id = $user['u1_id'];
            $e1_body_host_id = $user['u1_id'];
        }
    }
}
print "e1_hand_id";
print $e1_hand_id;print "<br />";print "<br />";

print "e1_host_user_id";
print $e1_host_user_id;print "<br />";print "<br />";

print "e1_guest_user_id";
print $e1_guest_user_id;print "<br />";print "<br />";

print "e1_body_host_id";
print $e1_body_host_id;print "<br />";print "<br />";


?>

    <!DOCTYPE html>

    <html>

    <head>
        <meta charset="UTF-8">
        <title>ちょっとおてつだい | やりとり</title>
        <link rel="stylesheet" type="text/css" href="/css/base.css">
        <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>
        <script language="javascript" type="text/javascript">
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
    </header>
    <main style="overflow: scroll;">

<?php if($disp_type == 'list') : ?>
    <table class="viewhand-table">
                    <?php if($user_type == 'host') : ?>
                        <tr><td colspan="6" style="text-align: left;">自分のおてつだいに対してのやりとり一覧</td></tr>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align: left;">他の人のおてつだいに対してのやりとり一覧</td></tr>
                    <?php endif; ?>
                <tr>
                    <th>No</th>
                    <th>e_id</th>
                    <th>おてつだい情報ホスト</th>
                    <th>おてつだい情報ゲスト</th>
                    <th>タイトル</th>
                    <th>表示</th>
                </tr>
                <?php if(!empty($exchanges) && count($exchanges) > 0): ?>
                    <?php $rowCount = 0; ?>
                    <?php foreach($exchanges as $value): ?>
                        <?php $rowCount++; ?>
                            <tr>
                                <td><?=$rowCount ?></td>
                                <td><?=h('e1_id', $value) ?></td>
                                <td><?=h('u1_nickname', $value) ?></td>
                                <td><?=h('u2_nickname', $value) ?></td>
                                <td><?=h('h1_title', $value) ?></td>
                                <td><a href="exchanges.php?disp_type=view&user_type=<?= $user_type ?>&e1_id=<?= h('e1_id', $value) ?>">表示</a></td>
                            </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align: center;">やりとり履歴がありません。</td></tr>
                <?php endif; ?>
            </table>
            <br>
                    <?php if($user_type == 'host') : ?>
                        <a href="exchanges.php?disp_type=list&user_type=guest">他の人のおてつだいに対してのやりとり一覧</a>
                    <?php else: ?>
                        <a href="exchanges.php?disp_type=list&user_type=host">自分のおてつだいに対してのやりとり一覧</a>
                    <?php endif; ?>
            <br><br>
<?php else : ?>
    <table class="viewhand-table">
                    <?php if($user_type == 'host') : ?>
                        <tr><td colspan="6" style="text-align: left;">自分のおてつだいに対してのやりとり一覧</td></tr>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align: left;">他の人のおてつだいに対してのやりとり一覧</td></tr>
                    <?php endif; ?>
                <tr>
                    <th>No</th>
                    <th>送信者</th>
                    <th>メッセージ</th>
                </tr>
                <?php if(!empty($exchanges) && count($exchanges) > 0): ?>
                    <?php $rowCount = 0; ?>
                    <?php foreach($exchanges as $value): ?>
                        <?php $rowCount++; ?>
                            <tr>
                                <td><?=h('e1_sequential_no', $value) ?></td>
                                <td><?=h('u3_nickname', $value) ?></td>
                                <td><?=h('e1_body', $value) ?></td>
                            </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align: center;">やりとり履歴がありません。</td></tr>
                <?php endif; ?>
                </table>

                <table class="viewhand-table">
                <tr><form action="<?php print getUrl(); ?>" method="post">
                <th style="text-align: center;">本文<span class="required">*</span></th>
                <input type="hidden" name="e1_hand_id" value="<?=$e1_hand_id; ?>">
                <input type="hidden" name="e1_host_user_id" value="<?=$e1_host_user_id; ?>">
                <input type="hidden" name="e1_guest_user_id" value="<?=$e1_guest_user_id; ?>">
                <input type="hidden" name="e1_body_host_id" value="<?=$e1_body_host_id; ?>">

            
                <td>
                    <?php $key = 'e1_body' ?>
                        <!-- textarea内の改行ありcols="120%" rows="5のプレイスホルダー（chrome以外は未確認）：placeholder="本文&#13;&#10;はこちらに&#13;&#10;お書きください。" -->
                        <textarea name="<?=$key; ?>" style="width:100%;height:100%" placeholder="例) 詳細を聞かせてください。"></textarea>
                        <br><label class="error-message"><?=$errors[$key] ?? ''; ?></label>
                </td>
                <td style="text-align: center;"><button type="submit" name="view">送信</button></td>
                </tr>
                </form>
                </table>

<?php endif; ?>

        </main>
        <?php require_once(ROOT_PATH .'/Views/common/footer.php'); ?>
    </body>

    </html>
    <?php require_once(ROOT_PATH .'/Views/common/foot.php'); ?>