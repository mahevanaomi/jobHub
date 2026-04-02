<?php

declare(strict_types=1);

require_once 'database.php';

class categorieDB
{
    private database $bd;

    public function __construct()
    {
        $this->bd = new database();
    }

    public function readall(): array
    {
        $sql = 'SELECT id AS id_categorie, name AS nom, description FROM categories ORDER BY name ASC';
        $req = $this->bd->request($sql);
        return $this->bd->recover($req, false);
    }

    public function readcategorie(int $idcategorie): mixed
    {
        $sql = 'SELECT id AS id_categorie, name AS nom, description FROM categories WHERE id = ?';
        $req = $this->bd->request($sql, [$idcategorie]);
        return $this->bd->recover($req, true);
    }
}
