<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/database.php';

class database
{
    public function chaineConnexion(): PDO
    {
        return db();
    }

    public function request(string $sql, ?array $params = null): PDOStatement
    {
        $statement = $this->chaineConnexion()->prepare($sql);
        $statement->execute($params ?? []);
        return $statement;
    }

    public function recover(PDOStatement $statement, bool $one = true): mixed
    {
        $statement->setFetchMode(PDO::FETCH_OBJ);
        return $one ? $statement->fetch() : $statement->fetchAll();
    }
}
