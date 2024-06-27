<?php
namespace App;

use PDO;
use PhpParser\Node\Stmt\TraitUseAdaptation\Alias;

class QueryBuilder {


    private $from;
    private $order = [];
    private $limit;

    private $offset;
    private $where;

    private $fileds = ["*"];

    private $params = [];
    public function __construct() {
        
    }

   public function orderBy(string $key, string $direction ) :self
    {
        $value = strtoupper($direction);
        
            if (!in_array($value, ['ASC', 'DESC'])) {
               $this->order[] = $key;
            } else {
                 $this->order[] =  "$key $direction";
            }
                return $this;


    }
     public function offset(INT $offset ): self
    { 
        $this->offset = $offset;
        return $this;
    }

     public function limit(INT $limit ): self
    { 
        $this->limit = $limit;
        return $this;
    }

    

    public function from(string $tableName, string $alias = null ) :self
    {
             $this->from =  $alias === null ?  $tableName : "$tableName $alias";
             return $this;

    }

    public function page(int $page ) :self
    {
        return $this->offset($this->limit * ($page - 1));

    }

    public function where(string $condition): self 
    {
        $this->where = $condition;

        return  $this;
    }


    public function setParam(string $key , $value) : self 
    {
        $this->params[] = $value;
        return  $this;
    }

     public function fetch(PDO $pdo , string $fileds) : ?string 
     {

        $stmt = $pdo->prepare($this->toSQL());
        $stmt->execute($this->params);
        $result = $stmt->fetch()[$fileds];
         if ($result === false) {
          return null;
        }
        return $result[$fileds] ?? null;
       
    }

    public function count(PDO $pdo) : int 
    {
        // $query = clone $this;
        return (int)(clone $this)->select('COUNT(id) count')->fetch($pdo, 'count');
       
    }

    public function select ( ...$selectedValues) {

        if (is_array($selectedValues[0])) {
            $selectedValues = $selectedValues[0];
        }
        if ($this->fileds === ['*']) {
                $this->fileds = $selectedValues;
        } else {
            $this->fileds = array_merge($this->fileds, $selectedValues);
        }
        
        return $this;

    }
    

    public function toSQL () : string 
    {
        $fields = implode(', ' ,$this->fileds);

            $sql = "SELECT $fields FROM {$this->from}";

            if ($this->where) {
                $sql .= " WHERE " .  $this->where;
            }

            if (!empty($this->order)) {
                $sql .= " ORDER BY " . implode(', ', $this->order);
            }
             if ($this->limit > 0) {
                $sql .= " LIMIT " . $this->limit;
            }
            if ($this->offset !== null) {
                $sql .= " OFFSET " . $this->offset;
            }

            return $sql;
    }

}

// $query = new QueryBuilder();

// var_dump(
//     $query->select("id", "name")
//             ->from("users")
//             ->select('product')
// );


