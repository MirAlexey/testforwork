<?php


namespace myNs;


class Data
{
    public $db;
    function __construct()
    {
      $this->db = mysqli_connect("localhost", "root", "" , "web");
      //if (! @$this->db->query('SELECT COUNT(*)+1 FROM dataTable')){
            $this->db->query("CREATE TABLE IF NOT EXISTS dataTable(
                    id int PRIMARY KEY AUTO_INCREMENT,
					data TEXT,
					number int,
					param float,
					date TIMESTAMP              
				)");
       // }
    }
    public function getData(){
        $result = $this->db->query("SELECT * FROM dataTable");
        $res = $result->fetch_all();
        return $res;
    }
    public function getHead(){
        $result = $this->db->query("DESC dataTable");
        $res = $result->fetch_all();
        return $res;
    }
    public function AddData($data, $number, $param){
        $result = $this->db->prepare("INSERT INTO dataTable (data, number, param) VALUE ( ? , ? ,?);");
        $result->bind_param("sid", $data, $number, $param);
        if(!$result->execute()){
            return array('result'=> false, 'data'=> 'Ошибка:('.$result->errno.')'. $result->error);
        }else{
            $result = $this->db->query('SELECT * FROM dataTable WHERE id='.$this->db->insert_id);
            return array('result'=> true, 'data'=> $result->fetch_all());
        }
    }
    public function updateData($id, $data, $number, $param){
        $result = $this->db->prepare("UPDATE dataTable SET data=?, number = ?, param = ? WHERE id = ?");
        $result->bind_param("sidi", $data, $number, $param, $id);
        if(!$result->execute()){
            return array('result'=> false, 'data'=> 'Ошибка:('.$result->errno.')'. $result->error);
        }else{
            $result = $this->db->query('SELECT * FROM dataTable WHERE id='.$id);
            return array('result'=> true, 'data'=> $result->fetch_all());
        }
    }
    public function deleteData($id){
        $result = $this->db->prepare("DELETE FROM dataTable WHERE id= ?");
        $result->bind_param("i", $id);
        if(!$result->execute()){
            return array('result'=> false, 'data'=> 'Ошибка:('.$result->errno.')'. $result->error);
        }else{
            return array('result'=> true, 'data'=> 'Успех');
        }

    }

}