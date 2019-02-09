<?php
namespace Tagui\Model;
use \Tagui\DB\Sql;
use \Tagui\Model;
use \Tagui\User;

class Cart extends Model{

    const SESSION = "Cart";

    public static function getFromSession()
    {
        $cart = new Cart();

        if(isset($_SESSION[Cart :: SESSION]) && (int)$_SESSION[Cart :: SESSION]['idcart'] > 0){

            $cart -> get((int)$_SESSION[Cart :: SESSION]['idcart']);
        }else {
            $cart -> getfromSessionID();
            if(!(int)$cart->getidcart() >0){
                $data = [
                    'dessessionid' => session_id()
                ];
                if(User :: checklogin(false)){
                    $user = User::getFromSession();
                    $data['iduser'] = $user->getiduser();
                }
               $cart->setData($data);
               
               $cart->save();
               $cart-> setToSession();
                
            }
        }
        return $cart;
    }

    public function setToSession()
    {
        $_SESSION[Cart :: SESSION] = $this->getValues();
    }

    public function get(int $idcart)
    {
        $sql = new Sql();
        $result = $sql -> select("select * from tb_carts where idcart = :idcart", [
            ':idcart' => $idcart
        ]);

        if ( count ($result) >0) {
        $this->setData($result[0]);
        }
    }
    public function getfromSessionID()
    {
        $sql = new Sql();
        $result = $sql -> select("select * from tb_carts where dessessionid = :dessessionid", [
            ':dessessionid' => session_id()
        ]);
        if ( count ($result) >0) {
            $this->setData($result[0]);
            }
    }

    public function save(){

        $sql = new Sql();
        $result = $sql -> select("call sp_carts_save(:idcart, :dessessionid, :iduser, :deszipcode, :vlfreight, :nrdays)",[
            ":idcart" => $this->getidcart(),
            ":dessessionid"=> $this->getdessessionid(),
            ":iduser"=> $this->getiduser(),
            ":deszipcode"=> $this->getvlfreight(),
            ":vlfreight"=> $this->getvlfreight(),
            ":nrdays"=> $this->getnrdays()
        ]);
        
        $this->setData($result[0]);
    }


    public function addProduct(Product $product)
    {
        $sql = new Sql();
        $sql->query("insert into tb_cartsproducts (idcart, idproduct) values (:idcart, :idproduct)",[
            ':idcart' => $this->getidcart(),
            ':idproduct' => $product->getidproduct()
        ]);
    }

    public function removeProduct(Product $product, $all = false)
    {
        $sql = new Sql();
        if($all){

            $sql->query("update tb_cartsproducts set dtremoved = now() where idcart= :idcart and idproduct = :idproduct and
            dtremoved is null" ,[
                ':idcart' => $this->getidcart(),
                ':idproduct' => $product->getidproduct()
        ]);
        }else {
            $sql->query("update tb_cartsproducts set dtremoved = now() where idcart= :idcart and idproduct = :idproduct and 
            dtremoved is null limit 1",[
                ':idcart' => $this->getidcart(),
                ':idproduct' => $product->getidproduct()
        ]);
        }
    }

    public function getProducts()
    {
        $sql = new Sql();

        return Product :: checklist( $sql->select("
        SELECT b.idproduct, b.desproduct , b.vlprice, b.vlwidth, b.vlheight, b.vllength, b.vlweight, b.desurl, COUNT(*) AS nrqtd, SUM(b.vlprice) AS vltotal 
			FROM tb_cartsproducts a 
			INNER JOIN tb_products b ON a.idproduct = b.idproduct 
			WHERE a.idcart = :idcart AND a.dtremoved IS NULL 
			GROUP BY b.idproduct, b.desproduct , b.vlprice, b.vlwidth, b.vlheight, b.vllength, b.vlweight, b.desurl 
			ORDER BY b.desproduct", [
            'idcart' => $this->getidcart()
        ]));
    }
    
}//fim
?>