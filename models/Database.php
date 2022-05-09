<?php

class Database {
    public $con;

    public function __construct($id){
    
        $options = [
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_FOUND_ROWS => true
          ];

        try{
            switch($id){
                case 0:
                    $this->con = new PDO("mysql:dbname=lexten_web;host=127.0.0.1;charset=utf8mb4", "root", "");
                break;
                case 1:
                    $this->con = new PDO("mysql:dbname=lexten_server;host=127.0.0.1;charset=utf8mb4", "root", "");
                break;
                case 2:
                    $this->con = new PDO("mysql:dbname=sbans_318141;host=mysql.fakaheda.eu;charset=utf8mb4", "sbans_318141", "CSYGYMCH");
                break;
            }
        } catch (Exception $e){
            echo $e->getMessage();
            exit();
        }

    }

    public function get_results($query, $params){
                
        $q = $this->con->prepare($query);
        
        foreach($params as $key=>$item){
            if(gettype($item) == "string") $q->bindValue($key, $item, PDO::PARAM_STR);
            else if(gettype($item) == "integer") $q->bindValue($key, $item, PDO::PARAM_INT);
            else if(gettype($item) == "double") $q->bindValue($key, $item, PDO::PARAM_STR);
        }

        $q->execute();

        $results = $q->fetchAll();

        if(!$results) return ["status"=>false, "errors"=>"Žádné řádky nenalezeny."];

        return ["status"=>true, "results"=>$results, "num_rows"=>count($results)];
    }

    public function get_row($query, $params){
        $q = $this->con->prepare($query);

        $q->execute($params);

        $result = $q->fetch();

        if(!$result) return ["status"=>false, "errors"=>"Žádné řádky nenalezeny."];

        return ["status"=>true, "result"=>$result];
    }

    public function prepare($command, $columns){
        $results = [];
        $prep = $this->con->prepare($command);

        foreach($columns as $item){
            $prep->bind_param("s", $this->escape($item));
        }

        $prep->execute();

        $result = $prep->get_result();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }

        return $results;
    }

    public function insert($table_name, $columns = [], $data = []){
        $values = "";

        for($i = 0; $i < count($data); $i++){
            if($i != count($data)-1){
                $values.="?,";
            }
            else{
                $values.="?";
            }
        }

        $q = $this->con->prepare("INSERT INTO `$table_name` (".implode(",", $columns).") VALUES($values)");

        $q->execute($data);
        
        return $this->con->lastInsertId();
    }

    public function update($table_name, $set, $params, $where){
        $q = $this->con->prepare("UPDATE `$table_name` SET $set WHERE $where");

        
        $q->execute($params);

        if($q->rowCount()) return true;
        else return false;
    }

    public function delete($table, $where, $params){
        $q = $this->con->prepare("DELETE FROM `$table` WHERE $where");
    
        $q->execute($params);

        return true;
    }

    public function multi_query($query){
        $mx = true;
        $conn = $this->con;
        $conn->beginTransaction();
        $last = "";
        $cmd = "";
        try{
            foreach ($query AS $key=>$item) {
                $last = $key;
                $stmt = $conn->prepare($item["query"]);
                $stmt->execute($item["params"]);
                $result = $stmt->rowCount();
                if($result == 0){
                    $mx = false;
                }

                $stmt->closeCursor();
            }
            if($mx == true)
                $conn->commit();
            else
                $conn->rollBack();
        } catch (Exception $e) {
            $conn->rollBack();
            throw new Exception($last.' - '.json_encode($query[$last]["params"]).': '.$e->getMessage());
        }
        return $mx;
    }

    /*public function __destruct()
    {
        $this->con->query('KILL CONNECTION_ID()');
        $this->con = null;
    }*/

}

?>