
-- =========================================
-- PROYECTO: PAISES Y PROVINCIAS
-- BASE DE DATOS: paises_7A
-- =========================================

CREATE DATABASE IF NOT EXISTS paises_7A
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE paises_7A;

-- =========================================
-- TABLA PAISES
-- =========================================

CREATE TABLE IF NOT EXISTS paises (
    pais_id INT AUTO_INCREMENT PRIMARY KEY,
    pais_descripcion VARCHAR(50) NOT NULL
);

-- =========================================
-- TABLA PROVINCIAS
-- =========================================

CREATE TABLE IF NOT EXISTS provincias (
    pro_id INT AUTO_INCREMENT PRIMARY KEY,
    pais_id INT NOT NULL,
    pro_descripcion VARCHAR(50) NOT NULL,
    CONSTRAINT fk_provincias_paises
        FOREIGN KEY (pais_id)
        REFERENCES paises(pais_id)
);

-- =========================================
-- CARGA DE LOS 20 PAISES
-- =========================================

INSERT INTO paises (pais_descripcion)
SELECT datos.pais
FROM (
    SELECT 'Argentina' AS pais
    UNION ALL SELECT 'Bolivia'
    UNION ALL SELECT 'Brasil'
    UNION ALL SELECT 'Chile'
    UNION ALL SELECT 'Colombia'
    UNION ALL SELECT 'Costa Rica'
    UNION ALL SELECT 'Cuba'
    UNION ALL SELECT 'Ecuador'
    UNION ALL SELECT 'El Salvador'
    UNION ALL SELECT 'Guatemala'
    UNION ALL SELECT 'Haití'
    UNION ALL SELECT 'Honduras'
    UNION ALL SELECT 'México'
    UNION ALL SELECT 'Nicaragua'
    UNION ALL SELECT 'Panamá'
    UNION ALL SELECT 'Paraguay'
    UNION ALL SELECT 'Perú'
    UNION ALL SELECT 'República Dominicana'
    UNION ALL SELECT 'Uruguay'
    UNION ALL SELECT 'Venezuela'
) AS datos
WHERE NOT EXISTS (
    SELECT 1
    FROM paises p
    WHERE p.pais_descripcion = datos.pais
);

-- =========================================
-- CARGA DE LAS PROVINCIAS ARGENTINAS
-- INCLUYE CABA
-- =========================================

INSERT INTO provincias (pais_id, pro_descripcion)
SELECT p.pais_id, datos.provincia
FROM paises p
CROSS JOIN (
    SELECT 'Buenos Aires' AS provincia
    UNION ALL SELECT 'Catamarca'
    UNION ALL SELECT 'Chaco'
    UNION ALL SELECT 'Chubut'
    UNION ALL SELECT 'Córdoba'
    UNION ALL SELECT 'Corrientes'
    UNION ALL SELECT 'Entre Ríos'
    UNION ALL SELECT 'Formosa'
    UNION ALL SELECT 'Jujuy'
    UNION ALL SELECT 'La Pampa'
    UNION ALL SELECT 'La Rioja'
    UNION ALL SELECT 'Mendoza'
    UNION ALL SELECT 'Misiones'
    UNION ALL SELECT 'Neuquén'
    UNION ALL SELECT 'Río Negro'
    UNION ALL SELECT 'Salta'
    UNION ALL SELECT 'San Juan'
    UNION ALL SELECT 'San Luis'
    UNION ALL SELECT 'Santa Cruz'
    UNION ALL SELECT 'Santa Fe'
    UNION ALL SELECT 'Santiago del Estero'
    UNION ALL SELECT 'Tierra del Fuego'
    UNION ALL SELECT 'Tucumán'
    UNION ALL SELECT 'Ciudad Autónoma de Buenos Aires'
) AS datos
WHERE p.pais_descripcion = 'Argentina'
AND NOT EXISTS (
    SELECT 1
    FROM provincias pr
    WHERE pr.pais_id = p.pais_id
    AND pr.pro_descripcion = datos.provincia
);

-- =========================================
-- CONSULTAS DE VERIFICACION
-- =========================================

SELECT * FROM paises
ORDER BY pais_id;

SELECT * FROM provincias
ORDER BY pro_id;

SELECT
    p.pais_descripcion AS Pais,
    COUNT(pr.pro_id) AS Cantidad_de_provincias
FROM paises p
LEFT JOIN provincias pr
    ON p.pais_id = pr.pais_id
GROUP BY p.pais_id, p.pais_descripcion
ORDER BY p.pais_descripcion;
