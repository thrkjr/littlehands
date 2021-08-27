<?php
require_once(ROOT_PATH .'/Views/common/head.php');
if($_SERVER["REQUEST_METHOD"] == 'POST') {
    $file_name = $_POST['file_name'];
    if($file_name == 'login.php' || $file_name == 'signup.php' ) {
        $results = $littlehand->checkInput();
        if ($results->result) {
            $littlehand->initModels();
            if($file_name == 'login.php') {
                $results = $littlehand->login();
            }elseif($file_name == 'signup.php') {
                $results = $littlehand->signup();
            }
        }


        if ($results->result) {
            $s->set('user', $results->rows);
            $s->setAuthenticateStatus(true); // サインインの状態にする
        }else{
            $s->set('user', $_POST);
            $s->set('errors', $results->errors);
            header( 'Location: ' .$file_name);
            exit();
        }
    }
}
$authenticateStatus = $s->checkAuthenticateStatus() ? $s->getAuthenticateStatus() : false;
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>ちょっとおてつだい | トップページ</title>
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <!-- ↓下記のstylesheetは当ファイル内のdisplay-div内の各アイコンを表示するために使用しています。 -->
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <!-- key = AIzaSyDcA-kXWyf1YGOUDChR6QJsCYr3fCyNAvc -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDcA-kXWyf1YGOUDChR6QJsCYr3fCyNAvc&libraries=&v=weekly" async></script>
        <!--
    Community Geocoder : https://github.com/geolonia/community-geocoder/blob/master/README.md : https://geolonia.com/
    【ライセンス、利用規約】
    ・ソースコードのライセンスは MIT ライセンスです。
    ・取得した緯度経度の情報のご利用方法に制限はありません。他社の地図、アプリ、その他ご自由にご利用ください。
    ・ご利用に際しましては、できればソーシャルでのシェア、Geolonia へのリンクの設置などをしていただけると、開発者たちのモチベーションが上がると思います。
    プルリクや Issue は大歓迎です。住所の正規化を工夫すれば精度があがりそうなので、そのあたりのアイディアを募集しています。
    -->
    <script src="https://cdn.geolonia.com/community-geocoder.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
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
            <i class="fas fa-map-marked-alt cursor-pointer" id="display_map" onclick="clickDisplay('display_map')"></i>
        </div>
    </header>
    <main style="overflow: scroll;">

    <!--おてつだい数表示位置-->
    <div id ='handCount'></div>

    <!--テーブル生成位置-->
    <div id ='maintable'></div>

    <!--The div element for the map -->
    <div id="map"></div>


    
    </main>

<?php require_once(ROOT_PATH .'/Views/common/footer.php'); ?>
</body>
</html>
<?php require_once(ROOT_PATH .'/Views/common/foot.php'); ?>