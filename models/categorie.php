
<?php
    require_once 'database.php';

    class categorieDB{

        private $bd;
        public function __construct(){
            $this->bd= new database();
        }
        
        public function readall(){
            $sql="SELECT * FROM categorie ORDER BY id_categorie DESC";
            $req= $this->bd->request($sql);
            $datas=$this->bd->recover($req, false);
            return $datas;
        }

        public function readcategorie($idcategorie){
            $sql="SELECT * FROM categorie WHERE id_categorie=?";
            $params= array($idcategorie);
            $req= $this->bd->request($sql,$params);
            $datas=$this->bd->recover($req, true);
            return $datas;
        }


        
    }

 ?>