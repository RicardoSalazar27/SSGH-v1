<?php
namespace Model;

#[\AllowDynamicProperties]
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    protected static $alertas = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) {
        self::$db = $database;
    }

    // Setear un tipo de Alerta
    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Obtener las alertas
    public static function getAlertas() {
        return static::$alertas;
    }

    // Validación que se hereda en modelos
    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    // Consulta SQL para crear un objeto en Memoria (Active Record)
    public static function consultarSQL($query) {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // liberar la memoria
        $resultado->free();

        // retornar los resultados
        return $array;
    }

    // Crea el objeto en memoria que es igual al de la BD
    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value ) {
            if(property_exists( $objeto, $key  )) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value ) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {
            $this->$key = $value;
          }
        }
    }

    // Registros - CRUD
    public function guardar() {
        $resultado = '';
        if(!is_null($this->id)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado;
    }

    // Obtener todos los Registros
    // public static function all($orden) {
    //     $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC";
    //     $resultado = self::consultarSQL($query);
    //     return $resultado;
    // }
    
    // Obtener todos los registros con opción de orden ascendente o descendente
    public static function all($orden = 'DESC') {
        // Validar que solo acepte 'ASC' o 'DESC'
        if ($orden !== 'ASC' && $orden !== 'DESC') {
            $orden = 'DESC'; // Si el valor no es válido, se usa DESC por defecto
        }

        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id $orden";
        return self::consultarSQL($query);
    }

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla  ." WHERE id = $id";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC LIMIT $limite";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Busqueda Where con Múltiples opciones
    public static function whereArray($array = []) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE ";
        foreach($array as $key => $value) {
            if($key == array_key_last($array)) {
                $query .= " $key = '$value'";
            } else {
                $query .= " $key = '$value' AND ";
            }
        }
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Traer un total de registros (admite la busqueda por columna-solo 1)
    public static function total($columna = '', $valor = '') {
        $query = "SELECT COUNT(*) FROM " . static::$tabla;
        if($columna) {
            $query .= " WHERE $columna = $valor";
        }
        $resultado = self::$db->query($query);
        $total = $resultado->fetch_array();

        return array_shift($total);
    }

    // Total de Registros con un Array Where (varias columas con un valor en especifico)
    public static function totalArray($array = []) {
        $query = "SELECT COUNT(*) FROM " . static::$tabla . " WHERE ";
        foreach($array as $key => $value) {
            if($key == array_key_last($array)) {
                $query .= " $key = '$value' ";
            } else {
                $query .= " $key = '$value' AND ";
            }
        }
        //debuguear($query);
        $resultado = self::$db->query($query);
        $total = $resultado->fetch_array();
        return array_shift($total);
    }

    // Total de registros con un Array Where IN (misma columna diferentes valores)
    public static function totalArrayIn($array = []) {
        $query = "SELECT COUNT(*) FROM " . static::$tabla . " WHERE ";

        $conditions = [];
        foreach($array as $key => $value) {
            // Si el valor es un array, usamos el operador IN
            if (is_array($value)) {
                $conditions[] = "$key IN (" . implode(',', array_map(function($v) { return "'$v'"; }, $value)) . ")";
            } else {
                // Si no es un array, usamos la comparación estándar
                $conditions[] = "$key = '$value'";
            }
        }

        // Unir todas las condiciones con 'AND'
        $query .= implode(' AND ', $conditions);

        //debuguear($query);

        // Ejecutar la consulta
        $resultado = self::$db->query($query);
        $total = $resultado->fetch_array();

        // Devolver el total
        return array_shift($total);
    }

    // Busqueda Where con Columna 
    public static function where($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE $columna = '$valor'";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Búsqueda parcial LIKE con Columna
    public static function like($columna, $valor) {
        $valor = "%" . $valor . "%";  // Agregar los comodines para búsqueda parcial
        $query = "SELECT * FROM " . static::$tabla . " WHERE $columna LIKE '$valor'";
        //debuguear($query);
        $resultado = self::consultarSQL($query);
        return ($resultado);
    }

    // crea un nuevo registro
    public function crear() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES ('"; // Sin espacio extra
        $query .= join("', '", array_values($atributos));
        $query .= "') "; // Sin espacio antes del cierre

        //debuguear($query); // Descomentar si no te funciona algo

        // Resultado de la consulta
        $resultado = self::$db->query($query);
        return [
           'resultado' =>  $resultado,
           'id' => self::$db->insert_id
        ];
    }

    // Actualizar el registro
    public function actualizar() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 

        // Actualizar BD
        $resultado = self::$db->query($query);
        return $resultado;
    }

    // Actualizar (PUT)
    public function update($datos) {
        if (empty($datos)) {
            debuguear("Datos vacíos: no hay nada que actualizar.");
            return false;
        }
    
        // Obtener todas las columnas de la base de datos, excepto 'id'
        $columnasDB = static::$columnasDB;
        $columnasDB = array_diff($columnasDB, ['id']); // Excluir ID
    
        // Verificar que todas las columnas existan en los datos proporcionados
        // foreach ($columnasDB as $columna) {
        //     if (!array_key_exists($columna, $datos)) {
        //         debuguear("Falta la columna obligatoria: " . $columna);
        //         return false;
        //     }
        // }
    
        // debuguear("Datos recibidos antes de procesar: ");
        // debuguear($datos);
    
        // Filtrar y sanitizar los datos recibidos
        $atributos = [];
        foreach ($datos as $key => $value) {
            if (in_array($key, $columnasDB)) {
                // Actualiza el valor en el objeto antes de sanitizarlo
                $this->$key = $value;
    
                // Sanitiza el valor y agrégalo a la lista de actualización
                $atributos[$key] = self::$db->escape_string($value);
            }
        }
    
        // debuguear("Atributos después de sanitización:");
        // debuguear($atributos);
    
        if (empty($atributos)) {
            debuguear("No hay cambios válidos para actualizar.");
            return false;
        }
    
        // Construcción de la consulta UPDATE
        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key} = '{$value}'";
        }
    
        // debuguear("Valores para la consulta UPDATE:");
        // debuguear($valores);
    
        $query = "UPDATE " . static::$tabla . " SET " . join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' LIMIT 1";
    
        // debuguear("Consulta SQL generada:");
        // debuguear($query);
    
        // Ejecutar la consulta
        $resultado = self::$db->query($query);
    
        // debuguear("Resultado de la ejecución:");
        // debuguear($resultado);
    
        return $resultado;
    }
    
    
    // Actualizar (PATCH)
    public function updatepartially($datos) {
        if (empty($datos)) {
            return false; // No hay datos para actualizar
        }
    
        // Filtrar solo los datos enviados en la petición que están en el modelo
        $atributos = [];
        foreach ($datos as $key => $value) {
            if (in_array($key, array_keys($this->atributos()))) {
                // 🔥 Actualiza el valor en el objeto antes de sanitizarlo
                $this->$key = $value;  
                
                // 🔥 Luego sanitízalo y agrégalo a la lista de actualización
                $atributos[$key] = self::$db->escape_string($value);
            }
        }
    
        if (empty($atributos)) {
            return false; // No hay cambios válidos para actualizar
        }
    
        // Construcción de la consulta UPDATE
        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key} = '{$value}'";
        }
    
        $query = "UPDATE " . static::$tabla . " SET " . join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' LIMIT 1";
    
        //debuguear($query);
        // Ejecutar la consulta
        $resultado = self::$db->query($query);
        return $resultado;
    }        

    // Eliminar un Registro por su ID
    public function eliminar() {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        return $resultado;
    }

    // Permite Ejecutar Procedimeintos de Almacenado Definidos En Los MOdelos
    public static function ejecutarProcedimiento($nombreProcedimiento, $parametros = []) {
        // Convertir los parámetros a una cadena para usarlos en la consulta
        $parametrosSQL = implode(", ", array_map(function($param) {
            // Asegurarse de que los parámetros numéricos sean tratados correctamente
            return is_numeric($param) ? $param : "'".$param."'";
        }, $parametros));
        
        // Construir la consulta
        $query = "CALL $nombreProcedimiento($parametrosSQL)";
        //debuguear($query);
        //return;
    
        // Ejecutar el procedimiento almacenado
        $resultado = self::$db->query($query);
        return $resultado;
    }    

}