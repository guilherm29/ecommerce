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
    
}//fim
?>