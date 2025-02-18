<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'Usuarios';
    protected static $columnasDB = ['id', 'nombre','apellido', 'email', 'password','rol_id', 'estatus','telefono','direccion'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $rol_id;
    public $estatus;
    public $telefono;
    public $direccion;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->rol_id = $args['rol_id'] ?? 2;
        $this->estatus = $args['estatus'] ?? 0;
        $this->telefono = $args['telefono'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
    }

    // Comprobar el password
    public function comprobar_password() : bool {
        return password_verify($this->password_actual, $this->password );
    }

    // Hashea el password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

}