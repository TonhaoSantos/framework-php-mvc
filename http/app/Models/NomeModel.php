<?php
namespace app\Models;

//Usando BaseModel
use Core\BaseModel;

class NomeModel extends BaseModel
{
    //Nome das tabelas igual a que esta na base
    protected $table = "TB_NOT_NOME_TABELA";
    protected $table2 = "TB_NTX_NOME_TABELA_X";

    //Metodo que recebe as regras de validação
    public function nomeRules()
    {
      /*
       * 'atributoPassado' => 'TipoValidação'
       *
       * Tipos:
       *       min:quantidadeEmNumero, max:quantidadeEmNumero e unique
       *       required, email, float e int
       * Para separação utilizar o |
       */
		  $rules = [
		 	    'email' => 'required|email',
			    'senha' => 'min:8|uppercaseletter|lowercaseletter|hasnumber|hassymbol'
      ];

      return $rules;
    }


    /*
     * Categoria
     */
    /*
     * Retorna
     */
    public function nomeQualquer($valueParametro1, $valueParametro2, ...)
    {
        $query = "SELECT * FROM {$this->table} WHERE usu_email=:email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":email2", $valueParametro1);
        $stmt->bindValue(":email1", $valueParametro2);
        $stmt->execute();
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result;
    }

    /*
     * Insere
     */
    public function nomeQualquer($valueParametro1, $valueParametro2, ...)
    {
        $query = "INSERT INTO {$this->table2} (campo1, campo2) VALUES (:valueParametro1, :valueParametro2)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":valueParametro1", $valueParametro1);
        $stmt->bindValue(":valueParametro2", $valueParametro2);
        $stmt->execute();
        $result = $stmt;
        $stmt->closeCursor();
        return $result;
    }

    /*
     * Atualiza
     */
    public function nomeQualquer($valueParametro1, $valueParametro2, ...)
    {
        $query = "UPDATE {$this->table2} SET campo1=:valueParametro1, campo2=:valueParametro2 WHERE campo3=:valueParametro3 AND campo4=:valueParametro4";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":valueParametro1", $valueParametro1);
        $stmt->bindValue(":valueParametro2", $valueParametro2);
        $stmt->bindValue(":valueParametro3", $valueParametro3);
        $stmt->bindValue(":valueParametro4", $valueParametro4);
        $stmt->execute();
        $result = $stmt;
        $stmt->closeCursor();
        return $result;
    }

    /*
     * Deleta
     */
    public function nomeQualquer($valueParametro1, ...)
    {
        $query = "DELETE FROM {$this->table2} WHERE campo1=:$valueParametro1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(":$valueParametro1", $valueParametro1);
        $stmt->execute();
        $result = $stmt;
        $stmt->closeCursor();
        return $result;
    }
}
