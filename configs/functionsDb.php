<?php

class Database
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function get($table, $limit = 100, $offset = 0)
    {
        $sql = "SELECT * FROM $table LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        
        // Asignar los parámetros del límite y el desplazamiento
        $stmt->bind_param('ii', $limit, $offset);
    
        if (!$stmt->execute()) {
            return [];
        }
    
        $result = $stmt->get_result();
    
        // Obtener los datos de forma incremental
        if ($result->num_rows > 0) {
            return $result;
        }
        return [];
    }
    
    // TODO implementar la función insertDB
    public function insertDB($table, $data)
    {
        $fields = implode(',', array_keys($data));
        $values = '"';
        $values .= implode('","', array_values($data));
        $values .= '"';

        $query = "INSERT INTO $table (" . $fields . ') VALUES (' . $values . ')';
        $this->conn->query($query);

        return $this->conn->insert_id;
    }

    // TODO implementar la función updateDB
    public function updateDB($table, $id, $data)
    {
        $query = "UPDATE $table SET ";
        foreach ($data as $key => $value) {
            $query .= "$key = '$value'";
            if (sizeof($data) > 1 && $key != array_key_last($data)) {
                $query .= " , ";
            }
        }

        $query .= ' WHERE id = ' . $id;

        $this->conn->query($query);

        if (!$this->conn->affected_rows) {
            return 0;
        }

        return $this->conn->affected_rows;
    }

    public function delete($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id_producto = ?";

        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conn->error);
        }
    
        // Preparo la consulta
        $stmt->bind_param('i', $id);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
