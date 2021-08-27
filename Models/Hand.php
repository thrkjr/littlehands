<?php
require_once(ROOT_PATH .'/Models/Db.php');

class Hand extends Db {
    private $tablePhysical = 'hands';
    private $tableLogical = 'おてつだい';
    public function __construct($pdo = null){
        parent::__construct($pdo);
    }

    /**
     * おてつだい情報を取得する(IDをキーとして)
     * 
     * @return DynamicProperty $results 
     */
    public function selectByID($id) {

        $results = $this->initResults();
        $this->transaction();

        try {
            $sql = $this->selectSql_By_ID();
            $sth = $this->pdo->prepare($sql);
            $params = array(':id' => $id);
            // SQL実行結果                   : $sth->execute($params)
            // SQL実行件数                   : $sth->rowCount()
            // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
            // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
            $this->makeResults(
                $results,
                $sth->execute($params),
                $sth->rowCount(),
                $sth->fetchAll(PDO::FETCH_ASSOC),
                $id
            );
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['select'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }

    /**
     * おてつだい情報を取得する(ユーザIDをキーとして)
     * 
     * @return DynamicProperty $results 
     */
    public function selectByUserID($user_id) {

        $results = $this->initResults();
        $this->transaction();

        try {
            $sql = $this->selectSql_By_user_id();
            $sth = $this->pdo->prepare($sql);
            $params = array(':user_id' => $user_id);
            // SQL実行結果                   : $sth->execute($params)
            // SQL実行件数                   : $sth->rowCount()
            // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
            // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
            $this->makeResults(
                $results,
                $sth->execute($params),
                $sth->rowCount(),
                $sth->fetchAll(PDO::FETCH_ASSOC),
                null
            );
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['select'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }

    /**
     * おてつだい情報を登録する
     * 
     * @return DynamicProperty $results 
     */
    public function insert() {

        $results = $this->initResults();
        $this->transaction();
    
        try {
            $sql = $this->insertSql();
            $sth = $this->pdo->prepare($sql);
            $paramsPost = $this->request['post'];

            $params = array(
            ":user_id" => $paramsPost["u1_id"],
            ":hand_type" => $paramsPost["h1_hand_type"],
            ":title" => $paramsPost["h1_title"],
            ":fee" => $paramsPost["h1_fee"],
            ":body" => $paramsPost["h1_body"],
            ":photos_path" => $paramsPost["h1_photos_path"]
            );

            // $params = $this->getParams($this->request['post'],
            //                             array('id', 'address_id', 'zip_code_1', 'zip_code_2', 'state', 'city', 'address1', 'address2', 'address3'));
            // SQL実行結果                   : $sth->execute($params)
            // SQL実行件数                   : $sth->rowCount()
            // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
            // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
            $this->makeResults(
                $results,
                $sth->execute($params),
                $sth->rowCount(),
                null,
                $this->pdo->lastInsertId()
            );
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['insert'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }

        
    public function add1_pageView($id = ""){
        $sql = 'update hands set page_view = page_view +1 where id = :id';
        $sth = $this->pdo->prepare($sql);
        $params = array(":id" => $id);
        $result = $sth->execute($params);
        return $result;
    }
    
    /**
     * おてつだい情報を更新する
     * 
     * @return DynamicProperty $results 
     */
    public function update() {

        $results = $this->initResults();
        $this->transaction();
    
        try {
            $sql = $this->updateSql();
            $sth = $this->pdo->prepare($sql);

            $paramsPost = $this->request['post'];
            $params = array(
                ":id" => $paramsPost["h1_id"],
                ":hand_type" => $paramsPost["h1_hand_type"],
                ":title" => $paramsPost["h1_title"],
                ":fee" => $paramsPost["h1_fee"],
                ":body" => $paramsPost["h1_body"],
                ":photos_path" => $paramsPost["h1_photos_path"]
                );

            // SQL実行結果                   : $sth->execute($params)
            // SQL実行件数                   : $sth->rowCount()
            // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
            // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
            $this->makeResults(
                $results,
                $sth->execute($params),
                $sth->rowCount(),
                null,
                $paramsPost["h1_id"]
            );
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['update'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }

    /**
     * おてつだい情報を論理削除する
     * 
     * @return DynamicProperty $results 
     */
    public function delete() {

        $results = $this->initResults();
        $this->transaction();
    
        try {
            $sql = $this->deleteSql();
            $sth = $this->pdo->prepare($sql);
            $paramsPost = $this->request['post'];
            $params = array(':id' => $paramsPost['h1_id']);
            // SQL実行結果                   : $sth->execute($params)
            // SQL実行件数                   : $sth->rowCount()
            // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
            // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
            $this->makeResults(
                $results,
                $sth->execute($params),
                $sth->rowCount(),
                null,
                $paramsPost['h1_id']
            );
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['delete'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }


    public function selectCount($user_id = null) {
        $results = $this->initResults();
        $this->transaction();
    
        try {
            $params = array();
            $sqlParams = array();

            if($this->getMethodType() != '') {
                $params = $this->request[$this->getMethodType()];
            }

            if($user_id == ''){
                $user_id = $this->isExistsKeyInArray('user_id', $params) ? $params['user_id'] : '';
            }

            $sql = $this->selectCountSql($user_id);
            $sth = $this->pdo->prepare($sql);
            
            if($user_id != null) {
                $sqlParams = array(':user_id' => $user_id);
                $this->makeResults(
                    $results,
                    $sth->execute($sqlParams),
                    $sth->fetchColumn(),
                    null,
                    $user_id
                );
            }else{
                $this->makeResults(
                    $results,
                    $sth->execute(),
                    $sth->fetchColumn(),
                    null,
                    null
                );
            }
            
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['select'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }
    
    /**
     * おてつだい情報を取得する(各検索条件を設定)
     * 
     * @return DynamicProperty $results 
     */
    public function select($id = '', $user_id = '', $page = 1) {

        $results = $this->initResults();
        $this->transaction();

        try {
            $params = array();
            $sqlParams = array();
            
            if($this->getMethodType() != '') {
                $params = $this->request[$this->getMethodType()];
            }

            if($id == '') {
                $id = $this->isExistsKeyInArray('id', $params) ? $params['id'] : '';
            }

            $page = $this->isExistsKeyInArray('page', $params) ? $params['page'] : 1;

            if($id != '') {
                $sqlParams += array(':id' => $id);
            } else {

                if($user_id == ''){
                    $user_id = $this->isExistsKeyInArray('user_id', $params) ? $params['user_id'] : '';
                }
                if ($user_id != '') $sqlParams += array(':user_id' => $user_id);
                
                $hand_type = $this->isExistsKeyInArray('hand_type', $params) ? $params['hand_type'] : '';
                if ($hand_type != '' && $hand_type != 0) $sqlParams += array(':hand_type' => $hand_type);

                $fee_lower = $this->isExistsKeyInArray('fee_lower', $params) ? $params['fee_lower'] : '';
                $fee_upper = $this->isExistsKeyInArray('fee_upper', $params) ? $params['fee_upper'] : '';
                if($fee_lower != '' && $fee_upper != '') {
                    $sqlParams += array(':fee_lower' => $fee_lower);
                    $sqlParams += array(':fee_upper' => $fee_upper);
                }elseif($fee_lower != ''){
                    $sqlParams += array(':fee_lower' => $fee_lower);
                    $sqlParams += array(':fee_upper' => '4294967295');
                }elseif($fee_upper != ''){
                    $sqlParams += array(':fee_lower' => '0');
                    $sqlParams += array(':fee_upper' => $fee_upper);
                }
                $freeword = $this->isExistsKeyInArray('freeword', $params) && $params['freeword'] != '' ? '%'.$params['freeword'].'%' : "";
                if ($freeword != '') $sqlParams += array(':freeword_1' => $freeword, ':freeword_2' => $freeword);
        
            }

            $order_no = $this->isExistsKeyInArray('order_no', $params) ? $params['order_no'] : '4';

            $sql = $this->selectSql($sqlParams, $order_no);

            $countSql = $sql;
            $sth = $this->pdo->prepare($sql);
            $countResult = $sth->execute($sqlParams);
            $count = $sth->rowCount();

            //if($this->isExistsKeyInArray('page', $params)) {
                //$page = $params['page'];
                $sql .= ' LIMIT 20 OFFSET '.(20 * ($page - 1));
            //}

            $sth = $this->pdo->prepare($sql);
            
            // SQL実行結果                   : $sth->execute($params)
            // SQL実行件数                   : $sth->rowCount()
            // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
            // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
            $this->makeResults(
                $results,
                $sth->execute($sqlParams),
                $count,
                $sth->fetchAll(PDO::FETCH_ASSOC),
                null
            );
            $results->test_sql = $sql;
            $results->test_sqlParams = $sqlParams;

            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['select'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }

    private function selectSql($params, $orderNo = 0) {
        $sql = "";

        $sql .= "SELECT ";

        ///////////////////////////////////////////////ユーザ：users
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

        ///////////////////////////////////////////////おてつだい：hands
        $sql .= "   , h1.id                 AS  h1_id ";
        $sql .= "   , h1.user_id            AS  h1_user_id ";
        $sql .= "   , h1.hand_type          AS  h1_hand_type ";
        $sql .= "   , h1.title              AS  h1_title ";
        $sql .= "   , h1.fee                AS  h1_fee ";
        $sql .= "   , h1.body               AS  h1_body ";
        $sql .= "   , h1.photos_path        AS  h1_photos_path ";
        $sql .= "   , h1.page_view          AS  h1_page_view ";
        $sql .= "   , h1.created_at         AS  h1_created_at ";
        $sql .= "   , h1.updated_at         AS  h1_updated_at ";
        $sql .= "   , h1.del_flg            AS  h1_del_flg ";
        
        ///////////////////////////////////////////////住所：addresses
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
        
        ///////////////////////////////////////////////お気に入り：favorites
        $sql .= "   , (SELECT COUNT(id) FROM favorites WHERE hand_id = h1.id) AS f1_favorites ";

        ///////////////////////////////////////////////ユーザ：users
        $sql .= "FROM ";
        $sql .= "     users                 AS  u1 ";

        ///////////////////////////////////////////////おてつだい：hands
        $sql .= "LEFT OUTER JOIN ";
        $sql .= "     hands                 AS  h1 ";
        $sql .= "ON ";
        $sql .= "     u1.id                 =   h1.user_id ";

        ///////////////////////////////////////////////住所：addresses
        $sql .= "LEFT OUTER JOIN ";
        $sql .= "     addresses             AS  a1 ";
        $sql .= "ON ";
        $sql .= "     u1.id                 =   a1.user_id ";
        $sql .= "AND ";
        $sql .= "     h1.id                 =   a1.hand_id ";
        
        $sql .= "WHERE ";
        $sql .= "     h1.del_flg            =   0 ";

        if($this->isExistsKeyInArray(':id', $params)) {
            $sql .= "AND ";
            $sql .= "     h1.id             =   :id ";
        }else{
            if($this->isExistsKeyInArray(':user_id', $params)) {
                $sql .= "AND ";
                $sql .= "     h1.user_id    =   :user_id ";
            }
    
            if($this->isExistsKeyInArray(':hand_type', $params)) {
                $sql .= "AND ";
                $sql .= "     h1.hand_type  =   :hand_type ";
            }

            if($this->isExistsKeyInArray(':fee_lower', $params) || $this->isExistsKeyInArray(':fee_upper', $params)) {
                $sql .= "AND ";
                $sql .= "     fee between :fee_lower AND :fee_upper ";
            }
            
            if($this->isExistsKeyInArray(':freeword_1', $params) || $this->isExistsKeyInArray(':freeword_2', $params)) {
                $sql .= "AND ";
                $sql .= "   (title LIKE :freeword_1 OR body LIKE :freeword_2) ";
            }
            
            $sql .= "ORDER BY ";
            switch($orderNo) {
                case 0:// >新着
                    $sql .= "     h1_created_at DESC ";
                    break;
                case 1:// 報酬
                    $sql .= "     h1_fee DESC ";
                    $sql .= "   , h1_created_at DESC ";
                    break;
                case 2:// お気に入り数
                    $sql .= "     f1_favorites DESC ";
                    $sql .= "   , h1_created_at DESC ";
                    break;
                case 3:// 閲覧数
                    $sql .= "     h1_page_view DESC ";
                    $sql .= "   , h1_created_at DESC ";
                    break;
                case 4:// 登録日時
                    $sql .= "     h1_created_at ASC ";
                    break;           
            }
        }

        return $this->formatHalfWidthSpace($sql);
    }

    private function selectSql_By_ID() {
        $sql = "";
        $sql .= "SELECT * ";
        $sql .= "FROM ";
        $sql .= $this->tablePhysical." ";
        $sql .= "WHERE ";
        $sql .= "      id        =   :id ";
        $sql .= "AND ";
        $sql .= "   del_flg = 0 ";
        return $this->formatHalfWidthSpace($sql);
    }

    private function selectSql_By_user_id() {
        $sql = "";
        $sql .= "SELECT * ";
        $sql .= "FROM ";
        $sql .= $this->tablePhysical." ";
        $sql .= "WHERE ";
        $sql .= "   del_flg = 0 ";
        $sql .= "AND ";
        $sql .= "      user_id        =   :user_id ";
        return $this->formatHalfWidthSpace($sql);
    }
    


    private function insertSql() {
        $sql = "";
        $sql .= "INSERT INTO ".$this->tablePhysical." ";
        $sql .= "( ";
        $sql .= "     user_id ";
        $sql .= "   , hand_type ";
        $sql .= "   , title ";
        $sql .= "   , fee ";
        $sql .= "   , body ";
        $sql .= "   , photos_path ";
        $sql .= ") ";
        $sql .= "VALUES ";
        $sql .= "( ";
        $sql .= "     :user_id ";
        $sql .= "   , :hand_type ";
        $sql .= "   , :title ";
        $sql .= "   , :fee ";
        $sql .= "   , :body ";
        $sql .= "   , :photos_path ";
        $sql .= ") ";
        return $this->formatHalfWidthSpace($sql);
    }

    private function updateSql() {
        $sql = "";
        $sql .= "UPDATE ".$this->tablePhysical." ";
        $sql .= "SET ";
        $sql .= "         hand_type           =   :hand_type ";
        $sql .= "       , title               =   :title ";
        $sql .= "       , fee                 =   :fee ";
        $sql .= "       , body                =   :body ";
        $sql .= "       , photos_path         =   :photos_path ";
        $sql .= "       , updated_at          =    CURRENT_TIMESTAMP() ";
        $sql .= "WHERE ";
        $sql .= "         id                  =   :id ";
        return $this->formatHalfWidthSpace($sql);
    }

    private function deleteSql() {
        $sql = "";
        $sql .= "UPDATE ".$this->tablePhysical." ";
        $sql .= "SET ";
        $sql .= "          updated_at          =    CURRENT_TIMESTAMP()  ";
        $sql .= "         ,del_flg             =   1 ";
        $sql .= "WHERE ";
        $sql .= "          id                  =   :id ";
        return $this->formatHalfWidthSpace($sql);
    }

    private function selectCountSql($user_id) {
        $sql = "";
        ////////////////////////////////////////ユーザ
        $sql .= "SELECT COUNT(*) ";
        $sql .= "FROM ";
        $sql .= "       users AS u1 ";

        ////////////////////////////////////////おてつだい
        $sql .= "LEFT OUTER JOIN ";
        $sql .= "       hands AS h1 ";
        $sql .= "ON ";
        $sql .= "       u1.id = h1.user_id ";

        ////////////////////////////////////////条件
        $sql .= "WHERE ";
        $sql .= "       h1.del_flg = 0 ";
        if($user_id != null || $user_id != '') {
            $sql .= "AND ";
            $sql .= "       h1.user_id = :user_id ";
        }

        return $this->formatHalfWidthSpace($sql);
    }

}

?>