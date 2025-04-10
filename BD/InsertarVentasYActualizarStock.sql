CREATE PROCEDURE insertar_ventas_y_actualizar_stock(
    IN ventas_json JSON,
    IN productos_json JSON
)
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE total INT;

    -- Insertar ventas en la tabla Pagos
    SET total = JSON_LENGTH(ventas_json);
    WHILE i < total DO
        INSERT INTO Pagos (
            reservacion_id,
            producto_id,
            monto,
            fecha_pago,
            tipo_pago,
            descripcion,
            estado
        ) VALUES (
            JSON_UNQUOTE(JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].reservacion_id'))),
            JSON_UNQUOTE(JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].producto_id'))),
            JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].monto')),
            JSON_UNQUOTE(JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].fecha_pago'))),
            JSON_UNQUOTE(JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].tipo_pago'))),
            JSON_UNQUOTE(JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].descripcion'))),
            JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].estado'))
        );
        SET i = i + 1;
    END WHILE;

    -- Actualizar stock de productos
    SET i = 0;
    SET total = JSON_LENGTH(productos_json);
    WHILE i < total DO
        UPDATE Productos
        SET stock = stock - JSON_EXTRACT(productos_json, CONCAT('$[', i, '].cantidad'))
        WHERE id = JSON_UNQUOTE(JSON_EXTRACT(productos_json, CONCAT('$[', i, '].producto_id')));
        SET i = i + 1;
    END WHILE;

END;


--version 2 soporta venta al publico
CREATE PROCEDURE insertar_ventas_y_actualizar_stock(
    IN ventas_json JSON,
    IN productos_json JSON
)
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE total INT;

    -- Insertar ventas en la tabla Pagos
    SET total = JSON_LENGTH(ventas_json);
    WHILE i < total DO
        INSERT INTO Pagos (
            reservacion_id,
            producto_id,
            monto,
            fecha_pago,
            tipo_pago,
            descripcion,
            estado
        ) VALUES (
            IF(JSON_UNQUOTE(JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].reservacion_id'))) = '0', NULL, JSON_UNQUOTE(JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].reservacion_id')))),
            JSON_UNQUOTE(JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].producto_id'))),
            JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].monto')),
            JSON_UNQUOTE(JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].fecha_pago'))),
            JSON_UNQUOTE(JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].tipo_pago'))),
            JSON_UNQUOTE(JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].descripcion'))),
            JSON_EXTRACT(ventas_json, CONCAT('$[', i, '].estado'))
        );
        SET i = i + 1;
    END WHILE;

    -- Actualizar stock de productos
    SET i = 0;
    SET total = JSON_LENGTH(productos_json);
    WHILE i < total DO
        UPDATE Productos
        SET stock = stock - JSON_EXTRACT(productos_json, CONCAT('$[', i, '].cantidad'))
        WHERE id = JSON_UNQUOTE(JSON_EXTRACT(productos_json, CONCAT('$[', i, '].producto_id')));
        SET i = i + 1;
    END WHILE;

END;