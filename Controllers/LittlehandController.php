<?php
require_once(ROOT_PATH .'Models/User.php');
require_once(ROOT_PATH .'Models/Hand.php');
require_once(ROOT_PATH .'Models/Address.php');
require_once(ROOT_PATH .'Models/Exchange.php');
require_once(ROOT_PATH .'Controllers/Validate.php');
require_once(ROOT_PATH .'Controllers/DynamicProperty.php');

class LittlehandController {
    private $request;   // リクエストパラメータ(GET, POST)
    private $User;      // Userモデル
    private $Hand;      // Handモデル
    private $Address;   // Addressモデル
    private $Exchange; // Exchangesモデル
    private $Validate;  // Validateクラス

    public function __construct() {
        // リクエストパラメータの取得
        $this->request['get'] = $_GET;
        $this->request['post'] = $_POST;
    }

    public function initModels() {
        // モデルオブジェクトの生成
        $this->User = new User();

        // 別モデルと連携
        $dbh = $this->User->get_db_handler();
        $this->Hand = new Hand($dbh);
        $this->Address = new Address($dbh);
        $this->Exchange = new Exchange($dbh);
    }

    public function checkInput() {
        $this->emptyRequest();

        // Validateクラスの生成
        $this->Validate = new Validate();
        $errorResults = $this->Validate->checkInput($this->request['post']);

        return $errorResults;
    }



    /**
     * ログイン
     */
    public function login() {

        // 当クラスのコンストラクタで渡されたメソッドタイプが空の場合、処理を終了 TODO：終了して良いのかどうかは検討の余地あり。
        $this->emptyRequest(); 

        // ■　ユーザ情報とパスワードチェック　■
        // Userモデルからユーザ情報を取得
        $sqlResults = $this->User->select();

        // Userモデルから取得したユーザ情報と、入力値のパスワードの整合性チェック
        $errorResults = $this->Validate->checkUser($sqlResults, $this->request[$this->getMethodType()]['u1_password']);

        // Userモデルから取得したユーザ情報が1件ではなかったり、入力したパスワードが間違っていたりした場合は、入力値エラーの情報をViewsへ戻す
        if(!$errorResults->result) return $errorResults;

        // 入力値にエラーがなく、取得したユーザ情報も1件だけの場合、ユーザ情報をViewsへ戻す
        return $sqlResults;
    }

    /**
     * 新規登録
     */
    public function signup() {

        // 当クラスのコンストラクタで渡されたメソッドタイプが空の場合、処理を終了 TODO：終了して良いのかどうかは検討の余地あり。
        $this->emptyRequest(); 

        // ■　ユーザ情報を取得　■
        // Userモデルからユーザ情報を取得
        $sqlResults = $this->User->select();

        // Userモデルにて取得したユーザ情報チェック
        $errorResults = $this->Validate->existsUser($sqlResults);

        // Userモデルにて取得したユーザ情報が0件ではなかった場合、入力値エラーの情報をViewsへ戻す
        if(!$errorResults->result) return $errorResults;

        // ■　入力値の情報をユーザ情報テーブルへ登録　■
        // Userモデルにてユーザ情報の登録
        $sqlResults = $this->User->insert();

        $sqlInsertAddress = $this->Address->insertInitial($sqlResults->id);

        // ■　ユーザ情報とパスワードチェック　■
        // Userモデルからユーザ情報を取得
        $sqlResults = $this->User->select($sqlResults->id);

        // Userモデルから取得したユーザ情報と、入力値のパスワードの整合性チェック
        $errorResults = $this->Validate->checkUser($sqlResults, $this->request[$this->getMethodType()]['u1_password']);

        // Userモデルから取得したユーザ情報が1件ではなかったり、入力したパスワードが間違っていたりした場合は、入力値エラーの情報をViewsへ戻す
        if(!$errorResults->result) return $errorResults;

        // 入力値にエラーがなく、取得したユーザ情報も1件だけの場合、ユーザ情報をViewsへ戻す
        return $sqlResults;
    }

    /**
     * おてつだい登録
     */
    public function registHand() {
        
        // 当クラスのコンストラクタで渡されたメソッドタイプが空の場合、処理を終了 TODO：終了して良いのかどうかは検討の余地あり。
        $this->emptyRequest(); 

        // // ■　入力値チェック　■
        // // 当クラスのコンストラクタで渡されたパラメータを取得し、入力値のチェック
        // $errorResults = $this->Validate->checkInput($this->request['post']);

        // // 入力値にエラーがある場合は、入力値エラーの情報をViewsへ戻す
        // if(!$errorResults->result) return $errorResults;

        // ■　入力値の情報をユーザ情報テーブルへ登録　■
        // Handモデルにてユーザ情報の登録
        $sqlInsertHandResults = $this->Hand->insert();

        // Addressモデルにて住所情報の登録
        $sqlInsertAddressResults = $this->Address->insert($sqlInsertHandResults->id);
 
        
        $sqlSelectHandResult = $this->Hand->select($sqlInsertHandResults->id);

        // 取得した登録情報をViewsへ戻す
        return $sqlSelectHandResult;
    }

    /**
     * おてつだい更新
     */
    public function updateUser() {
        
        // 当クラスのコンストラクタで渡されたメソッドタイプが空の場合、処理を終了 TODO：終了して良いのかどうかは検討の余地あり。
        $this->emptyRequest(); 

        // // ■　入力値チェック　■
        // // 当クラスのコンストラクタで渡されたパラメータを取得し、入力値のチェック
        // $errorResults = $this->Validate->checkInput($this->request['post']);

        // // 入力値にエラーがある場合は、入力値エラーの情報をViewsへ戻す
        // if(!$errorResults->result) return $errorResults;

        // ■　入力値の情報をユーザ情報テーブルへ登録　■
        // Handモデルにてユーザ情報の登録
        $sqlUpdateUserResults = $this->User->update();
        
        // Addressモデルにて住所情報の登録
        $sqlUpdateAddressResults = $this->Address->update();

        $sqlResults = $this->User->select($sqlUpdateUserResults->id);

        // 取得した登録情報をViewsへ戻す
        return $sqlResults;
    }

    /**
     * おてつだい更新
     */
    public function updateHand() {
        
        // 当クラスのコンストラクタで渡されたメソッドタイプが空の場合、処理を終了 TODO：終了して良いのかどうかは検討の余地あり。
        $this->emptyRequest(); 

        // // ■　入力値チェック　■
        // // 当クラスのコンストラクタで渡されたパラメータを取得し、入力値のチェック
        // $errorResults = $this->Validate->checkInput($this->request['post']);

        // // 入力値にエラーがある場合は、入力値エラーの情報をViewsへ戻す
        // if(!$errorResults->result) return $errorResults;

        // ■　入力値の情報をユーザ情報テーブルへ登録　■
        // Handモデルにてユーザ情報の登録
        $sqlUpdateResultsHand = $this->Hand->update();
        
        // Addressモデルにて住所情報の登録
        $sqlUpdateResultsAddress = $this->Address->update($sqlUpdateResultsHand->id);

        $sqlSelectResultsHand = $this->selectHand($sqlUpdateResultsHand->id);

        // 取得した登録情報をViewsへ戻す
        return $sqlSelectResultsHand;
    }

    public function selectExchangesWithHands($host_user_id = null, $guest_user_id = null) {
        
        $sqlSelectResults = $this->Exchange->selectExchangesWithHands($host_user_id, $guest_user_id);
        
        // 取得した登録情報をViewsへ戻す
        return $sqlSelectResults;
    }

    public function insertExchange($user_type) {
        
        $sqlSelectResults = $this->Exchange->insert($user_type);
        
        // 取得した登録情報をViewsへ戻す
        return $sqlSelectResults;
    }

    public function selectExchangesMinID($hand_id, $host_user_id, $guest_user_id)  {
        
        $sqlSelectResults = $this->Exchange->selectMinID($hand_id, $host_user_id, $guest_user_id) ;
        
        // 取得した登録情報をViewsへ戻す
        return $sqlSelectResults;
    }
    
    public function selectExchanges($id) {
        
        $sqlSelectResults = $this->Exchange->select($id);
        
        // 取得した登録情報をViewsへ戻す
        return $sqlSelectResults;
    }

    /**
     * おてつだい取得(各検索条件を設定)
     */
    public function selectHand($id = "", $user_id = "") {
        
        // 当クラスのコンストラクタで渡されたメソッドタイプが空の場合、処理を終了 TODO：終了して良いのかどうかは検討の余地あり。
        //$this->emptyRequest(); 

        $sqlSelectResults = $this->Hand->select($id, $user_id);
        
        // 取得した登録情報をViewsへ戻す
        return $sqlSelectResults;
    }

    public function selectHandsCount($user_id = "", $page = 0) {
        
        $sqlSelectCountResults = $this->Hand->selectCount($user_id, $page);
        
        // 取得した登録情報をViewsへ戻す
        return $sqlSelectCountResults;
    }

    /**
     * おてつだい取得(各検索条件を設定)
     */
    public function add1_pageViewHand($id = "") {
        
        // 当クラスのコンストラクタで渡されたメソッドタイプが空の場合、処理を終了 TODO：終了して良いのかどうかは検討の余地あり。
        //$this->emptyRequest(); 

        $sqlSelectResults = $this->Hand->add1_pageView($id);
        
        // 取得した登録情報をViewsへ戻す
        return $sqlSelectResults;
    }

    /**
     * おてつだい取得（IDをキーとして）
     */
    public function selecthandByID($id) {
        
        // 当クラスのコンストラクタで渡されたメソッドタイプが空の場合、処理を終了 TODO：終了して良いのかどうかは検討の余地あり。
        $this->emptyRequest(); 

        $sqlResults = $this->Hand->selectByID($id);
        
        // 取得した登録情報をViewsへ戻す
        return $sqlResults;
    }

        /**
     * おてつだい取得（IDをキーとして）
     */
    public function selectaddressByID($id) {
        
        // 当クラスのコンストラクタで渡されたメソッドタイプが空の場合、処理を終了 TODO：終了して良いのかどうかは検討の余地あり。
        $this->emptyRequest(); 

        $sqlResults = $this->Address->selectByID($id);
        
        // 取得した登録情報をViewsへ戻す
        return $sqlResults;
    }

    /**
     * おてつだい取得（ユーザIDをキーとして）
     */
    public function selecthandByUserID($user_id) {
        
        //////////////////////////////////////////////////// 当クラスのコンストラクタで渡されたメソッドタイプが空の場合、処理を終了 TODO：終了して良いのかどうかは検討の余地あり。
        //////////////////////////////////////////////$this->emptyRequest(); 

        $sqlResults = $this->Hand->selectByUserID($user_id);
        
        // 取得した登録情報をViewsへ戻す
        return $sqlResults;
    }

    /**
     * おてつだい削除（IDをキーとして）
     */
    public function deleteHand() {
        
        //////////////////////////////////////////////////// 当クラスのコンストラクタで渡されたメソッドタイプが空の場合、処理を終了 TODO：終了して良いのかどうかは検討の余地あり。
        //////////////////////////////////////////////$this->emptyRequest(); 

        $sqlHandResults = $this->Hand->delete();
        $sqlAddressResults = $this->Address->delete();
        
        // 取得した登録情報をViewsへ戻す
        return true;
    }
 
    /**
     * 当クラスのコンストラクタで渡されたメソッドタイプが空の場合、処理を終了
     */
    private function emptyRequest() {
        if ($this->getMethodType() == "") {
            return;
                                                                    // echo '指定のパラメータが不正です。このページを表示できません。';
                                                                    // exit;
        }
    }

    /**
     * 当クラスのコンストラクタで渡されたメソッドタイプを取得する
     * ・get と post　の両方で同時にデータが飛んでくることも想定されるが、一旦これで。
     * 
     * @return string   get or post or ""
     */
    private function getMethodType() {
        if (!$this->isEmpty($this->request['get']) || count($this->request['get']) != 0) {
            return 'get';
        }
        if (!$this->isEmpty($this->request['post']) || count($this->request['post']) != 0) {
            return 'post';
        }
        return "";
    }

    /**
     * 配列が空かどうかのチェック
     * 
     * @param array $array 配列
     * @return boolean  true  ：  配列が空である指定のキーが存在する
     *                  false ：  配列が空ではない
     */
    private function isEmpty($array) {
        if ( empty($array) || !isset($array) || count($array) == 0 ) {
            return true;
        }
        return false;
    }

}

?>