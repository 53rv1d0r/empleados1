# Gestión de Empleados

Este proyecto es una aplicación web básica desarrollada en PHP que permite gestionar empleados. Incluye un sistema **CRUD** (Crear, Leer, Actualizar, Eliminar) completo para los registros de empleados y utiliza **tablas maestras** para campos como el tipo de documento, cargo y tipo de contrato.

---

## Características Principales

* **Gestión de Empleados**: Permite crear, leer, actualizar y eliminar registros de empleados.
* **Campos de Empleado**: Cada empleado incluye los siguientes datos:
    * Nombres
    * Apellidos
    * Número de documento
    * Tipo de documento (Maestro)
    * Cargo (Maestro)
    * Tipo de contrato (Maestro)
    * Fecha de ingreso
* **Tablas Maestras**: Utiliza tablas separadas para gestionar opciones predefinidas, lo que asegura consistencia y facilidad de mantenimiento:
    * `Tipos de Documento`: Cédula, Tarjeta de identidad, Cédula de extranjería, etc.
    * `Cargos`: Médico General, Auxiliar en salud, Líder Médico, etc.
    * `Tipos de Contrato`: Obra Labor, Prestación de servicios, Temporal, etc.
* **Interfaz de Usuario**:
    * Desarrollada con **HTML**, **CSS** y **PHP**.
    * Formularios intuitivos para **registrar** y **editar** empleados.
    * Tabla con **listado de empleados** para una visualización rápida.
    * Campos de tablas maestras implementados como **menús desplegables (`<select>`)** para una selección sencilla.
    * **Validación de campos obligatorios** antes de guardar la información.
* **Backend**:
    * Desarrollado completamente en **PHP**.
    * Base de datos **MySQL** o **MariaDB** para el almacenamiento de datos.

---

## Tecnologías Utilizadas

* **Lenguaje**: PHP
* **Base de Datos**: MySQL / MariaDB
* **Frontend**: HTML, CSS

---

## Estructura del Proyecto

empleados1/
├── assets/                       # Archivos estáticos como imágenes o logos
│   └── corporacion-fomenthum-logo.svg
├── css/                          # Hojas de estilo CSS
│   └── style.css
├── empleados/                    # Scripts PHP para la gestión de empleados (CRUD)
│   ├── crear.php                 # Formulario y lógica para crear un nuevo empleado
│   ├── editar.php                # Formulario y lógica para editar un empleado existente
│   └── index.php                 # Lista de empleados y opciones para CRUD
├── includes/                     # Archivos PHP reutilizables (conexión a DB)
│   └── db.php                    # Configuración y conexión a la base de datos
└── index.php                     # Página principal de la aplicación 

---

## Requisitos del Sistema

* Servidor web (Apache, Nginx) con soporte para PHP.
* PHP 7.4 o superior (recomendado).
* MySQL o MariaDB.

---

## Configuración e Instalación

Sigue estos pasos para poner en marcha el proyecto en tu entorno local:

### 1. Clona el repositorio

Abre tu terminal y ejecuta el siguiente comando:

``` git clone https://github.com/53rv1d0r/empleados1.git ``` 

2. Configura tu servidor web

Coloca la carpeta empleados1 en el directorio htdocs (o el equivalente de tu servidor web, como www para Nginx).

3. Configura la base de datos

Crea una base de datos en MySQL/MariaDB (por ejemplo, empleados_db).

Ejecuta el script SQL para crear las tablas necesarias. Este script deberías encontrarlo en los entregables, si no lo tienes, deberás crearlo manualmente:

Creando base de datos:
```
CREATE DATABASE empleados1; 
USE empleados1; 
```
Creando tablas tipos_documento, cargos, tipos_contrato y empleados en DB  empleados1:

```
CREATE TABLE tipos_documento(
    tipos_documento_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo_documento VARCHAR (100) NOT NULL UNIQUE
);
CREATE TABLE cargos(
    cargos_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_cargo VARCHAR (100) NOT NULL UNIQUE
);
CREATE TABLE tipos_contrato(
    tipos_contrato_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo_contrato VARCHAR (100) NOT NULL UNIQUE
);
CREATE TABLE empleados(
	empleado_id INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR (100) NOT NULL,
    apellidos VARCHAR (100) NOT NULL,
    numero_documento VARCHAR (50) NOT NULL UNIQUE,
    tipo_documento INT NOT NULL,
    cargo INT NOT NULL,
    tipo_contrato INT NOT NULL,
	fecha_ingreso DATE NOT NULL,
    FOREIGN KEY (tipo_documento) REFERENCES tipos_documento(tipos_documento_id),
    FOREIGN KEY (cargo) REFERENCES cargos (cargos_id),
    FOREIGN KEY (tipo_contrato) REFERENCES tipos_contrato (tipos_contrato_id)
);
```
Inserción (SEED) de datos en tablas maestras:
```
INSERT INTO tipos_documento (nombre_tipo_documento) VALUES
('CÉDULA DE CIUDADANÍA'),
('TARJETA DE IDENTIDAD'),
('CÉDULA DE EXTRANJERÍA'),
('PASAPORTE')
;
INSERT INTO cargos (nombre_cargo) VALUES
('MÉDICO GENERAL'),
('AUXILIAR EN SALUD'),
('LIDER MÉDICO'),
('ENFERMERO(A)'),
('ADMINISTRADOR(A)')
;
INSERT INTO tipos_contrato (nombre_tipo_contrato)VALUES
('OBRA LABOR'),
('PRESTACIÓN DE SERVICIOS'),
('TÉRMINO FIJO'),
('TÉRMINO INDEFINIDO')
;
```
4. Configura la conexión a la base de datos

Edita el archivo includes/db.php con tus credenciales de la base de datos:
```
<?php
$host = 'localhost'; // O la IP de tu servidor de BD
$db = 'empleados_db'; // El nombre de la base de datos que creaste
$user = 'tu_usuario_db'; // Tu usuario de la base de datos
$password = 'tu_contraseña_db'; // Tu contraseña de la base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
```
5. Accede a la aplicación

Abre tu navegador y ve a la URL donde está alojado tu proyecto ```(por ejemplo, http://localhost/empleados1 o la URL configurada para tu virtual host).```
