<?php

namespace App;

use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder
{
    private $pdo;
    private $queryFactory;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=127.0.0.1;dbname=marlin", "marlin", "marlin");
        $this->queryFactory = new QueryFactory('mysql');
    }

    public function getAll($table)
    {

        $select = $this->queryFactory->newSelect();
        $select->cols([
            '*'
        ])->from($table);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function insert($data, $table)
    {
        $insert = $this->queryFactory->newInsert();
        $insert->into($table)->cols($data);
        $sth = $this->pdo->prepare($insert->getStatement());
        $sth->execute($insert->getBindValues());
    }

    public function update($data, $id, $table)
    {
        $update = $this->queryFactory->newUpdate();
        $update->table($table)->cols($data)->where('id=:id')->bindValue('id', $id);
        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    public function findOne($id, $table)
    {
        $id=$_GET['id'];
        $select = $this->queryFactory->newSelect();
        $select->cols([
            '*'
        ])->from($table)->where('id=:id')->bindValue('id', $id);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function delete($id, $table)
    {
        $delete = $this->queryFactory->newDelete();
        $delete->from($table)->where('id=:id')->bindValue('id', $id);
        $sth = $this->pdo->prepare($delete->getStatement());
        $sth->execute($delete->getBindValues());

    }

//    public function findOneWithRelation($id, $table, $joinTable, $foreign_id)
//    {
//        $select = $this->queryFactory->newSelect();
//        $select->cols([
//            '*'
//        ])->from($table)->where('id=:id')->join('LEFT', ":join AS join", 'id=join.:foreign_id')
//            ->bindValues([
//                'id' => $id,
//                'join' => $joinTable,
//                'foreign_id' => $foreign_id
//            ]);
//        $sth = $this->pdo->prepare($select->getStatement());
//        $sth->execute($select->getBindValues());
//        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
//        return $result;
//    }
    public function findRelation($id, $table,$foreign_key)
    {
        $id=$_GET['id'];
        $select = $this->queryFactory->newSelect();
        $select->cols([
            '*'
        ])->from($table)->where(':foreign_key=:id')->bindValues([
            'id'=> $id,
            'foreign_key' => $foreign_key,
        ]);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}

