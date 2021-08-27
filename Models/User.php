<?php
require_once(ROOT_PATH .'/Models/Db.php');

class User extends Db {
    private $tablePhysical = 'users';
    private $tableLogical = 'ユーザ';
    public function __construct($pdo = null){
        parent::__construct($pdo);
    }

    /**
     * ユーザ情報を取得する
     * 
     * @return DynamicProperty $results 
     */
    public function select($u1_id = null) {

        $results = $this->initResults();
        $this->transaction();

        try {
            $sql = $this->selectSql($u1_id);
            $sth = $this->pdo->prepare($sql);
            $params = $this->request[$this->getMethodType()];
            $paramsSql = $u1_id != null ? array(':u1_id' => $u1_id) : array(':u1_email' => $params['u1_email']);
            // SQL実行結果                   : $sth->execute($params)
            // SQL実行件数                   : $sth->rowCount()
            // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
            // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
            $this->makeResults(
                $results,
                $sth->execute($paramsSql),
                $sth->rowCount(),
                $sth->fetchAll(PDO::FETCH_ASSOC),
                $u1_id
            );
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['select'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }

    /**
     * ユーザ情報を登録する
     * 
     * @return DynamicProperty $results 
     */
    public function insert() {

        $results = $this->initResults();
        $this->transaction();
    
        try {
            $sql = $this->insertSql();
            $sth = $this->pdo->prepare($sql);
            $params = $this->request[$this->getMethodType()];
            $paramsSql = array(':u1_email' => $params['u1_email'], ':u1_password' =>password_hash($params['u1_password'], PASSWORD_DEFAULT));
            // SQL実行結果                   : $sth->execute($params)
            // SQL実行件数                   : $sth->rowCount()
            // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC);
            // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
            $this->makeResults(
                $results,
                $sth->execute($paramsSql),
                $sth->rowCount(),
                null,
                $this->pdo->lastInsertId()
            );


            $naze = $this->pdo->lastInsertId();


            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['insert'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }

    /**
     * ユーザ情報を更新する
     * 
     * @return DynamicProperty $results 
     */
    public function update() {

        $results = $this->initResults();
        $this->transaction();
    
        try {
            $sql = $this->updateSql();
            $sth = $this->pdo->prepare($sql);
            $params = $this->request['post'];

            $paramBirth = null;
            if(isset($params['u1_birth']) && ($params['u1_birth'] != '' ||  $params['u1_birth'] != null)) {
                $paramBirth = $params['u1_birth'];
            }

            $paramsSql = array(
                            ':u1_nickname' => $params['u1_nickname']
                          , ':u1_firstname' => $params['u1_firstname']
                          , ':u1_lastname' => $params['u1_lastname']
                          , ':u1_firstname_kana' => $params['u1_firstname_kana']
                          , ':u1_lastname_kana' => $params['u1_lastname_kana']
                          , ':u1_gender' => $params['u1_gender']
                          , ':u1_birth' => $paramBirth
                          , ':u1_tel' => $params['u1_tel']
                          , ':u1_self_introduction' => $params['u1_self_introduction']
                          //, ':u1_thumbnail_path' => $params['u1_thumbnail_path']
                          , ':u1_id' => $params['u1_id']
                        );

            // SQL実行結果                   : $sth->execute($params)
            // SQL実行件数                   : $sth->rowCount()
            // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
            // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
            $this->makeResults(
                $results,
                $sth->execute($paramsSql),
                $sth->rowCount(),
                null,
                $params['u1_id']
            );
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['update'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }

    /**
     * ユーザ情報を論理削除する
     * 
     * @return DynamicProperty $results 
     */
    public function delete() {

        $results = $this->initResults();
        $this->transaction();
    
        try {
            $sql = $this->deleteSql();
            $sth = $this->pdo->prepare($sql);
            $params = $this->getParams($this->request[$this->getMethodType()]);
            // SQL実行結果                   : $sth->execute($params)
            // SQL実行件数                   : $sth->rowCount()
            // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
            // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
            $this->makeResults(
                $results,
                $sth->execute($params),
                $sth->rowCount(),
                null,
                $this->getID()
            );
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['delete'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }

    private function selectSql($u1_id = null) {

        $sql = "";
        $sql .= "SELECT ";

        //////////////////////////////////////////////////////////////////■ユーザ
        $sql .= "     u1.id                 AS  u1_id ";
        $sql .= "   , u1.nickname           AS  u1_nickname ";
        $sql .= "   , u1.firstname          AS  u1_firstname ";
        $sql .= "   , u1.lastname           AS  u1_lastname ";
        $sql .= "   , u1.firstname_kana     AS  u1_firstname_kana ";
        $sql .= "   , u1.lastname_kana      AS  u1_lastname_kana ";
        $sql .= "   , u1.gender             AS  u1_gender ";
        $sql .= "   , u1.birth              AS  u1_birth ";
        $sql .= "   , u1.tel                AS  u1_tel ";
        $sql .= "   , u1.self_introduction  AS  u1_self_introduction ";
        $sql .= "   , u1.email              AS  u1_email ";
        $sql .= "   , u1.password           AS  u1_password ";
        $sql .= "   , u1.thumbnail_path     AS  u1_thumbnail_path ";
        $sql .= "   , u1.`role`             AS  u1_role ";
        $sql .= "   , u1.created_at         AS  u1_created_at ";
        $sql .= "   , u1.updated_at         AS  u1_updated_at ";
        $sql .= "   , u1.del_flg            AS  u1_del_flg ";

        //////////////////////////////////////////////////////////////////■住所
        $sql .= "   , a1.id                 AS  a1_id ";
        $sql .= "   , a1.user_id            AS  a1_user_id ";
        $sql .= "   , a1.hand_id            AS  a1_hand_id ";
        $sql .= "   , a1.zip_code_1         AS  a1_zip_code_1 ";
        $sql .= "   , a1.zip_code_2         AS  a1_zip_code_2 ";
        $sql .= "   , a1.`state`            AS  a1_state ";
        $sql .= "   , a1.city               AS  a1_city ";
        $sql .= "   , a1.address1           AS  a1_address1 ";
        $sql .= "   , a1.address2           AS  a1_address2 ";
        $sql .= "   , a1.address3           AS  a1_address3 ";
        $sql .= "   , a1.lat                AS  a1_lat ";
        $sql .= "   , a1.lng                AS  a1_lng ";
        $sql .= "   , a1.created_at         AS  a1_created_at ";
        $sql .= "   , a1.updated_at         AS  a1_updated_at ";
        $sql .= "   , a1.del_flg            AS  a1_del_flg ";

        $sql .= "FROM ";
        $sql .= "     users                 AS  u1 ";
        $sql .= "LEFT OUTER JOIN ";
        $sql .= "     addresses             AS  a1 ";
        $sql .= "ON ";
        $sql .= "     u1.id                 =   a1.user_id ";
        $sql .= "AND ";
        $sql .= "     a1.hand_id            =   0 ";

        $sql .= "WHERE ";
        if($u1_id != null) {
            $sql .= "     u1.id              =   :u1_id ";
        }else{
            $sql .= "     u1.email           =   :u1_email ";
        }
        

        return $this->formatHalfWidthSpace($sql);
        // $sql = "";
        // $sql .= "SELECT * ";
        // $sql .= "FROM ";
        // $sql .= $this->tablePhysical." ";
        // $sql .= "WHERE ";
        // $sql .= "      email        =   :email ";
        // return $this->formatHalfWidthSpace($sql);
    }



    private function insertSql() {
        $sql = "";
        $sql .= "INSERT INTO ";
        $sql .= $this->tablePhysical." ";
        $sql .= "       ( ";
        $sql .= "               email ";
        $sql .= "             , password ";
        $sql .= "       ) ";
        $sql .= "   VALUES ";
        $sql .= "       ( ";
        $sql .= "               :u1_email ";
        $sql .= "             , :u1_password ";
        $sql .= "       ) ";
        return $this->formatHalfWidthSpace($sql);
    }

    private function updateSql() {
        $sql = "";
        $sql .= "UPDATE ".$this->tablePhysical." ";
        $sql .= "SET ";
        $sql .= "         nickname          =   :u1_nickname ";
        $sql .= "       , firstname         =   :u1_firstname ";
        $sql .= "       , lastname          =   :u1_lastname ";
        $sql .= "       , firstname_kana    =   :u1_firstname_kana ";
        $sql .= "       , lastname_kana     =   :u1_lastname_kana ";
        $sql .= "       , gender            =   :u1_gender ";
        $sql .= "       , birth             =   :u1_birth ";
        $sql .= "       , tel               =   :u1_tel ";
        $sql .= "       , self_introduction =   :u1_self_introduction ";
        // $sql .= "       , thumbnail_path    =   :u1_thumbnail_path ";
        $sql .= "       , updated_at        =    CURRENT_TIMESTAMP() ";
        $sql .= "WHERE ";
        $sql .= "         id                =   :u1_id ";
        return $this->formatHalfWidthSpace($sql);
    }

    private function deleteSql() {
        $sql = "";
        $sql .= "UPDATE ".$this->tablePhysical." ";
        $sql .= "SET ";
        $sql .= "         del_flg           =   1 ";
        $sql .= "WHERE ";
        $sql .= "         id                =   :u1_id ";
        return $this->formatHalfWidthSpace($sql);
    }

}

?>