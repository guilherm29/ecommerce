<?php
namespace Tagui\Model;
use \Tagui\DB\Sql;
use \Tagui\Model;

class Category extends Model{

    

    public static function listAll(){

        $sql = new Sql();
        return $sql -> select ("SELECT * FROM tb_categories  ORDER BY descategory");
        
    }
 
    public function save(){

        $sql = new Sql();
        $result = $sql -> select("CALL sp_categories_save(:idcategory, :descategory)", array(
           ":idcategory" => $this -> getidcategory(),
           ":descategory" =>$this -> getdescategory(),
        ));

        $this-> setData($result[0]);

    }

    public function get($idcategory){

        $sql = new Sql();

        $results = $sql-> select("select * from tb_categories  where idcategory = :idcategory", 
        array(

            ":idcategory"=> $idcategory
        ));//

        $this->setData($results[0]);
    }

public function delete(){

    $sql = new Sql();
    $sql->query ("delete from tb_categories where idcategory= :idcategory", [

        ":idcategory" => $this->getidcategory()
    ]);
}



}//fim
?>