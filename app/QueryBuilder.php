<?php

namespace App;

use Aura\SqlQuery\QueryFactory;
use JasonGrimes\Paginator;
use PDO;

class QueryBuilder
{
    private $pdo;
    private $queryFactory;

    public function __construct(PDO $pdo,QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
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
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
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
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
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
    public function findRelation($id, $table, $foreign_key)
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $select = $this->queryFactory->newSelect();
        $select->cols([
            '*'
        ])->from($table)->where("$foreign_key=:id")->bindValues([
            'id' => $id,
        ]);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function uploadImage($img, $id)
    {

        $allowedFiletypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($img["type"], $allowedFiletypes)) {
            print_r('Можно загружать файлы только в формате: jpg, png');
            exit;
        }

        $fileName = $img['name'];
        $target_dir = __DIR__ . '/../img/demo/avatars/';
        $target_file = $target_dir . basename($fileName);
        if (!move_uploaded_file($img['tmp_name'], $target_file)) {
            print_r('Ошибка при перемещении файла!');
            exit;
        }

        $update = $this->queryFactory->newUpdate();
        $update->table('credentials')->cols(['image' => $fileName])->where('user_id=:id')->bindValue('id', $id);
        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());


    }

    public function getPage($table,$itemsPerPage)
    {
        $currentPage = $_GET['page'] ?? 1;


        $select = $this->queryFactory->newSelect();
        $select->cols([
            '*'
        ])->from($table)->setPaging($itemsPerPage)->page($currentPage);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

}

