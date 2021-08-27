<?php
require_once(ROOT_PATH .'/Models/Db.php');

class Exchange extends Db {
    private $tablePhysical = 'exchanges';
    private $tableLogical = 'やりとり';
    public function __construct($pdo = null){
        parent::__construct($pdo);
    }


    public function selectExchangesWithHands($host_user_id = null, $guest_user_id = null) {
        $sql = $this->selectHandsAndExchangesSql($host_user_id, $guest_user_id);
        $sth = $this->pdo->prepare($sql);
        $params = array();
        if($host_user_id != null) {
            $params = array(':host_user_id' => $host_user_id);
        }elseif($guest_user_id != null) {
            $params = array(':guest_user_id' => $guest_user_id);
        }
        $sth->execute($params);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    public function selectMinID($hand_id, $host_user_id, $guest_user_id) {
        $sql = 
        "select MIN(id) as MIN_e1_id from exchanges where hand_id = :hand_id and host_user_id = :host_user_id and guest_user_id = :guest_user_id";
        $sth = $this->pdo->prepare($sql);
        $params = array
        (':hand_id' => $hand_id, ':host_user_id' => $host_user_id, ':guest_user_id' => $guest_user_id);
        $sth->execute($params);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }


    public function select($id) {
        $sql = $this->selectSql();
        $sth = $this->pdo->prepare($sql);
        $params = array(':e2_id' => $id, ':e3_id' => $id, ':e4_id' => $id);
        $sth->execute($params);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
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
            $params = array(':id' => $paramsPost['a1_id']);
            $this->makeResults(
                $results,
                $sth->execute($params),
                $sth->rowCount(),
                null,
                $paramsPost['a1_id']
            );
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['delete'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }

    /**
     * おてつだい情報を登録する
     * 
     * @return DynamicProperty $results 
     */
    public function insert($user_type) {

        $results = $this->initResults();
        $this->transaction();
    
        try {
            $sql = $this->insertSql();
            $sth = $this->pdo->prepare($sql);
            $paramsPost = $this->request['post'];

            $e1_body_host_id = '';
            if($user_type == 'host') {
                $e1_body_host_id = $paramsPost["e1_host_user_id"];
            }else{
                $e1_body_host_id = $paramsPost["e1_guest_user_id"];
            }

            $params = array(
            ":hand_id_1" => $paramsPost["e1_hand_id"],
            ":host_user_id_1" => $paramsPost["e1_host_user_id"],
            ":guest_user_id_1" => $paramsPost["e1_guest_user_id"],
            ":hand_id_2" => $paramsPost["e1_hand_id"],
            ":host_user_id_2" => $paramsPost["e1_host_user_id"],
            ":guest_user_id_2" => $paramsPost["e1_guest_user_id"],
            ":body_host_id" => $e1_body_host_id,
            ":body" => $paramsPost["e1_body"]
            );

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

            $latlng =$this->getlatlng($paramsPost);
            $lat = $latlng != null ? $latlng['lat'] : null;
            $lng = $latlng != null ? $latlng['lng'] : null;

            $params = array(
                ":id" => $paramsPost["a1_id"],
                ":zip_code_1" => $paramsPost["a1_zip_code_1"],
                ":zip_code_2" => $paramsPost["a1_zip_code_2"],
                ":state" => $paramsPost["a1_state"],
                ":city" => $paramsPost["a1_city"],
                ":address1" => $paramsPost["a1_address1"],
                ":address2" => $paramsPost["a1_address2"],
                ":address3" => $paramsPost["a1_address3"],
                ":lat" => $lat,
                ":lng" => $lng
                );


            // $params = $this->getParams($this->request[$this->getMethodType()], 
            // array('id', 'fee', 'title', 'hands_type', 'body', 'photos_path'), array(':id' => $id));
            // SQL実行結果                   : $sth->execute($params)
            // SQL実行件数                   : $sth->rowCount()
            // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
            // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
            $this->makeResults(
                $results,
                $sth->execute($params),
                $sth->rowCount(),
                null,
                $paramsPost["a1_id"],
            );
            $this->commit();
        } catch (PDOException $e) {
            $this->rollback();
            $this->getErrorInfo(sprintf($this->sqlError['update'], $this->tablePhysical, $this->tableLogical), $e);
        }

        return $results;
    }

    private function insertSql() {
        $sql = "";
        $sql .= "INSERT INTO ".$this->tablePhysical." ";
        $sql .= "   ( ";
        $sql .= "        hand_id ";
        $sql .= "      , sequential_no ";
        $sql .= "      , host_user_id ";
        $sql .= "      , guest_user_id ";
        $sql .= "      , body_host_id ";
        $sql .= "      , body ";
        $sql .= "   ) ";
        $sql .= "VALUES ";
        $sql .= "   ( ";
        $sql .= "        :hand_id_2 ";

        // ここから　↓↓↓　■■■■■■■　sequential_noに最大値＋1を設定する　■■■■■■■　↓↓↓　ここから
        // sequential_no　は　hand_id、host_user_id、guest_user_id　の条件でしぼってその最大値に＋１の値を登録
        // 最大値が取得できない場合は、条件のレコードが０件であるため、0に＋1をして、sequential_noは1となる
        $sql .= "      , IFNULL ";
        $sql .= "        ( ";
        $sql .= "           ( ";
        $sql .= "              SELECT MAX(sequential_no) AS e1_sequential_no ";
        $sql .= "              FROM exchanges AS e1 ";
        $sql .= "              WHERE hand_id = :hand_id_1 ";
        $sql .= "              AND host_user_id = :host_user_id_1 ";
        $sql .= "              AND guest_user_id = :guest_user_id_1 ";
        $sql .= "           ) ";
        $sql .= "        ,0 ";
        $sql .= "        ) + 1 ";
        // ここまで　↑↑↑　■■■■■■■　sequential_noに最大値＋1を設定する　■■■■■■■　↑↑↑　ここまで

        $sql .= "      , :host_user_id_2 ";
        $sql .= "      , :guest_user_id_2 ";
        $sql .= "      , :body_host_id ";
        $sql .= "      , :body ";
        $sql .= "   ) ";
        return $this->formatHalfWidthSpace($sql);
    }

    private function updateSql() {
        $sql = "";
        $sql .= "UPDATE ".$this->tablePhysical." ";
        $sql .= "SET ";
        $sql .= "        hand_id = :hand_id ";
        $sql .= "      , sequential_no = :sequentia l_no ";
        $sql .= "      , host_user_id = :host_user_id ";
        $sql .= "      , guest_user_id = :guest_user_id ";
        $sql .= "      , body_host_id = :body_host_id ";
        $sql .= "      , body = :body ";
        $sql .= "      , updated_at = CURRENT_TIMESTAMP() ";
        $sql .= "WHERE ";
        $sql .= "        id = :id ";
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

    
    // private function selectSql() {
    //     $sql = "";
    //     $sql .= "SELECT ";
    //     $sql .= "     id ";
    //     $sql .= "   , hand_id ";
    //     $sql .= "   , sequential_no ";
    //     $sql .= "   , host_user_id ";
    //     $sql .= "   , guest_user_id ";
    //     $sql .= "   , body_host_id ";
    //     $sql .= "   , body ";
    //     $sql .= "   , created_at ";
    //     $sql .= "   , updated_at ";
    //     $sql .= "   , del_flg ";
    //     $sql .= "FROM ";
    //     $sql .= "     ".$this->tablePhysical." ";
    //     $sql .= "WHERE ";
    //     $sql .= "     id = :id ";
    //     $sql .= "ORDER BY ";
    //     $sql .= "     id ASC ";
    //     return $this->formatHalfWidthSpace($sql);
    // }

    private function selectSql() {
        $sql = "";
        $sql .= "SELECT ";
        $sql .= "      e1.id AS e1_id ";
        $sql .= "    , e1.hand_id AS e1_hand_id ";
        $sql .= "    , h1.title AS h1_title ";
        $sql .= "    , e1.sequential_no AS e1_sequential_no ";
        $sql .= "    , e1.host_user_id AS e1_host_user_id ";
        $sql .= "    , u1.nickname AS u1_nickname ";
        $sql .= "    , e1.guest_user_id AS e1_guest_user_id ";
        $sql .= "    , u2.nickname AS u2_nickname ";
        $sql .= "    , e1.body_host_id AS e1_body_host_id ";
        $sql .= "    , u3.nickname AS u3_nickname ";
        $sql .= "    , e1.body AS e1_body ";
        $sql .= "    , e1.created_at AS e1_created_at ";
        $sql .= "    , e1.updated_at AS e1_updated_at ";
        $sql .= "    , e1.del_flg AS e1_del_flg  ";
        /////////////////////////////////////////////////////////////exchanges
        $sql .= "FROM ";
        $sql .= "      exchanges AS e1 ";
        /////////////////////////////////////////////////////////////hands
        $sql .= "LEFT OUTER JOIN ";
        $sql .= "      hands AS h1 ";
        $sql .= "ON ";
        $sql .= "      e1.hand_id = h1.id ";
        /////////////////////////////////////////////////////////////users
        $sql .= "LEFT OUTER JOIN ";
        $sql .= "      users AS u1 ";
        $sql .= "ON ";
        $sql .= "      e1.host_user_id = u1.id ";
        $sql .= "LEFT OUTER JOIN ";
        $sql .= "      users AS u2 ";
        $sql .= "ON ";
        $sql .= "      e1.guest_user_id = u2.id ";
        $sql .= "LEFT OUTER JOIN ";
        $sql .= "      users AS u3 ";
        $sql .= "ON ";
        $sql .= "      e1.body_host_id = u3.id ";
        /////////////////////////////////////////////////////////////WHERE
        $sql .= "WHERE ";
        $sql .= "      e1.hand_id = (SELECT e2.hand_id FROM exchanges AS e2 WHERE e2.id = :e2_id) ";
        $sql .= "AND ";
        $sql .= "      e1.host_user_id = (SELECT e3.host_user_id FROM exchanges AS e3 WHERE e3.id = :e3_id) ";
        $sql .= "AND ";
        $sql .= "      e1.guest_user_id = (SELECT e4.guest_user_id FROM exchanges AS e4 WHERE e4.id = :e4_id) ";
        /////////////////////////////////////////////////////////////ORDER BY
        $sql .= "ORDER BY ";
        $sql .= "      e1.sequential_no ASC ";

        return $this->formatHalfWidthSpace($sql);
    }

    private function selectHandsAndExchangesSql($host_user_id = null, $guest_user_id = null) {
        $sql = "";
        $sql .= "SELECT ";
        $sql .= "      u1.nickname AS u1_nickname ";
        $sql .= "    , u2.nickname AS u2_nickname ";
        $sql .= "    , h1.hand_type AS u1_hand_type ";
        $sql .= "    , h1.title AS h1_title ";
        $sql .= "    , e1.id AS e1_id  ";
        $sql .= "    , e1.hand_id AS e1_hand_id  ";
        $sql .= "    , e1.sequential_no AS e1_sequential_no ";
        $sql .= "    , e1.host_user_id AS e1_host_user_id ";
        $sql .= "    , e1.guest_user_id AS e1_guest_user_id ";
        $sql .= "    , e1.body_host_id AS e1_body_host_id ";
        $sql .= "    , e1.body AS e1_body ";
        $sql .= "    , e1.created_at AS e1_created_at ";
        $sql .= "    , e1.updated_at AS e1_updated_at ";
        $sql .= "    , e1.del_flg AS e1_del_flg ";
        $sql .= "FROM ";
        $sql .= "      exchanges AS e1 ";

        $sql .= "LEFT OUTER JOIN ";
        $sql .= "      users AS u1 ";
        $sql .= "ON ";
        $sql .= "      e1.host_user_id = u1.id ";

        $sql .= "LEFT OUTER JOIN ";
        $sql .= "      users AS u2 ";
        $sql .= "ON ";
        $sql .= "      e1.guest_user_id = u2.id ";

        $sql .= "LEFT OUTER JOIN ";
        $sql .= "      hands AS h1 ";
        $sql .= "ON ";
        $sql .= "      e1.hand_id = h1.id ";

        $sql .= "WHERE ";
        $sql .= "      e1.sequential_no = 1 ";

        $sql .= "AND ";
        if($host_user_id != null) {
            $sql .= "      e1.host_user_id = :host_user_id";
        }elseif($guest_user_id != null) {
            $sql .= "      e1.guest_user_id = :guest_user_id";
        }

        // $sql .= "ORDER BY ";
        // $sql .= "      e1.id ASC ";
        
        return $this->formatHalfWidthSpace($sql);
    }
    
}
?>