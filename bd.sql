mysql -u tu_usuario -p

-- Crear tabla Roles
CREATE TABLE Roles (
    ID_rol INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50)  -- Nombre del rol (Ej. 'Recepcionista', 'Administrador', etc.)
);

-- Crear tabla EstadoHabitacion
CREATE TABLE EstadoHabitacion (
    ID_estado_habitacion INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50),  -- Estado de la habitación (Ej. 'Ocupada', 'Disponible', 'Necesita limpieza')
    descripcion TEXT  -- Descripción del estado (Ej. 'Habitación ocupada por huésped', 'Habitación lista para ser ocupada')
);

-- Crear tabla Niveles (Pisos)
CREATE TABLE Niveles (
    ID_nivel INT PRIMARY KEY AUTO_INCREMENT,
    numero VARCHAR(50), -- Ejemplo 1er
    nombre VARCHAR(50),  -- Nombre del nivel (Ej. 'Primer NIvel', 'Segundo Nivel', etc.)
    descripcion TEXT  -- Descripción del nivel (Ej. 'Planta superior', 'Planta baja', etc.)
);

-- Crear tabla Categoria (anteriormente TiposHabitacion)
CREATE TABLE Categoria (
    ID_categoria INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50),  -- Nombre del tipo de habitación (Ej. 'Suite', 'Sencilla', etc.)
    descripcion TEXT,  -- Descripción del tipo de habitación
    estado VARCHAR(50)  -- Estado del tipo (Ej. 'Disponible', 'En mantenimiento')
);

-- Crear tabla Habitaciones
CREATE TABLE Habitaciones (
    ID_habitacion INT PRIMARY KEY AUTO_INCREMENT,
    numero INT,  -- Número de la habitación
    ID_estado_habitacion INT,  -- Clave foránea que referencia al estado actual de la habitación
    ID_nivel INT,  -- Clave foránea que referencia al nivel/piso en que se encuentra la habitación
    ID_categoria INT,  -- Clave foránea que referencia al tipo de habitación (Ej. 'Estándar', 'Suite', etc.)
    FOREIGN KEY (ID_estado_habitacion) REFERENCES EstadoHabitacion(ID_estado_habitacion),
    FOREIGN KEY (ID_nivel) REFERENCES Niveles(ID_nivel),
    FOREIGN KEY (ID_categoria) REFERENCES Categoria(ID_categoria)
);

-- Crear tabla Hotel
CREATE TABLE Hotel (
    ID_hotel INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),  -- Nombre del hotel
    telefono VARCHAR(20),  -- Teléfono del hotel
    correo VARCHAR(100),  -- Correo electrónico del hotel
    ubicacion VARCHAR(255)  -- Ubicación física del hotel
);

-- Crear tabla Clientes (Huéspedes)
CREATE TABLE Clientes (
    ID_cliente INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),  -- Nombre del huésped
    documento_identidad VARCHAR(50),  -- Documento de identidad del huésped
    correo VARCHAR(100),  -- Correo electrónico del huésped
    telefono VARCHAR(20),  -- Teléfono del huésped
    direccion VARCHAR(255)  -- Dirección del huésped
);

-- Crear tabla Usuarios
CREATE TABLE Usuarios (
    ID_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),  -- Nombre del usuario
    correo VARCHAR(100),  -- Correo electrónico del usuario
    contrasena VARCHAR(255),  -- Contraseña cifrada del usuario
    ID_rol INT,  -- Clave foránea que referencia al rol del usuario,
    estatus INT, -- estatus (0 = inactivo, 1 = activo)
    FOREIGN KEY (ID_rol) REFERENCES Roles(ID_rol)  -- Relación con la tabla Roles
);

-- Crear tabla Reservas
CREATE TABLE Reservas (
    ID_reserva INT PRIMARY KEY AUTO_INCREMENT,
    ID_usuario INT,  -- Clave foránea que referencia al usuario que hizo la reserva
    ID_cliente INT,  -- Clave foránea que referencia al cliente (huésped) que hará la reserva
    fecha_entrada DATE,  -- Fecha de entrada
    fecha_salida DATE,  -- Fecha de salida
    FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID_usuario),
    FOREIGN KEY (ID_cliente) REFERENCES Clientes(ID_cliente)  -- Relación con la tabla Clientes
);

-- Crear tabla Reservas_Habitaciones
CREATE TABLE Reservas_Habitaciones (
    ID_reserva INT,  -- Clave foránea que referencia a la reserva
    ID_habitacion INT,  -- Clave foránea que referencia a la habitación
    PRIMARY KEY (ID_reserva, ID_habitacion),  -- Combinación de ambos campos como clave primaria
    FOREIGN KEY (ID_reserva) REFERENCES Reservas(ID_reserva),
    FOREIGN KEY (ID_habitacion) REFERENCES Habitaciones(ID_habitacion)
);

-- Crear tabla Pagos
CREATE TABLE Pagos (
    ID_pago INT PRIMARY KEY AUTO_INCREMENT,
    ID_reserva INT,  -- Clave foránea que referencia la reserva
    cantidad DECIMAL(10, 2),  -- Monto pagado
    fecha_pago DATE,  -- Fecha en que se realizó el pago
    metodo_pago VARCHAR(50),  -- Método de pago (Ej. 'Efectivo', 'Tarjeta de crédito')
    FOREIGN KEY (ID_reserva) REFERENCES Reservas(ID_reserva)
);

-- Crear tabla Facturacion AUN NO CREO ESTA TABLAAAA
CREATE TABLE Facturacion (
    ID_factura INT PRIMARY KEY AUTO_INCREMENT,
    ID_reserva INT,  -- Clave foránea que referencia la reserva relacionada con la factura
    monto_total DECIMAL(10, 2),  -- Monto total de la factura
    fecha_emision DATE,  -- Fecha de emisión de la factura
    estado_pago VARCHAR(50),  -- Estado del pago (Ej. 'Pagada', 'Pendiente')
    FOREIGN KEY (ID_reserva) REFERENCES Reservas(ID_reserva)
);

-- Crear tabla Auditoria
CREATE TABLE Auditoria (
    ID_auditoria INT PRIMARY KEY AUTO_INCREMENT,
    ID_usuario INT,  -- Clave foránea que referencia al usuario que realizó la acción
    accion ENUM('CREAR', 'EDITAR', 'ELIMINAR', 'CONFIRMAR', 'LOGIN', 'LOGOUT'),  
    tabla_afectada VARCHAR(50),  -- Nombre de la tabla afectada (Ej. "reservas", "usuarios")
    ID_registro_afectado INT,  -- ID del registro afectado en la tabla correspondiente
    detalle TEXT,  -- Información adicional (Ej. cambios realizados, valores previos, etc.)
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Fecha y hora de la acción
    ip_usuario VARCHAR(45),  -- IP del usuario que hizo la acción
    FOREIGN KEY (ID_usuario) REFERENCES Usuarios(ID_usuario)
);
