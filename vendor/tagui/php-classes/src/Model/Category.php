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
       
    Category :: updatefile();

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

    Catefory :: updatefile();
}

public static function updatefile(){
    $categories = Category::listAll();
    $html = [];
    foreach ($categories as $row) {
       array_push($html, '<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
    }

    file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . 
    "categories-menu.html",implode('',$html));
}


}//fim
?>