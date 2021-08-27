let nowDisplayType = "display_list";
var map;
var marker = [];
var infoWindow = [];
// マーカーを立てる場所名・緯度・経度　
//【経緯度（けいいど、英語: longitude and latitude）とは、経度（longitude）および緯度（latitude）】
var markerDataTest = [{
        title: 'ニックネームさん',
        name: 'TAM 東京',
        lat: 35.6954806,
        lng: 139.76325010000005,
        icon: 'img/1/my.jpg'
            /////////////////////icon: 'js/my.png'pngはなんかダメみたい 
    }, {
        name: '小川町駅',
        lat: 35.664505,
        lng: 139.861877
    }
    // ,{
    //     name: '淡路町駅',
    //     lat: 35.69496,
    //     lng: 139.76746000000003
    // } 
    // ,{
    //     name: '御茶ノ水駅',
    //     lat: 35.6993529,
    //     lng: 139.76526949999993
    // } 
    // ,{
    //     name: '神保町駅',
    //     lat: 35.695932,
    //     lng: 139.75762699999996
    // } 
    // ,{
    //     name: '新御茶ノ水駅',
    //     lat: 35.696932,
    //     lng: 139.76543200000003
    // }
];


//■■■■■■■■■■■■■■■■■　グーグルマップ　■■■■■■■■■■■■■■■■■
function dispMap(hands) {

    var isExistsUserInfo = false;
    if (user_info != null) {
        if (user_info['a1_lat'] != null && user_info['a1_lat'] != '' &&
            user_info['a1_lng'] != null && user_info['a1_lng'] != '') {
            hands.unshift(user_info);
            isExistsUserInfo = true;
        }
    }

    clearTable();
    clearMap();

    document.getElementById('map').style.height = "500px";


    var centerSet = false;
    // 地図の作成
    if (hands[0]['a1_lat'] != null && hands[0]['a1_lat'] != '' &&
        hands[0]['a1_lng'] != null && hands[0]['a1_lng'] != '') {
        var mapLatLng = new google.maps.LatLng({ lat: hands[0]['a1_lat'], lng: hands[0]['a1_lng'] }); // 緯度経度のデータ作成
        map = new google.maps.Map(document.getElementById('map'), { // #sampleに地図を埋め込む
            center: mapLatLng, // 地図の中心を指定
            zoom: 15 // 地図のズームを指定
        });
        centerSet = true;
    }

    //"C:/xampp/htdocs/littlehands/Views/img/user.jpg"
    //"C:/xampp/htdocs/littlehands/Views/img/1/my.jpg"
    //var icon_url_user = user_info['iconPath'];
    var icon_url_red = "http://mt.googleapis.com/vt/icon/name=icons/spotlight/spotlight-poi.png&scale=1";
    var icon_url_blue = "http://mt.google.com/vt/icon?color=ff004C13&name=icons/spotlight/spotlight-waypoint-blue.png";

    // マーカー毎の処理
    ///////////さくさんAPI使うと上限使用数を超えて課金されても嫌なので///////////
    ///////////一旦forの回数を決め打ちの 5　とした///////////
    //////////////for (var i = 0; i < hands.length; i++) {
    for (var i = 0; i < 5; i++) {

        if (!(hands[0]['a1_lat'] != null && hands[0]['a1_lat'] != '' &&
                hands[0]['a1_lng'] != null && hands[0]['a1_lng'] != '')) {
            continue;
        }

        markerLatLng = new google.maps.LatLng({ lat: hands[i]['a1_lat'], lng: hands[i]['a1_lng'] }); // 緯度経度のデータ作成

        var title = "";
        if (hands[i].h1_title != null) {
            title = hands[i].h1_title;
        }

        var iconUrl = "";
        if (hands[i]['h1_hand_type'] == 1) {
            iconUrl = icon_url_red;
        } else if (hands[i]['h1_hand_type'] == 2) {
            iconUrl = icon_url_blue;
        }

        marker[i] = new google.maps.Marker({ // マーカーの追加
            position: markerLatLng, // マーカーを立てる位置を指定
            title: title,
            map: map, // マーカーを立てる地図を指定
            icon: iconUrl
        });　

        infoWindow[i] = new google.maps.InfoWindow({ // 吹き出しの追加
            content: '<div class="map">' + hands[i]['u1_nickname'] + '</div>' // 吹き出しに表示する内容
        });

        markerEvent(i); // マーカーにクリックイベントを追加
    }


    if (isExistsUserInfo) {
        marker[0].setOptions({ // マーカーのオプション設定
            icon: {
                url: '../../img/logoWhiteBackColor.png', // マーカーの画像を変更
                scaledSize: new google.maps.Size(50, 50),
                fillColor: "#FF0000", //塗り潰し色
            }
        });
    }
}

// マーカーにクリックイベントを追加
function markerEvent(i) {
    marker[i].addListener('click', function() { // マーカーをクリックしたとき
        infoWindow[i].open(map, marker[i]); // 吹き出しの表示
    });
}
//■■■■■■■■■■■■■■■■■　グーグルマップ　■■■■■■■■■■■■■■■■■


function clearMap() {
    var parent = document.getElementById('map');
    var child = document.getElementById('map').childNodes[0];
    if (child != null) {
        // table要素を削除
        parent.removeChild(child);
    }
    parent.style.height = "0px";
}


// 画面表示時イベント
window.onload = function(e) {

    var href = window.location.href;
    var filename_ex = href.match(".+/(.+?)([\?#;].*)?$")[1]; // 拡張子付きで
    if (filename_ex == 'hands.php') {
        var disp_type = getParam('disp_type', href);
        if (disp_type != 'list') {
            return;
        }
    } else if (filename_ex == 'index.php') {
        document.getElementById('display_list').classList.add('cursor-pointer-opacity-1');
    }

    if (checkInput(e)) {
        dispHands();
    }
}

// 画面の各コントロールの値の変更時イベント
document.addEventListener('change', function(e) {

    var href = window.location.href;
    var filename_ex = href.match(".+/(.+?)([\?#;].*)?$")[1]; // 拡張子付きで
    if (filename_ex == 'hands.php') {
        var disp_type = getParam('disp_type', href);
        if (disp_type != 'list') {
            return;
        }
    }
    if (checkInput(e)) {
        dispHands();
    }
})

// 画面のdisplay-divの要素のクリック時イベント
function clickDisplay(displayType) {

    if (nowDisplayType != displayType) {

        if (displayType == 'display_list') {
            document.getElementById('display_list').classList.add('cursor-pointer-opacity-1');
            document.getElementById('display_map').classList.remove('cursor-pointer-opacity-1');

        } else if (displayType == 'display_map') {
            document.getElementById('display_list').classList.remove('cursor-pointer-opacity-1');
            document.getElementById('display_map').classList.add('cursor-pointer-opacity-1');
        }
        nowDisplayType = displayType;
        dispHands();
    }
}

/**
 * Get the URL parameter value
 *
 * @param  name {string} パラメータのキー文字列
 * @return  url {url} 対象のURL文字列（任意）
 */
function getParam(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}


function getUrlParams(url, page) {

    var hand_type = "";
    var freeword = "";
    var fee_lower = "";
    var fee_upper = "";
    var order_no = "";

    var handtypeElements = document.getElementsByName('hand_type');
    for (let i = 0; i < handtypeElements.length; i++) {
        if (handtypeElements.item(i).checked) {
            hand_type = handtypeElements.item(i).value;
        }
    }
    freeword = document.getElementById('freeword').value;
    fee_lower = document.getElementById('fee_lower').value;
    fee_upper = document.getElementById('fee_upper').value;

    var order_no_element = document.getElementById('order_no');
    // 選択されている値の番号を取得
    order_no = order_no_element.selectedIndex;
    // 値を取得
    var order_no_text = order_no_element.options[order_no_element.selectedIndex].text;

    // 表示
    //document.getElementsByClassName('result')[0].textContent = sortText;
    //htmlへこれを書いて表示する　→　<div class="result"></div>

    var href = window.location.href;
    // 拡張子付きで
    var filename_ex = href.match(".+/(.+?)([\?#;].*)?$")[1];
    // 拡張子無しで
    var filename = href.match(".+/(.+?)\.[a-z]+([\?#;].*)?$")[1];

    // // URLからpageを取得
    // var page = 1;
    // if (getParam('page', href) != null) {
    //     page = getParam('page', href);
    // }

    var arrStr = '';
    if (filename_ex != 'index.php') {
        var user_id = '';
        if (user_info != null) {
            user_id = user_info['u1_id']
        }
        arrStr = [
            url, '?',
            'display_type=', nowDisplayType, '&',
            'page=', page, '&',
            'user_id=', user_id, '&',
            'hand_type=', hand_type, '&',
            'freeword=', freeword, '&',
            'fee_lower=', fee_lower, '&',
            'fee_upper=', fee_upper, '&',
            'order_no=', order_no
        ];
    } else {
        arrStr = [
            url, '?',
            'page=', page, '&',
            'display_type=', nowDisplayType, '&',
            'hand_type=', hand_type, '&',
            'freeword=', freeword, '&',
            'fee_lower=', fee_lower, '&',
            'fee_upper=', fee_upper, '&',
            'order_no=', order_no
        ];
    }

    var resultStr = arrStr.join("");
    return resultStr;
}

function checkInput(e) {

    var fee_lower = document.getElementById('fee_lower').value;
    var fee_upper = document.getElementById('fee_upper').value;
    var int_fee_lower = 0;
    var int_fee_upper = 4294967295;

    if (e.target.name == 'fee_lower' || e.target.name == 'fee_upper') {
        if (e.target.value.match(/[^0-9]/g)) {
            alert('半角数字で入力して下さい。');
            e.target.value = "";
            return false;
        } else {
            if (fee_lower != "") {
                int_fee_lower = Number(fee_lower);
            }
            if (fee_upper != "") {
                int_fee_upper = Number(fee_upper);
            }
        }
        if (int_fee_lower > int_fee_upper) {
            alert('報酬下限より報酬上限を大きくなるように数字を入力して下さい。');
            e.target.value = "";
            return false;
        }
        if (e.target.value != '') {
            e.target.value = Number(e.target.value);
        }

    }

    return true;
}

function dispHands(page = 1) {

    var url = getUrlParams('common/select.php', page);

    console.log(url);
    let hands = new Array();
    asyncCRUD(url)
        .then(function(response) {
            if (response.result) {

                var result = response.result;
                var rowsCount = response.rowsCount;
                var hands = response.rows;
                var id = response.id;
                var test_sql = response.test_sql;
                var test_sqlParams = response.test_sqlParams;
                var get = response.get;

                var selectCount = response.selectCount;

                // console.log("■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■");
                // console.log("■　01 : SQL実行結果　result　■");
                // console.log(result);
                // console.log("■　02 : SQL実行行数　rowsCount　■");
                // console.log(rowsCount);
                // console.log("■　03 : SQL実行取得行　hands　■");
                // console.log(hands);
                // console.log("■　04 : SQL実行成功ID　id　■");
                // console.log(id);
                // console.log("■　05 : SQL文　test_sql　■");
                // console.log(test_sql);
                // console.log("■　06 : SQLパラメタ　test_sqlParams　■");
                // console.log(test_sqlParams);
                // console.log("■　07 : 画面からのパラメタ　get　■");
                // console.log(get);
                // console.log("■　08 : おてつだい総件数（ログイン中はそのユーザIDで絞って。）　■");
                // console.log(selectCount);

            }

            if (nowDisplayType == "display_list") {
                dispTable(hands, rowsCount, page);
            } else if (nowDisplayType == "display_map") {
                dispMap(hands);
            }

        })
}

function dispTable(hands, selectCount, page = 1) {

    //var page = 1;
    var href = window.location.href;
    // // URLからpageを取得
    // if (getParam('page', href) != null) {
    //     page = getParam('page', href);
    // }

    // 拡張子付きで
    var filename_ex = href.match(".+/(.+?)([\?#;].*)?$")[1];
    // 拡張子無しで
    var filename = href.match(".+/(.+?)\.[a-z]+([\?#;].*)?$")[1];


    var baseNo = (page - 1) * 20;

    // table要素を生成
    var table = document.createElement('table');

    ////////////////////■テーブルヘッダ
    // tr要素を生成          
    var tr = document.createElement('tr');
    //////////////////////本文ありvar th_Arr = ['No', 'id', '投稿内容', 'タイトル', '報酬', '本文', '閲覧数', 'お気に入り数', '都道府県', '市区町村', 'その他住所', '番地', '建物名', '表示'];
    var th_Arr = ['No', 'ニックネーム', 'id', '投稿内容', 'タイトル', '報酬', '閲覧数', 'お気に入り数', '都道府県', '市区町村', 'その他住所', '番地', '建物名', '表示'];
    var thCount = th_Arr.length;
    for (var i = 0; i < th_Arr.length; i++) {
        var th = document.createElement('th');
        th.textContent = th_Arr[i];
        th.classList.add('white-space-nowrap');
        tr.appendChild(th);
    }
    // tr要素をtable要素の子要素に追加
    table.appendChild(tr);

    ////////////////////■テーブルデータ
    var handsLength = hands.length;

    // おてつだい情報が0件より多い場合
    if (handsLength > 0) {
        var dispView = '../../hands.php?disp_type=view&';
        //本文あり　var td_Arr = ['No', 'h1_id', 'h1_hand_type', 'h1_title', 'h1_fee', 'h1_body', 'h1_page_view', 'f1_favorites', 'a1_state', 'a1_city', 'a1_address1', 'a1_address2', 'a1_address3', 'dispView'];

        //本文なし
        var td_Arr = ['No', 'u1_nickname', 'h1_id', 'h1_hand_type', 'h1_title', 'h1_fee', 'h1_page_view', 'f1_favorites', 'a1_state', 'a1_city', 'a1_address1', 'a1_address2', 'a1_address3', 'dispView'];

        var No = 0;

        for (var i = 0; i < handsLength; i++) {

            // tr要素を生成
            tr = document.createElement('tr');
            No++;
            for (var j = 0; j < td_Arr.length; j++) {

                if (td_Arr[j] == 'h1_body') {
                    continue;
                }

                // td要素を生成
                var td = document.createElement('td');

                // td要素内にテキストを追加
                if (td_Arr[j] == 'No') {
                    td.textContent = baseNo + No;
                } else if (td_Arr[j] == 'h1_hand_type') {
                    if (hands[i][td_Arr[j]] == 1) {
                        td.textContent = 'てつだって';
                    } else if (hands[i][td_Arr[j]] == 2) {
                        td.textContent = 'てつだうよ';
                    }
                } else if (td_Arr[j] == 'dispView') {
                    // td.textContent = "<a href='" + dispView + "?id=" + hands[i]['h1_id'] + "'>表示</a>";
                    td.innerHTML = "<a href=" + dispView + "id=" + hands[i]['h1_id'] + ">表示</a>";
                } else {
                    td.textContent = hands[i][td_Arr[j]];
                }

                // if (td_Arr[j] != 'h1_body') {
                //     td.classList.add('white-space-nowrap');
                // }

                // td要素をtr要素の子要素に追加
                tr.appendChild(td);
            }

            // tr要素をtable要素の子要素に追加
            table.appendChild(tr);
        }

        //////////////////////////////////////////////// ページ設定ここから
        tr = document.createElement('tr');
        var td = document.createElement('td');
        td.colSpan = thCount;
        ////////////////////////////td.textContent = selectCount;
        ////////////////////////////var href = window.location.href;

        var linkUrl = '';
        if (filename_ex == 'index.php') {
            var linkUrl = '../../index.php?page=';
        } else {
            var linkUrl = '../../hands.php?disp_type=list&page=';
        }

        var pageCount = Math.ceil(selectCount / 20);
        var innerString = '';
        if (pageCount == 1) {
            innerString = "1";
        } else {
            for (var iPage = 1; iPage <= pageCount; iPage++) {
                if (iPage > 1) {
                    innerString = innerString + "　";
                }
                if (page == iPage) {
                    innerString = innerString + iPage;
                } else {
                    //<a href="javascript:void(0);" onclick="OnLinkClick();">Exec2</a><br />
                    //元innerString = innerString + "<a href=" + linkUrl + iPage + ">" + iPage + "</a>";
                    innerString = innerString +
                        "<a href='javascript:void(0);' onclick='pageClick(" + iPage + ");'>" + iPage + "</a>";
                }
            }
        }
        //////////////////////////////////////////////// ページ設定ここまで

        //////////もとtd.innerHTML = "<a href=" + listView + "id=" + hands[i]['h1_id'] + ">表示</a>";
        td.innerHTML = innerString;
        td.classList.add('white-space-nowrap', 'text-align-center');
        tr.appendChild(td);
        table.appendChild(tr);


        // おてつだい情報が0件の場合
    } else {

        // tr要素を生成   
        tr = document.createElement('tr');

        // td要素を生成
        var td = document.createElement('td');
        td.colSpan = thCount;
        td.textContent = 'おてつだい情報がありません。';
        td.classList.add('white-space-nowrap', 'text-align-center');
        // td要素をtr要素の子要素に追加
        tr.appendChild(td);

        // tr要素をtable要素の子要素に追加
        table.appendChild(tr);
    }

    clearTable();
    clearMap();

    // 生成したtable要素を追加する


    var p = document.createElement("p");
    p.innerHTML = "おてつだい総数　：" + selectCount;
    document.getElementById('handCount').appendChild(p);

    document.getElementById('maintable').appendChild(table);
    document.getElementById("maintable").childNodes[0].classList.add('viewhand-table');
}

function pageClick(page) {
    dispHands(page)
}


function clearTable() {
    var parent = document.getElementById('maintable');
    var child = document.getElementById('maintable').childNodes[0];
    if (child != null) {
        // table要素を削除
        parent.removeChild(child);
    }

    var parent1 = document.getElementById('handCount');
    var child1 = document.getElementById('handCount').childNodes[0];
    if (child1 != null) {
        // table要素を削除
        parent1.removeChild(child1);
    }
}

// 非同期でのデータCRUD処理
function asyncCRUD(url) {
    return new Promise(function(resolve) {
        var request = new XMLHttpRequest();
        request.open('GET', url, true);
        request.responseType = 'json';
        request.send();
        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var response = request.response;
                resolve(response);
            }
        }
    });
}