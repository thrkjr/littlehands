<?php
require_once(ROOT_PATH .'/Models/Db.php');

class Address extends Db {
    private $tablePhysical = 'favorite';
    private $tableLogical = 'お気に入り';
    public function __construct($pdo = null){
        parent::__construct($pdo);
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
            // SQL実行結果                   : $sth->execute($params)
            // SQL実行件数                   : $sth->rowCount()
            // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
            // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
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

    // /**
    //  * おてつだい情報を取得する(各検索条件を設定)
    //  * 
    //  * @return DynamicProperty $results 
    //  */
    // public function selectByParams() {

    //     $results = $this->initResults();
    //     $this->transaction();

    //     try {
    //         $get = $this->request[$this->getMethodType()];
    //         $sortIndex = $this->isExistsKeyInArray('sortIndex', $get) && $get['sortIndex'] != '' ? $get['sortIndex'] : 0;
    //         $hands_type1 = 0;
    //         $hands_type2 = 0;
    //         if ($this->isExistsKeyInArray('hands_type', $get)) {
    //             if ($get['hands_type'] == 0) {
    //                 $hands_type1 = 1;
    //                 $hands_type2 = 2;
    //             } else {
    //                 $hands_type2 = $get['hands_type'];
    //             }
    //         }
    //         $fee_lower = $this->isExistsKeyInArray('fee_lower', $get) && $get['fee_lower'] != '' ? $get['fee_lower'] : 0;
    //         $fee_upper = $this->isExistsKeyInArray('fee_upper', $get) && $get['fee_upper'] != '' ? $get['fee_upper'] : 4294967295;
    //         $freeword = $this->isExistsKeyInArray('freeword', $get) && $get['freeword'] != '' ? $get['freeword'] : "";

    //         $sql = $this->selectSql_By_Params($freeword, $sortIndex);
    //         //$sql = "SELECT * FROM hands";
    //         $sth = $this->pdo->prepare($sql);

    //         $params = array();
    //         if ($freeword!="") {
    //             // TODO 検索文字として　「%」　や　「_」　が入力された場合のことも考えるべきである。が一旦これで。
    //             $params = array($hands_type1, $hands_type2, $fee_lower, $fee_upper, '%'.$freeword.'%', '%'.$freeword.'%');
    //         }else{
    //             $params = array($hands_type1, $hands_type2, $fee_lower, $fee_upper);
    //         }
            
    //         // SQL実行結果                   : $sth->execute($params)
    //         // SQL実行件数                   : $sth->rowCount()
    //         // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
    //         // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
    //         $this->makeResults(
    //             $results,
    //             $sth->execute($params),
    //             $sth->rowCount(),
    //             $sth->fetchAll(PDO::FETCH_ASSOC),
    //             null
    //         );
    //         $this->commit();
    //     } catch (PDOException $e) {
    //         $this->rollback();
    //         $this->getErrorInfo(sprintf($this->sqlError['select'], $this->tablePhysical, $this->tableLogical), $e);
    //     }

    //     return $results;
    // }

    // /**
    //  * おてつだい情報を取得する(IDをキーとして)
    //  * 
    //  * @return DynamicProperty $results 
    //  */
    // public function selectByID($id) {

    //     $results = $this->initResults();
    //     $this->transaction();

    //     try {
    //         $sql = $this->selectSql_By_ID();
    //         $sth = $this->pdo->prepare($sql);
    //         $params = array(':id' => $id);
    //         // SQL実行結果                   : $sth->execute($params)
    //         // SQL実行件数                   : $sth->rowCount()
    //         // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
    //         // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
    //         $this->makeResults(
    //             $results,
    //             $sth->execute($params),
    //             $sth->rowCount(),
    //             $sth->fetchAll(PDO::FETCH_ASSOC),
    //             $id
    //         );
    //         $this->commit();
    //     } catch (PDOException $e) {
    //         $this->rollback();
    //         $this->getErrorInfo(sprintf($this->sqlError['select'], $this->tablePhysical, $this->tableLogical), $e);
    //     }

    //     return $results;
    // }

    // /**
    //  * おてつだい情報を取得する(ユーザIDをキーとして)
    //  * 
    //  * @return DynamicProperty $results 
    //  */
    // public function selectByUserID($user_id) {

    //     $results = $this->initResults();
    //     $this->transaction();

    //     try {
    //         $sql = $this->selectSql_By_user_id();
    //         $sth = $this->pdo->prepare($sql);
    //         $params = array(':user_id' => $user_id);
    //         // SQL実行結果                   : $sth->execute($params)
    //         // SQL実行件数                   : $sth->rowCount()
    //         // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
    //         // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
    //         $this->makeResults(
    //             $results,
    //             $sth->execute($params),
    //             $sth->rowCount(),
    //             $sth->fetchAll(PDO::FETCH_ASSOC),
    //             null
    //         );
    //         $this->commit();
    //     } catch (PDOException $e) {
    //         $this->rollback();
    //         $this->getErrorInfo(sprintf($this->sqlError['select'], $this->tablePhysical, $this->tableLogical), $e);
    //     }

    //     return $results;
    // }

    // /**
    //  * おてつだい情報を登録する
    //  * 
    //  * @return DynamicProperty $results 
    //  */
    // public function insert($hand_id = null) {

    //     $results = $this->initResults();
    //     $this->transaction();
    
    //     try {
    //         $sql = $this->insertSql();
    //         $sth = $this->pdo->prepare($sql);

    //         $paramsPost = $this->request['post'];
    //         $latlng =$this->getlatlng($paramsPost);
    //         $lat = $latlng != null ? $latlng['lat'] : null;
    //         $lng = $latlng != null ? $latlng['lng'] : null;

    //         $params = array(
    //         ":user_id" => $paramsPost["u1_id"],
    //         ":hand_id" => $hand_id,
    //         ":zip_code_1" => $paramsPost["a1_zip_code_1"],
    //         ":zip_code_2" => $paramsPost["a1_zip_code_2"],
    //         ":state" => $paramsPost["a1_state"],
    //         ":city" => $paramsPost["a1_city"],
    //         ":address1" => $paramsPost["a1_address1"],
    //         ":address2" => $paramsPost["a1_address2"],
    //         ":address3" => $paramsPost["a1_address3"],
    //         ":lat" => $lat,
    //         ":lng" => $lng
    //         );

    //         // SQL実行結果                   : $sth->execute($params)
    //         // SQL実行件数                   : $sth->rowCount()
    //         // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
    //         // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
    //         $this->makeResults(
    //             $results,
    //             $sth->execute($params),
    //             $sth->rowCount(),
    //             null,
    //             $this->pdo->lastInsertId()
    //         );
    //         $this->commit();
    //     } catch (PDOException $e) {
    //         $this->rollback();
    //         $this->getErrorInfo(sprintf($this->sqlError['insert'], $this->tablePhysical, $this->tableLogical), $e);
    //     }

    //     return $results;
    // }


        
    // /**
    //  * おてつだい情報を取得する(IDをキーとして)
    //  * 
    //  * @return DynamicProperty $results 
    //  */
    // public function selectByID($id) {

    //     $results = $this->initResults();
    //     $this->transaction();

    //     try {
    //         $sql = $this->selectSql_By_ID();
    //         $sth = $this->pdo->prepare($sql);
    //         $params = array(':id' => $id);
    //         // SQL実行結果                   : $sth->execute($params)
    //         // SQL実行件数                   : $sth->rowCount()
    //         // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
    //         // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
    //         $this->makeResults(
    //             $results,
    //             $sth->execute($params),
    //             $sth->rowCount(),
    //             $sth->fetchAll(PDO::FETCH_ASSOC),
    //             $id
    //         );
    //         $this->commit();
    //     } catch (PDOException $e) {
    //         $this->rollback();
    //         $this->getErrorInfo(sprintf($this->sqlError['select'], $this->tablePhysical, $this->tableLogical), $e);
    //     }

    //     return $results;
    // }

    // private function selectSql_By_ID() {
    //     $sql = "";
    //     $sql .= "SELECT * ";
    //     $sql .= "FROM ";
    //     $sql .= $this->tablePhysical." ";
    //     $sql .= "WHERE ";
    //     $sql .= "      id        =   :id ";
    //     $sql .= "AND ";
    //     $sql .= "   del_flg = 0 ";
    //     return $this->formatHalfWidthSpace($sql);
    // }

    // /**
    //  * おてつだい情報を更新する
    //  * 
    //  * @return DynamicProperty $results 
    //  */
    // public function update() {

    //     $results = $this->initResults();
    //     $this->transaction();
    
    //     try {
    //         $sql = $this->updateSql();
    //         $sth = $this->pdo->prepare($sql);
    //         $paramsPost = $this->request['post'];

    //         $latlng =$this->getlatlng($paramsPost);
    //         $lat = $latlng != null ? $latlng['lat'] : null;
    //         $lng = $latlng != null ? $latlng['lng'] : null;

    //         $params = array(
    //             ":id" => $paramsPost["a1_id"],
    //             ":zip_code_1" => $paramsPost["a1_zip_code_1"],
    //             ":zip_code_2" => $paramsPost["a1_zip_code_2"],
    //             ":state" => $paramsPost["a1_state"],
    //             ":city" => $paramsPost["a1_city"],
    //             ":address1" => $paramsPost["a1_address1"],
    //             ":address2" => $paramsPost["a1_address2"],
    //             ":address3" => $paramsPost["a1_address3"],
    //             ":lat" => $lat,
    //             ":lng" => $lng
    //             );


    //         // $params = $this->getParams($this->request[$this->getMethodType()], 
    //         // array('id', 'fee', 'title', 'hands_type', 'body', 'photos_path'), array(':id' => $id));
    //         // SQL実行結果                   : $sth->execute($params)
    //         // SQL実行件数                   : $sth->rowCount()
    //         // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
    //         // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
    //         $this->makeResults(
    //             $results,
    //             $sth->execute($params),
    //             $sth->rowCount(),
    //             null,
    //             $paramsPost["a1_id"],
    //         );
    //         $this->commit();
    //     } catch (PDOException $e) {
    //         $this->rollback();
    //         $this->getErrorInfo(sprintf($this->sqlError['update'], $this->tablePhysical, $this->tableLogical), $e);
    //     }

    //     return $results;
    // }

    // /**
    //  * おてつだい情報を論理削除する
    //  * 
    //  * @return DynamicProperty $results 
    //  */
    // public function deleteByID($id) {

    //     $results = $this->initResults();
    //     $this->transaction();
    
    //     try {
    //         $sql = $this->deleteSql();
    //         $sth = $this->pdo->prepare($sql);
    //         $params = array(':id' => $id);
    //         // SQL実行結果                   : $sth->execute($params)
    //         // SQL実行件数                   : $sth->rowCount()
    //         // SELECT実行で取得した全行       :  $sth->fetchAll(PDO::FETCH_ASSOC)
    //         // INSERT登録された際に自動附番ID :  $this->pdo->lastInsertId()
    //         $this->makeResults(
    //             $results,
    //             $sth->execute($params),
    //             $sth->rowCount(),
    //             null,
    //             $id
    //         );
    //         $this->commit();
    //     } catch (PDOException $e) {
    //         $this->rollback();
    //         $this->getErrorInfo(sprintf($this->sqlError['delete'], $this->tablePhysical, $this->tableLogical), $e);
    //     }

    //     return $results;
    // }

    // private function selectSql_By_ID() {
    //     $sql = "";
    //     $sql .= "SELECT * ";
    //     $sql .= "FROM ";
    //     $sql .= $this->tablePhysical." ";
    //     $sql .= "WHERE ";
    //     $sql .= "      id        =   :id ";
    //     $sql .= "AND ";
    //     $sql .= "   del_flg = 0 ";
    //     return $this->formatHalfWidthSpace($sql);
    // }

    // private function selectSql_By_Params($freeword = "", $orderNo = 0) {
    //     $sql = "";
    //     $sql .= "SELECT * ";
    //     $sql .= "FROM ";
    //     $sql .= $this->tablePhysical." ";
    //     $sql .= "WHERE ";
    //     $sql .= "   del_flg = 0 ";
    //     $sql .= "AND ";
    //     $sql .= "   hands_type in (?, ?) ";
    //     $sql .= "AND ";
    //     $sql .= "   fee between ? AND ? ";

    //     if($freeword != "") {
    //         $sql .= "AND ";
    //         $sql .= "   (title LIKE ? OR body LIKE ?) ";
    //     }

    //     switch($orderNo) {
    //         case 0:// <option value="new" selected>新着</option>
    //             $sql .= "ORDER BY created_at desc ";
    //             break;
    //         case 1:// <option value="reward">報酬</option>
    //             $sql .= "ORDER BY fee desc ";
    //             break;
    //         case 2:// <option value="favorites">お気に入り数</option>
    //             $sql .= "ORDER BY ID desc ";
    //             break;
    //         case 3:// <option value="page_views">閲覧数</option>
    //             $sql .= "ORDER BY ID desc ";
    //             break;           
    //     }

    //     return $this->formatHalfWidthSpace($sql);
    // }

    // private function selectSql_By_user_id() {
    //     $sql = "";
    //     $sql .= "SELECT * ";
    //     $sql .= "FROM ";
    //     $sql .= $this->tablePhysical." ";
    //     $sql .= "WHERE ";
    //     $sql .= "   del_flg = 0 ";
    //     $sql .= "AND ";
    //     $sql .= "      user_id        =   :user_id ";
    //     return $this->formatHalfWidthSpace($sql);
    // }
    


    // private function insertSql() {
    //     $sql = "";
    //     $sql .= "INSERT INTO ".$this->tablePhysical." ";
    //     $sql .= "( ";
    //     $sql .= "     user_id ";
    //     $sql .= "   , hand_id ";
    //     $sql .= "   , zip_code_1 ";
    //     $sql .= "   , zip_code_2 ";
    //     $sql .= "   , `state` ";
    //     $sql .= "   , city ";
    //     $sql .= "   , address1 ";
    //     $sql .= "   , address2 ";
    //     $sql .= "   , address3 ";
    //     $sql .= "   , lat ";
    //     $sql .= "   , lng ";
    //     $sql .= ") ";
    //     $sql .= "VALUES ";
    //     $sql .= "( ";
    //     $sql .= "     :user_id ";
    //     $sql .= "   , :hand_id ";
    //     $sql .= "   , :zip_code_1 ";
    //     $sql .= "   , :zip_code_2 ";
    //     $sql .= "   , :state ";
    //     $sql .= "   , :city ";
    //     $sql .= "   , :address1 ";
    //     $sql .= "   , :address2 ";
    //     $sql .= "   , :address3 ";
    //     $sql .= "   , :lat ";
    //     $sql .= "   , :lng ";
    //     $sql .= ") ";
    //     return $this->formatHalfWidthSpace($sql);
    // }

    // private function updateSql() {
    //     $sql = "";
    //     $sql .= "UPDATE ".$this->tablePhysical." ";
    //     $sql .= "SET ";
    //     $sql .= "         zip_code_1          =   :zip_code_1 ";
    //     $sql .= "       , zip_code_2          =   :zip_code_2 ";
    //     $sql .= "       , `state`                 =   :state ";
    //     $sql .= "       , city                =   :city ";
    //     $sql .= "       , address1         =   :address1 ";
    //     $sql .= "       , address2         =   :address2 ";
    //     $sql .= "       , address3         =   :address3 ";
    //     $sql .= "       , lat         =   :lat ";
    //     $sql .= "       , lng         =   :lng ";
    //     $sql .= "       , updated_at          =    CURRENT_TIMESTAMP() ";
    //     $sql .= "WHERE ";
    //     $sql .= "         id                  =   :id ";
    //     return $this->formatHalfWidthSpace($sql);
    // }



    // private function deleteSql() {
    //     $sql = "";
    //     $sql .= "UPDATE ".$this->tablePhysical." ";
    //     $sql .= "SET ";
    //     $sql .= "          updated_at          =    CURRENT_TIMESTAMP()  ";
    //     $sql .= "         ,del_flg             =   1 ";
    //     $sql .= "WHERE ";
    //     $sql .= "          id                  =   :id ";
    //     return $this->formatHalfWidthSpace($sql);
    // }

}

?>