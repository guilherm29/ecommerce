<?php
namespace Tagui\Model;
use \Tagui\DB\Sql;
use \Tagui\Model;

class Address extends Model{ 

    public static function getCep($nrcep)
    {

    $nrcep = str_replace("-",'', $nrcep);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://viacep.com.br/ws/$nrcep/json/");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = json_decode(curl_exec($ch), true);
        
        curl_close($ch);

        return $data;
        
    }

    public  function loadFromCEP($nrcep){

        $data = Adress :: getCEP($nrcep);

        if(isset($data['logradouro']) && $data['logradouro']) {

            $this -> setdesaddress($data['logradouro']);
            $this -> setdescomplement($data['complemento']);
            $this -> setdesdistrict($data['bairro']);
            $this -> setdescity($data['localidade']);
            $this -> setdescountry('Brasil');
            $this -> setnrzipcode($nrcep);

        }
    }
  

}//fim
?>