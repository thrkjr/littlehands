<?php

// バリデートクラス
class validate {

    const ESSENTIAL                      = '必須入力です。';
    const LIMIT_10                       = '10文字以内でご入力ください。';
    const LIMIT_11                       = '11文字以内でご入力ください。';
    const LIMIT_50                       = '50文字以内でご入力ください。';
    const LIMIT_100                      = '100文字以内でご入力ください。';
    const LIMIT_1000                     = '1,000文字以内でご入力ください。';
    const INCORRECT_EMAIL_OR_PASSWORD    = 'メールアドレスもしくはパスワードに間違いがあります。';
    const INCORRECT_EMAIL                = 'メールアドレスは正しい書式でご入力下さい。';
    const INCORRECT_PASSWORD             = '半角英数字をそれぞれ1種類以上含む8文字以上100文字以下でご入力ください。';
    const WHITESPACE_OR_EMPTY            = '未入力です。';
    const NOT_NUMBER                     = '数字のみ入力可能です。';
    const INCORRECT_DATE                 = '存在しない年月日です。';
    const CANNOT_LOGIN_ERR               = 'ログイン出来ません。管理者へお問い合わせ下さい。';
    const CANNOT_REGIST_ERR              = '正常に登録出来ませんでした。管理者へお問い合わせ下さい。';

    // コンストラクタ
    public function __construct() {}
    
    // Dbクラスにて取得したユーザ情報と入力値のパスワードのチェック
    // 新規登録画面用
    public function existsUser($sqlResults) {

        $results = $this->initResults();
        $result = false;    // チェック結果
        $errors = array();  // エラーメッセージ
        $key = "common";

        // SQLの実行結果がfalse
        if (!$sqlResults->result) {
            $errors[$key] = self::CANNOT_REGIST_ERR."　[SQL実行結果がfalse]"; //★一旦つけているだけ
        }else{
            // 入力したメールアドレスのユーザ情報が0件
            if($sqlResults->rowsCount == 0) {
                $result = true;//★★★★★★★★★★★★★★★★★★★★★★★★ここに来たらようやくチェック結果がＯＫである
            }else{
                $errors[$key] = self::CANNOT_REGIST_ERR."　[すでに同じユーザ情報（メールアドレス）が0件より多く登録されている]"; //★一旦つけているだけ
            }
        }

        $this->makeResults($results, $result, count($errors), $errors);
        return $results;
    }

    // Dbクラスにて取得したユーザ情報と入力値のパスワードのチェック
    // ログイン画面用
    public function checkUser(&$sqlResults, $password) {

        $results = $this->initResults();
        $result = false;    // チェック結果
        $errors = array();  // エラーメッセージ
        $key = "common";

        // SQLの実行結果がfalse
        if (!$sqlResults->result) {
            $errors[$key] = self::CANNOT_LOGIN_ERR."　[SQL実行結果がfalse]"; //★一旦つけているだけ
        }else{
            // 入力したメールアドレスのユーザ情報が0件
            if($sqlResults->rowsCount == 0) {
                $errors[$key] = self::INCORRECT_EMAIL_OR_PASSWORD."　[ユーザ情報が0件]"; //★一旦つけているだけ
            // 入力したメールアドレスのユーザ情報が1件より多い
            }elseif($sqlResults->rowsCount > 1) {
                $errors[$key] = self::CANNOT_LOGIN_ERR."　[ユーザ情報が1件より多い]"; //★一旦つけているだけ
            // 入力したメールアドレスのユーザ情報が1件の場合
            }else{
                // ユーザ情報のパスワード(ハッシュ値)と入力されたパスワードが一致しているか確認して、一致していなかった場合
                if (!password_verify($password, $sqlResults->rows[0]['u1_password'])) {
                    $errors[$key] = self::INCORRECT_EMAIL_OR_PASSWORD."　[パスワードが一致しない]"; //★一旦つけているだけ
                }else{
                    $result = true;//★★★★★★★★★★★★★★★★★★★★★★★★ここに来たらようやくチェック結果がＯＫである

                    // ユーザ情報のパスワード(ハッシュ値)は "" にする
                    $replacements = $sqlResults->rows[0];
                    $replacements['u1_password'] = "";
                    $sqlResults->rows = $replacements;
                }
            }
        }
        $this->makeResults($results, $result, count($errors), $errors);
        return $results;
    }

    // 入力値のチェック
    public function checkInput($params) {

        $results = $this->initResults();
        $errors = array();    // エラーメッセージ
        $key = "";            // $paramsのキー

        ////////////////////////////////////////////////////////////////ユーザ：users
        // nickname　ニックネーム　VARCHAR　50
        $key = 'u1_nickname';
        if ($this->isExistsKeyInArray($key, $params)) {
            if (!$this->isWhiteSpaceOrEmpty($params[$key])) {
                if (!$this->checkStringLength($params[$key], 50)) {
                    $errors[$key] = self::LIMIT_50;
                }
            }
        }

        // firstname　名前（姓）　VARCHAR　50
        $key = 'u1_firstname';
        if ($this->isExistsKeyInArray($key, $params)) {
            if (!$this->isWhiteSpaceOrEmpty($params[$key])) {
                if (!$this->checkStringLength($params[$key], 50)) {
                    $errors[$key] = self::LIMIT_50;
                }
            }
        }

        // lastname　名前（名）　VARCHAR　50
        $key = 'u1_lastname';
        if ($this->isExistsKeyInArray($key, $params)) {
            if (!$this->isWhiteSpaceOrEmpty($params[$key])) {
                if (!$this->checkStringLength($params[$key], 50)) {
                    $errors[$key] = self::LIMIT_50;
                }
            }
        }

        // firstname_kana　フリガナ（姓）　VARCHAR　50
        $key = 'u1_firstname_kana';
        if ($this->isExistsKeyInArray($key, $params)) {
            if (!$this->isWhiteSpaceOrEmpty($params[$key])) {
                if (!$this->checkStringLength($params[$key], 50)) {
                    $errors[$key] = self::LIMIT_50;
                }
            }
        }

        // lastname_kana　フリガナ（名）　VARCHAR　50
        $key = 'u1_lastname_kana';
        if ($this->isExistsKeyInArray($key, $params)) {
            if (!$this->isWhiteSpaceOrEmpty($params[$key])) {
                if (!$this->checkStringLength($params[$key], 50)) {
                    $errors[$key] = self::LIMIT_50;
                }
            }
        }
        
        // birth　生年月日　DATE
        $key = 'u1_birth';
        if ($this->isExistsKeyInArray($key, $params)) {
            if (!$this->isWhiteSpaceOrEmpty($params[$key])) {
                if (!$this->isDate($params[$key])) {
                    $errors[$key] = self::INCORRECT_DATE;
                }
            }
        }

        // tel　電話番号　VARCHAR　11
        $key = 'u1_tel';
        if ($this->isExistsKeyInArray($key, $params)) {
            if (!$this->isWhiteSpaceOrEmpty($params[$key])) {
                if (!$this->isNumeric($params[$key])) {
                    $errors[$key] = self::NOT_NUMBER;
                }elseif (!$this->checkStringLength($params[$key], 11)) {
                    $errors[$key] = self::LIMIT_11;
                }
            }
        }

        // self_introduction　自己紹介　TEXT
        $key = 'u1_self_introduction';
        if ($this->isExistsKeyInArray($key, $params)) {
            if (!$this->isWhiteSpaceOrEmpty($params[$key])) {
                if (!$this->checkStringLength($params[$key], 1000)) {
                    $errors[$key] = self::LIMIT_1000;
                }
            }
        }

        // email　メールアドレス　VARCHAR　100
        $key = 'u1_email';
        if ($this->isExistsKeyInArray($key, $params)) {
            if ($this->isWhiteSpaceOrEmpty($params[$key])) {
                $errors[$key] = self::ESSENTIAL;
            }else{
                if (!$this->checkStringLength($params[$key], 100)) {
                    $errors[$key] = self::LIMIT_100;
                }elseif (!$this->isCollectEmail($params[$key])) {
                    $errors[$key] = self::INCORRECT_EMAIL;
                }
            }
        }

        // password　パスワード　VARCHAR　100
        $key = 'u1_password';
        if ($this->isExistsKeyInArray($key, $params)) {
            if ($this->isWhiteSpaceOrEmpty($params[$key])) {
                $errors[$key] = self::ESSENTIAL;
            }else{
                if (!$this->checkStringLength($params[$key], 100)) {
                    $errors[$key] = self::LIMIT_100;
                }elseif (!$this->isCorrectPassword($params[$key])) {
                    $errors[$key] = self::INCORRECT_PASSWORD;
                }
            }
        }

        ////////////////////////////////////////////////////////////////おてつだい：hands
        // title　タイトル　VARCHAR　50
        $key = 'h1_title';
        if ($this->isExistsKeyInArray($key, $params)) {
            if ($this->isWhiteSpaceOrEmpty($params[$key])) {
                $errors[$key] = self::ESSENTIAL;
            }else{
                if (!$this->checkStringLength($params[$key], 50)) {
                    $errors[$key] = self::LIMIT_50;
                }
            }
        }

        // fee　報酬　INT
        $key = 'h1_fee';
        if ($this->isExistsKeyInArray($key, $params)) {
            if ($this->isWhiteSpaceOrEmpty($params[$key])) {
                $errors[$key] = self::ESSENTIAL;
            }else{
                if (!$this->isNumeric($params[$key])) {
                    $errors[$key] = self::NOT_NUMBER;
                }elseif (!$this->checkStringLength($params[$key], 10)) {
                    $errors[$key] = self::LIMIT_10;
                }
            }
        }

        // body　本文　TEXT
        $key = 'h1_body';
        if ($this->isExistsKeyInArray($key, $params)) {
            if ($this->isWhiteSpaceOrEmpty($params[$key])) {
                $errors[$key] = self::ESSENTIAL;
            }else{
                if (!$this->checkStringLength($params[$key], 1000)) {
                    $errors[$key] = self::LIMIT_1000;
                }
            }
        }

        // body　本文　TEXT
        $key = 'e1_body';
        if ($this->isExistsKeyInArray($key, $params)) {
            if ($this->isWhiteSpaceOrEmpty($params[$key])) {
                $errors[$key] = self::ESSENTIAL;
            }else{
                if (!$this->checkStringLength($params[$key], 1000)) {
                    $errors[$key] = self::LIMIT_1000;
                }
            }
        }

        ////////////////////////////////////////////////////////////////おてつだい：hands
        
        $result = false;
        if(count($errors) == 0) {
            $result = true;
        }
        $this->makeResults($results, $result, count($errors), $errors);

        return $results;

        // ●●●●●●●users　ユーザテーブル●●●●●●●
        // id　ID　INT
        // nickname　ニックネーム　VARCHAR　50
        // firstname　名前（姓）　VARCHAR　50
        // lastname　名前（名）　VARCHAR　50
        // firstname_kana　フリガナ（姓）　VARCHAR　50
        // lastname_kana　フリガナ（名）　VARCHAR　50
        // gender　性別　INT
        // birth　生年月日　DATE
        // tel　電話番号　VARCHAR　11
        // self_introduction　自己紹介　TEXT
        // email　メールアドレス　VARCHAR　100
        // password　パスワード　VARCHAR　100
        // thumbnail_path　サムネイルパス　VARCHAR　255
        // created_at　登録日時　DATETIME
        // updated_at　更新日時　DATETIME
        // role　権限　INT
        // del_flg　論理削除　BOOLEAN

        // ●●●●●●●hands　おてつだいテーブル●●●●●●●
        // id　ID　INT
        // user_id　ユーザID　INT
        // title　タイトル　VARCHAR　50
        // body　本文　TEXT
        // hand_type　おてつだい　INT
        // photos_path　写真パス　VARCHAR　255
        // fee　報酬　INT
        // created_at　登録日時　DATETIME
        // updated_at　更新日時　DATETIME
        // del_flg　論理削除　BOOLEAN

        // ●●●●●●●exchanges　やりとりテーブル●●●●●●●
        // id　ID　INT
        // branch_id　枝番　INT
        // lendhands_id　おてつだいID　INT
        // body　本文　TEXT
        // created_at　登録日時　DATETIME
        // updated_at　更新日時　DATETIME
        // del_flg　論理削除　BOOLEAN
    }

    /**
     * 入力値のエラー情報に初期値を設定し返却する
     * 
     * @return DynamicProperty $results
     */
    private function initResults() {
        $results = new DynamicProperty();
        $results->result = false;
        $results->errorsCount = 0;
        $results->errors = array();
        return $results;
    }

    /**
     * 入力値のエラー情報を作成し返却する
     * 
     * @param boolean $result
     * @param int $errorsCount
     * @param array $errors
     * @return DynamicProperty $results
     */
    private function makeResults(&$results, $result, $errorsCount, $errors) {
        $results->result = $result;
        $results->errorsCount = $errorsCount;
        $results->errors = $errors;
    }

    /**
     * 文字列が指定文字数以内かどうかのチェック
     *
     * @param string    $str        ：  チェック文字列
     * @param integer   $length     ：  文字数指定
     * @return boolean  true        ：  文字列が指定文字数以内である
     *                  false       ：  文字列が指定文字数以内ではない
     */
    private function checkStringLength($str, $length) {
        if (mb_strlen($str) > $length) {
            return false;
        }
        return true;
    }

    /**
     * 文字列が「全角半角スペース」、「空文字」、「改行」のみかチェック
     *
     * @param string    $str  ：  チェック文字列
     * @return boolean  true  ：  文字列が「全角半角スペース」、「空文字」、「改行」のみである
     *                  false ：  文字列が「全角半角スペース」、「空文字」、「改行」のみではない
     */
    private function isWhiteSpaceOrEmpty( $str ) {
        // 前後の全角半角スペースを除去
        $str = preg_replace( '/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $str );
        // 文字列が空文字の場合
        if ( $str === "" || strcmp( $str, "" ) == 0 ) {
            return true;
        }
        return false;
    }

    // $a1 = isNumeric("123.4a");                  //false
    // $a2 = isNumeric(123.4);                     //false
    // $a3 = isNumeric(123);                       //true
    // $a4 = isNumeric("123.4");                   //false
    // $a5 = isNumeric("123");                     //true
    // $a6 = isNumeric("123f");                    //false
    // $a7 = isNumeric(1.234);                     //false
    // $a8 = isNumeric(1.2e3); // 1200             //true 
    // $a9 = isNumeric(7E-10); // 0.0000000007     //false 
    // $a10 = isNumeric("1_234.567");              //false
    // $a11 = isNumeric(true);                     //false
    // $a12 = isNumeric(false);                    //false
    // $a13 = isNumeric("あいうえお");              //false
    /**
     * 文字列が「数字」のみであるかどうかのチェック
     *
     * @param string    $str  ：  チェック文字列
     * @return boolean  true  ：  文字列が「数字」である
     *                  false ：  文字列が「数字」ではない
     */
    private function isNumeric( $str ) {
        if (is_numeric($str) && preg_match('/^[0-9]+$/', $str)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 文字列が「半角英数字をそれぞれ1種類以上含む8文字以上100文字以下」であるかのチェック
     * 
     * @param string    $str  ：  チェック文字列
     * @return boolean  true  ：  文字列が「半角英数字をそれぞれ1種類以上含む8文字以上100文字以下」である
     *                  false ：  文字列が「半角英数字をそれぞれ1種類以上含む8文字以上100文字以下」ではない
     */
    private function isCorrectPassword( $str ){
        return preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $str);
    }

    /**
     * 文字列が正しい「年月日」かどうかのチェック
     *　例：※2021/02/29は存在する年月日ではないため、結果はfalseとなる（ 2021/02/28の次の日は2021/03/01であるため ）
     * 
     * @param string    $str  ：  チェック文字列（右記の３パターンのみ有効：yyyy/MM/dd, yyyy-MM-dd, yyyyMMdd）
     * @return boolean  true  ：  文字列が正しい「年月日」である
     *                  false ：  文字列が正しい「年月日」ではない
     */
    private function isDate( $str ) {
        $result = false;
        if(preg_match("#^\d{4}([/-]?)\d{2}\\1\d{2}$#", $str, $match)) {
            $count = strlen($str);
            if($count === 8) {
                $str = substr_replace($str, '/', 6, 0);
                $str = substr_replace($str, '/', 4, 0);
            }else if($count === 10) {
                $str = str_replace('-', '/', $str);
            }
            list($Y, $m, $d) = explode('/', $str);
            $result  = checkdate((int)$m, (int)$d, (int)$Y);
        }
        return $result;
    }

    /**
     * 文字列が正しい書式の「メールアドレス」かどうかのチェック
     * 
     * @param string    $str  ：  チェック文字列
     * @return boolean  true  ：  文字列が正しい書式の「メールアドレス」である
     *                  false ：  文字列が正しい書式の「メールアドレス」ではない
     */
    private function isCollectEmail( $str ) {
        if (filter_var($str, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * 配列に指定のキーが存在するかどうかのチェック
     * 
     * @param string $key キー
     * @param array $array 配列
     * @return boolean  true  ：  配列に指定のキーが存在する
     *                  false ：  配列に指定のキーが存在しない
     */
    private function isExistsKeyInArray($key, $array) {
        if (!$this->isEmpty($array)) {
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
    private function isEmpty($array) {
        if ( empty($array) || !isset($array) || count($array) == 0 ) {
            return true;
        }
        return false;
    }
}