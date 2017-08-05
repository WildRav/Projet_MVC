<?php

class BaseSql
{

    private $db;

    private $table;
    private $columns;

    public function __construct()
    {
        try {
            $this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT, DB_USER, DB_PWD);
        } catch (Exception $ex) {
            die('Erreur SQL : ' . $ex->getMessage());
        }

        $this->table = strtolower(get_called_class());

        $objectVars = get_class_vars($this->table);
        $sqlVars = get_class_vars(get_class());

        $this->columns = array_diff_key($objectVars, $sqlVars);
    }

    public function save()
    {
        // Si id == -1 faire un insert dynamique
        // Sinon update dynamique
        $this->id = 1;
        $this->lastname = 'test';

        if ($this->id == -1) {
            $data = [];
            unset($this->columns['id']);

            $sqlCol = null;
            $sqlKey = null;
            foreach ($this->columns as $column => $value) {
                $data[$column] = $this->$column;
                $sqlCol .= ',' . $column;
                $sqlKey .= ',:' . $column;
            }
            $sqlCol = ltrim($sqlCol, ',');
            $sqlKey = ltrim($sqlKey, ',');

            $query = $this->db->prepare(
                "INSERT INTO " . $this->table .
                " (" . $sqlCol . ")" .
                " VALUES " .
                "(" . $sqlKey . ");"
            );

            $query->execute($data);
        } else {

            $data = [];
            $sqlSet = [];
            foreach ($this->columns as $column => $key) {
                $data[$column] = $this->$column;
                $sqlSet[] = $column . '=:' . $column;
            }

            $query = $this->db->prepare(
                "UPDATE " . $this->table . ' SET date_updated = sysdate(), ' . implode(',',
                    $sqlSet) . ' WHERE id = :id;'
            );
            $query->execute($data);
        }
    }

    // $condition = [ "id" => 3 ]
    // La fonction va alimenter l'objet suite a une requete sql
    // Attention la requete ne doit retourner qu'une seule valeur /!\
    // Si plusieurs valeurs => Die ou laisse l'objet vide
    public function populatePerso($condition)
    {
        $conditionQuery = '';
        foreach ($condition as $field => $value) {
            $conditionQuery .= $field . ' = :' . $field . ' AND ';
        }
        $conditionQuery = trim($conditionQuery, ' AND ');

        $query = $this->db->prepare(
            'SELECT * FROM ' . $this->table . ' WHERE ' . $conditionQuery
        );
        $query->execute($condition);

        if ($query->rowCount() > 1) {
            die('Trop de resultat');
        }

        $data = $query->fetch(PDO::FETCH_ASSOC);
        if (empty($data)) {
            return false;
        }

        foreach ($data as $name => $value) {
            $this->$name = $value;
        }
    }

    public function populate(array $search = [])
    {
        $query = $this->getOneBy($search, true);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->table);
        $object = $query->fetch();
        return $object;
    }

    public function getOneBy(array $search, $returnQuery = false)
    {
        foreach ($search as $key => $value) {
            $where[] = $key . '=:' . $key;
        }
        $query = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE ' . implode(' AND ', $where));

        $query->execute($search);
        if ($returnQuery) {
            return $query;
        }
        return $query->fetch(PDO::FETCH_ASSOC);
    }

}
