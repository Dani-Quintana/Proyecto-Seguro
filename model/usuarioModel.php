<?php
require_once __DIR__ . '/../config/database.php';

class UsuarioModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($usuario, $password) {
        $query = "SELECT * FROM usuario WHERE usuario = :usuario AND estado = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function updatePassword($usuario, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE usuario SET password = :password WHERE usuario = :usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':usuario', $usuario);
        return $stmt->execute();
    }

    public function setPrimerLogin($usuario, $value) {
        $query = "UPDATE usuario SET primer_login = :value WHERE usuario = :usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':value', $value, PDO::PARAM_INT);
        $stmt->bindParam(':usuario', $usuario);
        return $stmt->execute();
    }

    public function getUserByUsername($usuario) {
        $query = "SELECT * FROM usuario WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByDPI($dpi) {
        $query = "SELECT u.*, r.nombre AS rol_nombre FROM usuario u 
                  INNER JOIN rol r ON u.id_rol = r.id_rol 
                  WHERE u.DPI = :dpi AND u.estado = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dpi", $dpi);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePasswordAndFirstLogin($id_usuario, $hashedPassword) {
        $query = "UPDATE usuario SET password = :password, primer_login = 1 WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":id_usuario", $id_usuario);
        return $stmt->execute();
    }

    /**
     * Obtiene todos los usuarios con los campos necesarios para la vista de usuarios
     */
    public function getAllUsers() {
        $query = "
            SELECT 
                u.DPI, 
                u.nombres, 
                u.apellidos, 
                u.usuario, 
                u.telefono, 
                u.correo, 
                r.nombre AS rol 
            FROM usuario u
            INNER JOIN rol r ON u.id_rol = r.id_rol
            WHERE u.estado = 1
            ORDER BY u.nombres ASC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsers($search) {
        $query = "
            SELECT 
                u.DPI, 
                u.nombres, 
                u.apellidos, 
                u.usuario, 
                u.telefono, 
                u.correo, 
                r.nombre AS rol 
            FROM usuario u
            INNER JOIN rol r ON u.id_rol = r.id_rol
            WHERE u.estado = 1 AND (
                u.DPI LIKE :search OR
                u.nombres LIKE :search OR
                u.apellidos LIKE :search OR
                u.usuario LIKE :search OR
                u.telefono LIKE :search OR
                u.correo LIKE :search OR
                r.nombre LIKE :search
            )
            ORDER BY u.nombres ASC
        ";
        $stmt = $this->conn->prepare($query);
        $likeSearch = "%".$search."%";
        $stmt->bindParam(':search', $likeSearch);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los roles activos
     */
    public function getAllRoles() {
        $query = "SELECT id_rol, nombre FROM rol WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza los datos de un usuario
     */
    public function updateUser($datos) {
        $query = "UPDATE usuario SET 
                    DPI = :DPI,
                    usuario = :usuario,
                    nombres = :nombres,
                    apellidos = :apellidos,
                    telefono = :telefono,
                    correo = :correo,
                    id_rol = :id_rol
                  WHERE id_usuario = :id_usuario";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':DPI', $datos['DPI']);
        $stmt->bindParam(':usuario', $datos['usuario']);
        $stmt->bindParam(':nombres', $datos['nombres']);
        $stmt->bindParam(':apellidos', $datos['apellidos']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':correo', $datos['correo']);
        $stmt->bindParam(':id_rol', $datos['id_rol']);
        $stmt->bindParam(':id_usuario', $datos['id_usuario']);
        
        return $stmt->execute();
    }

    /**
     * Elimina un usuario (cambio de estado)
     */
    public function deleteUser($id_usuario) {
        $query = "UPDATE usuario SET estado = 0 WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute();
    }

    /**
     * Crea un nuevo usuario
     */
    public function createUser($datos, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO usuario (DPI, usuario, password, nombres, apellidos, telefono, correo, id_rol, estado, primer_login) 
                  VALUES (:DPI, :usuario, :password, :nombres, :apellidos, :telefono, :correo, :id_rol, 1, 0)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':DPI', $datos['DPI']);
        $stmt->bindParam(':usuario', $datos['usuario']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':nombres', $datos['nombres']);
        $stmt->bindParam(':apellidos', $datos['apellidos']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':correo', $datos['correo']);
        $stmt->bindParam(':id_rol', $datos['id_rol']);
        
        return $stmt->execute();
    }

    /**
     * Actualiza el perfil de un usuario (para ajustes personales)
     */
    public function updateUserProfile($datos) {
        $query = "UPDATE usuario SET 
                    DPI = :DPI,
                    usuario = :usuario,
                    nombres = :nombres,
                    apellidos = :apellidos,
                    telefono = :telefono,
                    correo = :correo
                  WHERE id_usuario = :id_usuario";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':DPI', $datos['DPI']);
        $stmt->bindParam(':usuario', $datos['usuario']);
        $stmt->bindParam(':nombres', $datos['nombres']);
        $stmt->bindParam(':apellidos', $datos['apellidos']);
        $stmt->bindParam(':telefono', $datos['telefono']);
        $stmt->bindParam(':correo', $datos['correo']);
        $stmt->bindParam(':id_usuario', $datos['id_usuario']);
        
        return $stmt->execute();
    }

    /**
     * Actualiza solo la contraseña de un usuario
     */
    public function updateUserPassword($id_usuario, $hashedPassword) {
        $query = "UPDATE usuario SET password = :password WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id_usuario', $id_usuario);
        return $stmt->execute();
    }
}