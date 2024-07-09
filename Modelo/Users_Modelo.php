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
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_id'] = $row['id'];
            return true;
        }else{
            return false;
        }

    }
}
?>