<?php
// php関数ファイル

    function getUserIconPath($userID, $extension = 'jpg') {

        //dirname($_SERVER['DOCUMENT_ROOT']); //C:/xampp/htdocs/littlehands
        $myIconPath = dirname($_SERVER['DOCUMENT_ROOT'])."/Views/img/".$userID."/my.".$extension;
        $generalIconPath = dirname($_SERVER['DOCUMENT_ROOT'])."/Views/img/user.".$extension;

        $arrIconPath = array();
        if (file_exists($myIconPath)) {
            $arrIconPath += array('iconFullPath' => $myIconPath, 'iconPath' => "img/".$userID."/my.".$extension);
            return $arrIconPath;
        }else{
            if (file_exists($generalIconPath)) {
                $arrIconPath += array('iconFullPath' => $generalIconPath, 'iconPath' => "img/user.".$extension);
                return $arrIconPath;
            }
        }
        return '';
    }



    // 住所情報から経度と緯度を取得
    function add_latlng(&$user_info, $user) {
        if( $user['a1_state'] != '' &&
            $user['a1_city'] != '' &&
            $user['a1_address1'] != '' &&
            $user['a1_address2'] != '' ) {
                $address = $user['a1_state'].$user['a1_city'].$user['a1_address1'].$user['a1_address2'];
                $latlng = getlatlng($address);
        }
            $latlng = getlatlng($address);
            if($latlng != null) { 
                $user_info += array('a1_address' => $address);
                $user_info += array('lat' => $latlng['lat']);
                $user_info += array('lng' => $latlng['lng']);
            }
    }

    // 住所情報から経度と緯度を取得
    function getlatlng($address) {
        $apiurl = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDcA-kXWyf1YGOUDChR6QJsCYr3fCyNAvc&address=";

        $json = json_decode(@file_get_contents($apiurl.$address), false);

        if($json != null && $json->status == 'OK') {
            $lat = $json->results[0]->geometry->location->lat;
            $lng = $json->results[0]->geometry->location->lng;
            $latlng = array('lat' => $lat, 'lng' => $lng);
            return $latlng;
        }else{
            return null;
        }
    }
// ■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓　getlatlng($address)の実行結果７種類　↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■
// stdClass Object...は、print_r($json)をブラウザに出したものです。
// ■■■■■■■　1　■■■■■■■
// ■■■　引数（$address）の値　：　東京都江戸川区　■■■
// stdClass Object
// (
//     [results] => Array
//         (
//             [0] => stdClass Object
//                 (
//                     [address_components] => Array
//                         (
//                             [0] => stdClass Object
//                                 (
//                                     [long_name] => Edogawa City
//                                     [short_name] => Edogawa City
//                                     [types] => Array
//                                         (
//                                             [0] => locality
//                                             [1] => political
//                                         )

//                                 )

//                             [1] => stdClass Object
//                                 (
//                                     [long_name] => Tokyo
//                                     [short_name] => Tokyo
//                                     [types] => Array
//                                         (
//                                             [0] => administrative_area_level_1
//                                             [1] => political
//                                         )

//                                 )

//                             [2] => stdClass Object
//                                 (
//                                     [long_name] => Japan
//                                     [short_name] => JP
//                                     [types] => Array
//                                         (
//                                             [0] => country
//                                             [1] => political
//                                         )

//                                 )

//                         )

//                     [formatted_address] => Edogawa City, Tokyo, Japan
//                     [geometry] => stdClass Object
//                         (
//                             [bounds] => stdClass Object
//                                 (
//                                     [northeast] => stdClass Object
//                                         (
//                                             [lat] => 35.7507845
//                                             [lng] => 139.9188743
//                                         )

//                                     [southwest] => stdClass Object
//                                         (
//                                             [lat] => 35.6347275
//                                             [lng] => 139.8331653
//                                         )

//                                 )

//                             [location] => stdClass Object
//                                 (
//                                     [lat] => 35.7067011
//                                     [lng] => 139.8681899
//                                 )

//                             [location_type] => APPROXIMATE
//                             [viewport] => stdClass Object
//                                 (
//                                     [northeast] => stdClass Object
//                                         (
//                                             [lat] => 35.7507845
//                                             [lng] => 139.9188743
//                                         )

//                                     [southwest] => stdClass Object
//                                         (
//                                             [lat] => 35.6347275
//                                             [lng] => 139.8331653
//                                         )

//                                 )

//                         )

//                     [place_id] => ChIJlyOpErWHGGAR0156e32g1Xs
//                     [types] => Array
//                         (
//                             [0] => locality
//                             [1] => political
//                         )

//                 )

//         )

//     [status] => OK
// )


// ■■■■■■■　2　■■■■■■■
// ■■■　引数（$address）の値　：　東京都江戸川区2丁目　■■■
// stdClass Object
// (
//     [results] => Array
//         (
//             [0] => stdClass Object
//                 (
//                     [address_components] => Array
//                         (
//                             [0] => stdClass Object
//                                 (
//                                     [long_name] => 2 Chome
//                                     [short_name] => 2 Chome
//                                     [types] => Array
//                                         (
//                                             [0] => political
//                                             [1] => sublocality
//                                             [2] => sublocality_level_3
//                                         )

//                                 )

//                             [1] => stdClass Object
//                                 (
//                                     [long_name] => Edogawa
//                                     [short_name] => Edogawa
//                                     [types] => Array
//                                         (
//                                             [0] => political
//                                             [1] => sublocality
//                                             [2] => sublocality_level_2
//                                         )

//                                 )

//                             [2] => stdClass Object
//                                 (
//                                     [long_name] => Edogawa City
//                                     [short_name] => Edogawa City
//                                     [types] => Array
//                                         (
//                                             [0] => locality
//                                             [1] => political
//                                         )

//                                 )

//                             [3] => stdClass Object
//                                 (
//                                     [long_name] => Tokyo
//                                     [short_name] => Tokyo
//                                     [types] => Array
//                                         (
//                                             [0] => administrative_area_level_1
//                                             [1] => political
//                                         )

//                                 )

//                             [4] => stdClass Object
//                                 (
//                                     [long_name] => Japan
//                                     [short_name] => JP
//                                     [types] => Array
//                                         (
//                                             [0] => country
//                                             [1] => political
//                                         )

//                                 )

//                             [5] => stdClass Object
//                                 (
//                                     [long_name] => 132-0013
//                                     [short_name] => 132-0013
//                                     [types] => Array
//                                         (
//                                             [0] => postal_code
//                                         )

//                                 )

//                         )

//                     [formatted_address] => 2 Chome Edogawa, Edogawa City, Tokyo 132-0013, Japan
//                     [geometry] => stdClass Object
//                         (
//                             [bounds] => stdClass Object
//                                 (
//                                     [northeast] => stdClass Object
//                                         (
//                                             [lat] => 35.6885512
//                                             [lng] => 139.9075465
//                                         )

//                                     [southwest] => stdClass Object
//                                         (
//                                             [lat] => 35.6824525
//                                             [lng] => 139.8958951
//                                         )

//                                 )

//                             [location] => stdClass Object
//                                 (
//                                     [lat] => 35.6855684
//                                     [lng] => 139.9014576
//                                 )

//                             [location_type] => APPROXIMATE
//                             [viewport] => stdClass Object
//                                 (
//                                     [northeast] => stdClass Object
//                                         (
//                                             [lat] => 35.6885512
//                                             [lng] => 139.9075465
//                                         )

//                                     [southwest] => stdClass Object
//                                         (
//                                             [lat] => 35.6824525
//                                             [lng] => 139.8958951
//                                         )

//                                 )

//                         )

//                     [place_id] => ChIJf4ElsQyHGGAR4nAOMkP1mv4
//                     [types] => Array
//                         (
//                             [0] => political
//                             [1] => sublocality
//                             [2] => sublocality_level_3
//                         )

//                 )

//         )

//     [status] => OK
// )


// ■■■■■■■　3　■■■■■■■
// ■■■　引数（$address）の値　：　null　■■■
// print_r($json)の出力結果なし


// ■■■■■■■　4　■■■■■■■
// ■■■　引数（$address）の値　：　0　■■■
// stdClass Object
// (
//     [results] => Array
//         (
//             [0] => stdClass Object
//                 (
//                     [address_components] => Array
//                         (
//                             [0] => stdClass Object
//                                 (
//                                     [long_name] => 709
//                                     [short_name] => 709
//                                     [types] => Array
//                                         (
//                                             [0] => street_number
//                                         )

//                                 )

//                             [1] => stdClass Object
//                                 (
//                                     [long_name] => Kansas
//                                     [short_name] => Kansas
//                                     [types] => Array
//                                         (
//                                             [0] => route
//                                         )

//                                 )

//                             [2] => stdClass Object
//                                 (
//                                     [long_name] => Longton
//                                     [short_name] => Longton
//                                     [types] => Array
//                                         (
//                                             [0] => locality
//                                             [1] => political
//                                         )

//                                 )

//                             [3] => stdClass Object
//                                 (
//                                     [long_name] => Longton
//                                     [short_name] => Longton
//                                     [types] => Array
//                                         (
//                                             [0] => administrative_area_level_3
//                                             [1] => political
//                                         )

//                                 )

//                             [4] => stdClass Object
//                                 (
//                                     [long_name] => Elk County
//                                     [short_name] => Elk County
//                                     [types] => Array
//                                         (
//                                             [0] => administrative_area_level_2
//                                             [1] => political
//                                         )

//                                 )

//                             [5] => stdClass Object
//                                 (
//                                     [long_name] => Kansas
//                                     [short_name] => KS
//                                     [types] => Array
//                                         (
//                                             [0] => administrative_area_level_1
//                                             [1] => political
//                                         )

//                                 )

//                             [6] => stdClass Object
//                                 (
//                                     [long_name] => United States
//                                     [short_name] => US
//                                     [types] => Array
//                                         (
//                                             [0] => country
//                                             [1] => political
//                                         )

//                                 )

//                             [7] => stdClass Object
//                                 (
//                                     [long_name] => 67352
//                                     [short_name] => 67352
//                                     [types] => Array
//                                         (
//                                             [0] => postal_code
//                                         )

//                                 )

//                         )

//                     [formatted_address] => 709 Kansas, Longton, KS 67352, USA
//                     [geometry] => stdClass Object
//                         (
//                             [location] => stdClass Object
//                                 (
//                                     [lat] => 37.3744075
//                                     [lng] => -96.0811981
//                                 )

//                             [location_type] => ROOFTOP
//                             [viewport] => stdClass Object
//                                 (
//                                     [northeast] => stdClass Object
//                                         (
//                                             [lat] => 37.375756480291
//                                             [lng] => -96.079849119708
//                                         )

//                                     [southwest] => stdClass Object
//                                         (
//                                             [lat] => 37.373058519708
//                                             [lng] => -96.082547080292
//                                         )

//                                 )

//                         )

//                     [place_id] => ChIJq6viFKrpuYcRnAS2dkRzfAs
//                     [types] => Array
//                         (
//                             [0] => establishment
//                             [1] => point_of_interest
//                         )

//                 )

//         )

//     [status] => OK
// )


// ■■■■■■■　5　■■■■■■■
// ■■■　引数（$address）の値　：　東京都江戸川区2丁目３３－５　■■■
// stdClass Object
// (
//     [results] => Array
//         (
//             [0] => stdClass Object
//                 (
//                     [address_components] => Array
//                         (
//                             [0] => stdClass Object
//                                 (
//                                     [long_name] => 5
//                                     [short_name] => 5
//                                     [types] => Array
//                                         (
//                                             [0] => premise
//                                         )

//                                 )

//                             [1] => stdClass Object
//                                 (
//                                     [long_name] => 33
//                                     [short_name] => 33
//                                     [types] => Array
//                                         (
//                                             [0] => political
//                                             [1] => sublocality
//                                             [2] => sublocality_level_4
//                                         )

//                                 )

//                             [2] => stdClass Object
//                                 (
//                                     [long_name] => 2-chōme
//                                     [short_name] => 2-chōme
//                                     [types] => Array
//                                         (
//                                             [0] => political
//                                             [1] => sublocality
//                                             [2] => sublocality_level_3
//                                         )

//                                 )

//                             [3] => stdClass Object
//                                 (
//                                     [long_name] => Matsue
//                                     [short_name] => Matsue
//                                     [types] => Array
//                                         (
//                                             [0] => political
//                                             [1] => sublocality
//                                             [2] => sublocality_level_2
//                                         )

//                                 )

//                             [4] => stdClass Object
//                                 (
//                                     [long_name] => Edogawa City
//                                     [short_name] => Edogawa City
//                                     [types] => Array
//                                         (
//                                             [0] => locality
//                                             [1] => political
//                                         )

//                                 )

//                             [5] => stdClass Object
//                                 (
//                                     [long_name] => Tokyo
//                                     [short_name] => Tokyo
//                                     [types] => Array
//                                         (
//                                             [0] => administrative_area_level_1
//                                             [1] => political
//                                         )

//                                 )

//                             [6] => stdClass Object
//                                 (
//                                     [long_name] => Japan
//                                     [short_name] => JP
//                                     [types] => Array
//                                         (
//                                             [0] => country
//                                             [1] => political
//                                         )

//                                 )

//                             [7] => stdClass Object
//                                 (
//                                     [long_name] => 132-0025
//                                     [short_name] => 132-0025
//                                     [types] => Array
//                                         (
//                                             [0] => postal_code
//                                         )

//                                 )

//                         )

//                     [formatted_address] => 2-chōme-33-5 Matsue, Edogawa City, Tokyo 132-0025, Japan
//                     [geometry] => stdClass Object
//                         (
//                             [bounds] => stdClass Object
//                                 (
//                                     [northeast] => stdClass Object
//                                         (
//                                             [lat] => 35.7024255
//                                             [lng] => 139.8747226
//                                         )

//                                     [southwest] => stdClass Object
//                                         (
//                                             [lat] => 35.7023582
//                                             [lng] => 139.8746277
//                                         )

//                                 )

//                             [location] => stdClass Object
//                                 (
//                                     [lat] => 35.7023988
//                                     [lng] => 139.8746668
//                                 )

//                             [location_type] => ROOFTOP
//                             [viewport] => stdClass Object
//                                 (
//                                     [northeast] => stdClass Object
//                                         (
//                                             [lat] => 35.703740830292
//                                             [lng] => 139.87602413029
//                                         )

//                                     [southwest] => stdClass Object
//                                         (
//                                             [lat] => 35.701042869709
//                                             [lng] => 139.87332616971
//                                         )

//                                 )

//                         )

//                     [place_id] => ChIJnaFtBTiGGGAREq2acerYipo
//                     [types] => Array
//                         (
//                             [0] => premise
//                         )

//                 )

//         )

//     [status] => OK
// )


// ■■■■■■■　6　■■■■■■■
// ■■■　引数（$address）の値　：　東京都江戸川区西葛西5-11-15　あいうえおかきくけこ　■■■
// stdClass Object
// (
//     [results] => Array
//         (
//             [0] => stdClass Object
//                 (
//                     [address_components] => Array
//                         (
//                             [0] => stdClass Object
//                                 (
//                                     [long_name] => 15
//                                     [short_name] => 15
//                                     [types] => Array
//                                         (
//                                             [0] => premise
//                                         )

//                                 )

//                             [1] => stdClass Object
//                                 (
//                                     [long_name] => 11
//                                     [short_name] => 11
//                                     [types] => Array
//                                         (
//                                             [0] => political
//                                             [1] => sublocality
//                                             [2] => sublocality_level_4
//                                         )

//                                 )

//                             [2] => stdClass Object
//                                 (
//                                     [long_name] => 5-chōme
//                                     [short_name] => 5-chōme
//                                     [types] => Array
//                                         (
//                                             [0] => political
//                                             [1] => sublocality
//                                             [2] => sublocality_level_3
//                                         )

//                                 )

//                             [3] => stdClass Object
//                                 (
//                                     [long_name] => Nishikasai
//                                     [short_name] => Nishikasai
//                                     [types] => Array
//                                         (
//                                             [0] => political
//                                             [1] => sublocality
//                                             [2] => sublocality_level_2
//                                         )

//                                 )

//                             [4] => stdClass Object
//                                 (
//                                     [long_name] => Edogawa City
//                                     [short_name] => Edogawa City
//                                     [types] => Array
//                                         (
//                                             [0] => locality
//                                             [1] => political
//                                         )

//                                 )

//                             [5] => stdClass Object
//                                 (
//                                     [long_name] => Tokyo
//                                     [short_name] => Tokyo
//                                     [types] => Array
//                                         (
//                                             [0] => administrative_area_level_1
//                                             [1] => political
//                                         )

//                                 )

//                             [6] => stdClass Object
//                                 (
//                                     [long_name] => Japan
//                                     [short_name] => JP
//                                     [types] => Array
//                                         (
//                                             [0] => country
//                                             [1] => political
//                                         )

//                                 )

//                             [7] => stdClass Object
//                                 (
//                                     [long_name] => 134-0088
//                                     [short_name] => 134-0088
//                                     [types] => Array
//                                         (
//                                             [0] => postal_code
//                                         )

//                                 )

//                         )

//                     [formatted_address] => 5-chōme-11-15 Nishikasai, Edogawa City, Tokyo 134-0088, Japan
//                     [geometry] => stdClass Object
//                         (
//                             [location] => stdClass Object
//                                 (
//                                     [lat] => 35.6646522
//                                     [lng] => 139.8617034
//                                 )

//                             [location_type] => ROOFTOP
//                             [viewport] => stdClass Object
//                                 (
//                                     [northeast] => stdClass Object
//                                         (
//                                             [lat] => 35.666001180292
//                                             [lng] => 139.86305238029
//                                         )

//                                     [southwest] => stdClass Object
//                                         (
//                                             [lat] => 35.663303219709
//                                             [lng] => 139.86035441971
//                                         )

//                                 )

//                         )

//                     [partial_match] => 1
//                     [place_id] => ChIJj8fYnOaHGGARmahyawRck_8
//                     [plus_code] => stdClass Object
//                         (
//                             [compound_code] => MV76+VM Edogawa City, Tokyo, Japan
//                             [global_code] => 8Q7XMV76+VM
//                         )

//                     [types] => Array
//                         (
//                             [0] => street_address
//                         )

//                 )

//         )

//     [status] => OK
// )


// ■■■■■■■　7　■■■■■■■
// ■■■　引数（$address）の値　：　'' ←　空文字　■■■
// print_r($json)の出力結果なし
// ■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑　getlatlng($address)の実行結果７種類　↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■


    function getFileName() {//"login.php"
        return basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
    }

    function getFileNameWithParams($url, $fileName) {//"login.php?id=1"
        $fileNameWithParams = basename($url);
        if($fileName != $fileNameWithParams) {
            return $fileNameWithParams;
        }
        return "";
    }

    // 現在のファイルのURLを取得//"http://localhost/login.php?id=1"
    function getUrl() {
        $url = '';
        if ( isset( $_SERVER[ 'HTTPS' ] ) ) {
            $url .= 'https://';
        } else {
            $url .= 'http://';
        }
        if ( isset( $_SERVER[ 'HTTP_HOST' ] ) ) {
            $url .= $_SERVER[ 'HTTP_HOST' ];
        }
        if ( isset( $_SERVER[ 'REQUEST_URI' ] ) ) {
            $url .= $_SERVER[ 'REQUEST_URI' ];
        }

        if(isCorrectUrl($url)){
            return $url;
        }else{
            return null;
        }
    }

    // 正しいURLであるかの判定
    function isCorrectUrl( $url ) {
        if ( $url === "" || strcmp( $url, "" ) == 0 ) {
            return false;
        }
        $pattern_https = '/https?:\/{2}[\w\/:%#\$&\?\(\)~\.=\+\-]+/';
        $pattern_http = '/http?:\/{2}[\w\/:%#\$&\?\(\)~\.=\+\-]+/';
        $result_https = preg_match( $pattern_https, $url );
        $result_http = preg_match( $pattern_http, $url );
        if ( $result_https || $result_http ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 配列に指定のキーが存在するかどうかのチェック
     * 
     * @param string $key キー
     * @param array $array 配列
     * @return boolean  true  ：  配列に指定のキーが存在する
     *                  false ：  配列に指定のキーが存在しない
     */
    function isExistsKeyInArray($key, $array) {

        $a1 = isEmpty($array);
        $a2 = array_key_exists($key, $array);
        $a3 = isset($array[$key]);
        $a4 = in_array($key, $array);

        $bbb = "";

        $aaaaaaaa = $_GET;
        $abc = "";



        if (!isEmpty($array)) {
            if (array_key_exists($key, $array) || isset($array[$key]) ) {
                return true;
            }
        }
        return false;
    }

    /**
     * 配列が空かどうかのチェック
     * 
     * @param array $array 配列
     * @return boolean  true  ：  配列が空である
     *                  false ：  配列が空ではない
     */
    function isEmpty($array) {
        if ( empty($array) || !isset($array) || count($array) == 0 ) {
            return true;
        }
        return false;
    }

    // XSS対策
    function h($key, $array) {
        if (!empty($array) || isset($array) || count($array) == 0) {
            if (array_key_exists($key, $array) || isset($array[$key])) {
                return htmlspecialchars($array[$key], ENT_QUOTES, 'UTF-8');
            }
        }
        return '';
    }

    /**
     * 文字列が「全角半角スペース」、「空文字」、「改行」のみかチェック
     *
     * @param string    $str  ：  チェック文字列
     * @return boolean  true  ：  文字列が「全角半角スペース」、「空文字」、「改行」のみである
     *                  false ：  文字列が「全角半角スペース」、「空文字」、「改行」のみではない
     */
    function isWhiteSpaceOrEmpty( $str ) {

        $str = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $str );

        // 文字列が空文字の場合
        if ( $str === "" || strcmp( $str, "" ) == 0 ) {
            return true;
        }
        return false;

    }

?>