<?php
class Users_Modelo
{
    private $db;
    private $users;
    private $ocupations;

    public function __construct()
    {
        require_once("Modelo/Conexion.php");
        $this->db = Conectar::conexion();

    }

    public function get_users()
    {
        $sql = "SELECT * FROM users";
        $consulta = $this->db->query($sql);

        while($registro = $consulta->fetch_assoc())
        {
            $this->users[] = $registro;
        }
        return $this->users;
    }

    public function get_user($id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("i", $id);
        $consulta->execute();
    
        $result = $consulta->get_result();
    
        if ($result->num_rows == 1) {
            return $result->fetch_assoc(); // Devuelve el registro como array asociativo
        } else {
            return null; // Manejar el caso donde no se encontró la película
        }
    }

    public function get_last_inserted_id()
    {
        return $this->db->insert_id;
    }

    public function update_user_pic($userId, $pic)
    {
        $sql = "UPDATE users SET pic = ? WHERE id = ?";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("si", $pic, $userId);

        return $consulta->execute();
    }

    public function get_ocupations()
    {
        $sql = "SELECT COLUMN_TYPE 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'users' AND COLUMN_NAME = 'ocupacion'";

        $consulta = $this->db->query($sql);

        if ($consulta->num_rows > 0) {
            $row = $consulta->fetch_assoc();
            $enum_str = $row['COLUMN_TYPE'];
            preg_match('/enum\((.*)\)$/', $enum_str, $matches);
            $this->ocupations = str_getcsv($matches[1], ',', "'");
        }

        return $this->ocupations;
    }

    public function user_exists($name)
    {
        $sql = "SELECT COUNT(*) as count FROM users WHERE name = ?";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("s", $name);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $row = $resultado->fetch_assoc();
        
        return $row['count'] > 0;
    }

    public function register($name, $age, $gender, $ocupation, $pic, $password)
    {
        $sql = "INSERT INTO users(name, edad, sex, ocupacion, pic, passwd) VALUES (?, ?, ?, ?, ?, ?)";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("sissss", $name, $age, $gender, $ocupation, $pic, $password);

        if($consulta->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function login($name, $password)
    {
        $sql = "SELECT * FROM users WHERE name = ? AND passwd = ?";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("ss", $name, $password);
        $consulta->execute();
        $result = $consulta->get_result();

        if($result->num_rows == 1){
            $row = $result->fetch_assoc();
            
            $sql = "SELECT is_admin FROM users_admin WHERE user_id = ?";
            $consultaAdmin = $this->db->prepare($sql);
            $consultaAdmin->bind_param("i", $row['id']);
            $consultaAdmin->execute();
            $resultAdmin = $consultaAdmin->get_result();

            if($resultAdmin->num_rows == 1)
            {
                $adminRow = $resultAdmin->fetch_assoc();
                $_SESSION['is_admin'] = $adminRow['is_admin'];
            }else{
                $_SESSION['is_admin'] = 0;
            }
            
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_pic'] = $row['pic'];

            return true;
        }else{
            return false;
        }
    }

    public function update_user($userId, $name, $age, $gender, $ocupation, $pic, $password)
    {
        $sql = "UPDATE users SET name = COALESCE(?, name), edad = COALESCE(?, edad), sex = COALESCE(?, sex), ocupacion = COALESCE(?, ocupacion), pic = COALESCE(?, pic), passwd = COALESCE(?, passwd) WHERE id = ?";
        $consulta = $this->db->prepare($sql);
        $consulta->bind_param("sissssi", $name, $age, $gender, $ocupation, $pic, $password, $userId);

        if($consulta->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function delete_user($userId)
    {
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);

        $stmt->bind_param("i", $userId);
        $succes = $stmt->execute();

        if($succes){
            return true;
        }else{
            error_log("Error al eliminar al usuario con ID : " . $userId . " " . $stmt->error);
            return false;
        }
    }

    public function get_all_users()
    {
        $sql = "SELECT * FROM users ORDER BY id DESC";
        $consulta = $this->db->query($sql);

        while($registro = $consulta->fetch_assoc())
        {
            $this->users[] = $registro;
        }
        return $this->users;
    }

    public function search_users($query)
    {
        $query = '%' . $query . '%';
        $sql = "SELECT id, name, edad, ocupacion FROM users WHERE name LIKE ? ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $users = array();
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        console_log($users);
        return $users;
    }
}
?>