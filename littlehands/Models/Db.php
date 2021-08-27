<?php
require_once(ROOT_PATH . '/database.php');
require_once(ROOT_PATH . '/Controllers/DynamicProperty.php');

class Db {

    /**
     * データベース接続情報
     */
    private $conf = [
        'host' => HOST,
        'db' => DB,
        'user' => USER,
        'password' => PASSWORD,
        'charset' => CHARSET
    ];

    /**
     * SQLエラー文言
     */
    protected $sqlError = [
        'connect' => 'データベースへの接続に失敗しました。',
        'select' => '%sテーブルからの%s情報の取得に失敗しました。',
        'update' => '%sテーブルへの%s情報の更新に失敗しました。',
        'insert' => '%sテーブルへの%s情報の登録に失敗しました。',
        'delete' => '%sテーブルからの%s情報の削除に失敗しました。',
    ];

    /**
     * PDOオブジェクト
     */
    protected $pdo;

    /**
     * PDOステータス
     */
    private $status;

    /**
     * リクエストパラメータ(GET, POST)
     */
    protected $request;

    /**
     * コンストラクタ（インスタンスが生成されるときに実行されるマジックメソッド）
     */
    public function __construct($pdo = null) {

        // リクエストパラメータの取得
        $this->request['get'] = $_GET;
        $this->request['post'] = $_POST;

        // 接続情報が存在しない場合
        if(!$pdo){
             try{
                // PDOステータス
                $this->status = false;

                // データソース名（Data Source Name）
                $dsn = "mysql:host={$this->conf['host']};dbname={$this->conf['db']};charset={$this->conf['charset']};";
      
                // オプションの設定
                // PDO::ATTR_ERRMODEという属性でPDO::ERRMODE_EXCEPTIONの値を設定することでエラーが発生したときに、PDOExceptionの例外を投げてくれます。
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false
                ];

                $this->pdo = new PDO($dsn, $this->conf['user'], $this->conf['password'], $options);
                // 接続完了

                // PDOステータス
                $this->status  = true;
            
            } catch (PDOException $e) {
                $this->getErrorInfo($this->sqlError['connect'], $e);
                echo "接続失敗: " . $e->getMessage() . "\n";
                exit();
            }

         // 接続情報が存在する場合
         }else{
            $this->pdo = $pdo;
        }

    }

    /**
     * 別モデルと連携用のデータベースハンドラ
     */
    public function get_db_handler() {
        return $this->pdo;
    }

    /**
     * 当クラスのコンストラクタで渡されたメソッドタイプを取得する
     * ・get と post　の両方で同時にデータが飛んでくることも想定されるが、一旦これで。
     * 
     * @return string   get or post or ""
     */
    protected function getMethodType() {
        if (!$this->isEmpty($this->request['get']) || count($this->request['get']) != 0) {
            return 'get';
        }
        if (!$this->isEmpty($this->request['post']) || count($this->request['post']) != 0) {
            return 'post';
        }
        return "";
    }

    /**
     * 文字列を1,2の順番に置換し返却する
     * 1. 文字列の中から2文字以上連続の半角スペースを1文字の半角スペースへ置換
     * 2. 文字列の前後の半角スペースを削除
     * 
     * @param $str 置換前の文字列
     * @return $resultStr 置換後の文字列
     */
    protected function formatHalfWidthSpace($str) {
        $resultStr = "";
        $resultStr = preg_replace('/\s+/', ' ', $str);
        $resultStr = trim($resultStr);
        return $resultStr;
    }
    
    /**
     * トランザクション
     */
    protected function transaction()
    {
        if (!$this->pdo->inTransaction()) {
            $this->pdo->beginTransaction();
        }
    }
 
    /**
     * ロールバック
     */
    protected function rollback()
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }
 
    /**
     * コミット
     */
    protected function commit()
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->commit();
        }
    }
    
    /**
     * 住所情報から経度と緯度を取得
     * 
     * @param array 住所情報
     * @return array 経度と緯度
     */
    protected function getlatlng($paramsPost = array()) {

        if($paramsPost["a1_zip_code_1"] != '' && 
           $paramsPost["a1_zip_code_2"] != '' && 
           $paramsPost["a1_state"] != '' && 
           $paramsPost["a1_city"] != '' && 
           $paramsPost["a1_address1"] != '' && 
           $paramsPost["a1_address2"] != '' ) {

            $address = $paramsPost["a1_state"].
                       $paramsPost["a1_city"].
                       $paramsPost["a1_address1"].
                       $paramsPost["a1_address2"];

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
        }else{
            return null;
        }
    }

    /**
     * POST or GET で送られてきたパラメータから、各テーブルの主キーとなるIDを取得
     * 
     * @return string 各テーブルの主キーとなるID
     */
    protected function getID()
    {
        if($this->getMethodType() != '') {
            return $this->request[$this->getMethodType()]['id'];
        }
        return '0';
    }

    /**
     * SQLの実行結果に初期値を設定し返却する
     * 
     * @return DynamicProperty $results
     */
    protected function initResults() {
        $results = new DynamicProperty();
        $results->result = false;
        $results->rowsCount = 0;
        $results->rows = null;
        $results->id = null;
        return $results;
    }
    
    /**
     * SQLの実行結果を作成し返却する
     * 
     * @param DynamicProperty $results
     * @param boolean $result
     * @param int $rowsCount
     * @param array $rows
     * @param int $id
     */
    protected function makeResults(&$results, $result, $rowsCount, $rows = null, $id = null) {
        $results->result = $result;
        $results->rowsCount = $rowsCount;
        if(!is_null($rows)) {
            $results->rows = $rows;
        }
        if(!is_null($id)) {
            $results->id = $id;
        }
        return $results;
    }

    /**
     * 画面から渡されたパラメータ(post or get)をSQL用のパラメータへ変換し返却する
     * 
     * @param array $params 画面から渡されたパラメータ(post or get)
     * @param array $exceptKeys 画面から渡されたパラメータのうち、SQL用のパラメータに含めないキー
     * @param array $includeKeys 画面から渡されたパラメータではないが、SQL用のパラメータに含めたいキー
     * @return array $array SQL用のパラメータ
     */
    protected static function getParams($params, $exceptKeys = null, $includeKeys = null, $prefix = '') {
        $array = array();

        $privateExceptKeys = array('file_name', 'insert',  'update',  'delete',  'select',  'edit');
        foreach ($params as $key => $value) {

            if($prefix != '' && $prefix.'_' == substr($key, 0, 3)){
                
            // 含めたくないキー　や　各画面からpostで飛んでくる各画面ファイル名　は除外
            }elseif(!is_null($exceptKeys) && in_array($key, $exceptKeys)) {
                continue;
            }elseif(in_array($key, $privateExceptKeys)) {
                continue;
            }
            // パスワードはハッシュ値に変換する
            if($key == "password") {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }
            $array[":".$key] = $value;
        }

        if($includeKeys != null) {
            foreach ($includeKeys as $key => $value) {
                $array[":".$key] = $value;
            }
        }

        return $array;
    }

    /**
     * エラーメッセージを出力する
     * 
     * @param string $errorTitle
     * @param PDOException $e
     * @return string errorInfo
     */
    protected function getErrorInfo($errorTitle, $e) {
       // $errorTitle // エラー題名
       // $e->getMessage(); // エラー内容
       // $e->getFile(); // エラーを捕捉したファイル(フルパス)
       // $e->getLine(); // エラーを捕捉した行数

       // Todo : macなどのその他OSでは試していないため、Windows以外やその他の場合にどうなるかは不明
       // Todo : コンストラクタでの例外は下記のエンコードおまじないで日本語となる（これをしないと文字化けしてブラウザへ出力される）、それ以外は英語のままである。
       $errorMessageString = mb_convert_encoding($e->getMessage(), 'UTF-8', 'ASCII,JIS,UTF-8,CP51932,SJIS-win');
       $statusString = $this->status ? "true" : "false";

       return $errorTitle." :<br />".
       "　エラー内容 : "."{$errorMessageString}<br />".
       "　エラーファイル名 : "."{$e->getFile()}<br />".
       "　エラー行番号 : "."{$e->getLine()}<br />".
       "　DBステータス : "."{$statusString}";

        //元
        // return $errorTitle." :<br />".
        //     "　エラー内容 : "."{$e->getMessage()}<br />".
        //     "　エラーファイル名 : "."{$e->getFile()}<br />".
        //     "　エラー行番号 : "."{$e->getLine()}";

       // return $errorTitle.":<br />".
       //         "{$e->getMessage()}<br />".
       //         "{$e->getLine()}<br />".
       //         "{$e->getFile()}";

    
       // ■■■■■■■■■■■■■■MySQL動いてない
       // PDOExceptionの例外発生 :
       // 　エラー内容 : SQLSTATE[HY000] [2002] 対象のコンピューターによって拒否されたため、接続できませんでした。
       // 　エラーファイル名 : C:\xampp\htdocs\04_php_form\cafe\php\db.php
       // 　エラー行番号 : 63
       // 　DBステータス : false
    
       // ■■■■■■■■■■■■■■パスワード不正
       // PDOExcepionの例外発生 :
       // 　エラー内容 : SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost' (using password: YES)
       // 　エラーファイル名 : C:\xampp\htdocs\04_php_form\cafe\php\db.php
       // 　エラー行番号 : 63
       // 　DBステータス : false

       // ■■■■■■■■■■■■■■select文　不正
       // データ取得失敗 :
       // 　エラー内容 : SQLSTATE[42S02]: Base table or view not found: 1146 Table 'cafe.contact' doesn't exist
       // 　エラーファイル名 : C:\xampp\htdocs\04_php_form\cafe\php\db.php
       // 　エラー行番号 : 160
       // 　DBステータス : true
    }

        /**
     * 配列に指定のキーが存在するかどうかのチェック
     * 
     * @param string $key キー
     * @param array $array 配列
     * @return boolean  true  ：  配列に指定のキーが存在する
     *                  false ：  配列に指定のキーが存在しない
     */
    protected function isExistsKeyInArray($key, $array) {
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
     * @return boolean  true  ：  配列が空である指定のキーが存在する
     *                  false ：  配列が空ではない
     */
    protected function isEmpty($array) {
        if ( empty($array) || !isset($array) || count($array) == 0 ) {
            return true;
        }
        return false;
    }

    /**
     * デストラクタ（インスタンスが破棄されるときに実行されるマジックメソッド）
     */
    public function __destruct() {
        $this->pdo = null;
        // PDOステータス
        $this->status  = false;
    }

}

?>