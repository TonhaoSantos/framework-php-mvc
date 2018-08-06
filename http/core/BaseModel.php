<?php

namespace Core;

use PDO;

abstract class BaseModel
{
    //Vai se objeto da conexão com banco de dados
    protected $pdo;
    //Tabela que vai trabalhar
    protected $table;

    public function __construct(PDO $pdo)
    {   
        //objeto de conexão
        $this->pdo = $pdo;
    }
    
    //Retorna todos os dados da tabela
    public function all()
    {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }
    
    //Retorna um dado da tabela
    public function find($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id=:id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result;
    }
    
    //inserindo dados na tabela
    public function create(array $externalData)
    {
        $data = $this->prepareDataInsert($externalData);
        $query = "INSERT INTO {$this->table} ({$data[0]}) VALUES ({$data[1]})";
        $stmt = $this->pdo->prepare($query);
        for($i = 0; $i < count($data[2]); $i++){
            $stmt->bindValue("{$data[2][$i]}", $data[3][$i]);
        }
        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
    }
    
    //Setando dados da função create
    private function prepareDataInsert(array $data)
    {
        //Chave que representa os campos das tabelas (Campos)
        $strKeys = "";
        //Chave que representa os dados que sera passado (Values)
        $strBinds = "";
        //Dados passados
        $binds = [];
        //Valores reais
        $values = [];
        
        foreach ($data as $key => $value) {
            //Montando campos da tabela
            $strKeys = "{$strKeys},{$key}";
            //Montando bind
            $strBinds = "{$strBinds},:{$key}";

            //Setando bind e values individual
            $binds[] = ":{$key}";
            $values[] = $value;
        }

        //Removendo ,
        $strKeys = substr($strKeys, 1);
        $strBinds = substr($strBinds, 1);

        //Retornando array
        return [$strKeys, $strBinds, $binds, $values];
    }

    //Atualizando
    public function update(array $externalData, $id)
    {
        $data = $this->prepareDataUpdate($externalData);
        $query = "UPDATE {$this->table} SET {$data[0]} WHERE id=:id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":id", $id);
        for($i = 0; $i < count($data[1]); $i++){
            $stmt->bindValue("{$data[1][$i]}", $data[2][$i]);
        }        
        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
        
    }
    
    //Setando dados da função update
    private function prepareDataUpdate(array $data)
    {
        //Chave que representa os campos das tabelas (Campos) e os dados que sera passado (Values)
        $strKeysAndBinds = "";
        //Dados passados
        $binds = [];
        //Valores reais
        $values = [];
        
        foreach ($data as $key => $value) {
            //Montando campos da tabela
            $strKeysAndBinds = "{$strKeysAndBinds},{$key}=:{$key}";

            //Setando bind e values individual
            $binds[] = ":{$key}";
            $values[] = $value;
        }

        //Removendo ,
        $strKeysAndBinds = substr($strKeysAndBinds, 1);

        //Retornando array
        return [$strKeysAndBinds, $binds, $values];
    }

    //Deletando dados
    public function delete($id)
    { 
        $query = "DELETE FROM {$this->table} WHERE id=:id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":id", $id);
        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
    }
}