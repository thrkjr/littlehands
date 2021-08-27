<?php

/**
* Session管理クラス
*/

class Session
{
    /**
     * @var 唯一のインスタンス
     */
    private static $instance;

    /**
     * @var 同一インスタンス検証用のID
     */
    private $id;

    /**
     * 同一インスタンス検証用のIDを取得する
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * コンストラクタ
     */
    private function __construct()
    {
        $this->id = hash('sha256', time());
    }

    /**
     * 外部からインスタンスを呼び出す
     */
    final public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }
  
    /**
     * セッションスタート
     */
    public function start() {
        if ((function_exists('session_status')
            && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
                session_start();
        }
    }

    /**
     * セッションIDを生成する
     */
    public function generateSession($del = true) {
        session_regenerate_id($del);
    }

    /**
     * セッションIDを取得する
     */
    public function getSessionID() {
        return session_id();
    }

    /**
     * サインインの状態を登録する
     */
    public function setAuthenticateStatus($flag) {
        $this->set('_authenticated', (bool)$flag);
        $this->generateSession();
    }

    /**
     * サインインの状態が登録済かどうかのチェック
     */
    public function checkAuthenticateStatus() {
        return isset($_SESSION['_authenticated']);
    }

    /**
     * サインインの状態　もしくは　サインアウトの情報　のどちらであるかをチェックする
     */
    public function getAuthenticateStatus() {
        if (isset($_SESSION['_authenticated'])) {
            return (bool)$_SESSION['_authenticated'];
        }
        return null;
    }

    /**
     * セッションにURLを登録する
     */
    public function setUrl($url) {
        if ($this->isExist('url')) {
            $this->set('pre_url', $this->get('url'));
            $this->set('pre_filename', basename($this->get('filename')));
            $this->set('pre_basename', basename($this->get('url')));
        }
        $this->set('url', $url);
        $this->set('filename', basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']));
        $this->set('basename', basename($url));
        // url:"http://localhost/hands.php?disp_type=view&id=48"
        // filename:"hands.php"
        // basename:"hands.php?disp_type=view&id=48"
        // pre_url:"http://localhost/index.php"
        // pre_filename:"index.php"
        // pre_basename:"index.php"
    }
    /**
     * セッションに対しての存在チェック
     * $_SESSIONに値が設定されているかどうか
     */
    public function isExist($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * セット
     * $_SESSIONに値を設定
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * ゲット
     * $_SESSIONから値を取得
     */
    public function get($key, $par = null) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return $par;
    }

    /**
     * 指定のセッションを空にする
     */
    public function clear($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * セッション情報を全て取得する
     */
    public function getAllSessionInfo() {
        return $_SESSION;
    }

    /**
     * セッションを空にする
     */
    public function allClear() {
        $_SESSION = array();
        $this->generateSession();
    }

    // // 下記は外部から呼び出されないようにprivateとする
    // /**
    //  * Make clone magic method private, so nobody can clone instance.
    //  */
    // private function __clone() {}

    // /**
    //  * Make sleep magic method private, so nobody can serialize instance.
    //  */
    // private function __sleep() {}

    // /**
    //  * Make wakeup magic method private, so nobody can unserialize instance.
    //  */
    // private function __wakeup() {}

}