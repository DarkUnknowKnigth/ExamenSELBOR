
DROP DATABASE IF EXISTS examen;
CREATE DATABASE examen;
-- TABLES
CREATE TABLE examen.clientes
(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  direccion TEXT NULL,
  pais VARCHAR(50),
  telefono VARCHAR(10),
  fecha_pedido TIMESTAMP,
  vendedor VARCHAR(50),
  region VARCHAR(10)
);
CREATE TABLE examen.facturas
(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  fecha_pedido TIMESTAMP,
  subtotal FLOAT,
  descuento FLOAT(3,2),
  vendedor VARCHAR(50)
);
-- RELATIONSHIPS
ALTER TABLE examen.facturas ADD CONSTRAINT fk_cliente_facturas FOREIGN KEY (cliente_id)
  REFERENCES examen.clientes (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

-- CREATE USER 'dany' IDENTIFIED BY 'dany1234';
-- GRANT ALL PRIVILEGES ON `examen` . * TO 'dany';