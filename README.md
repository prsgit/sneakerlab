# SneakerLab

Aplicación web desarrollada en PHP con base de datos MySQL para la gestión de una tienda de zapatillas (admin) y para la compra (cliente).

El proyecto está preparado para ejecutarse en un entorno local utilizando XAMPP.

---

## Requisitos

- XAMPP instalado (Apache + MySQL)
- Navegador web (Chrome, Firefox, Edge, etc.)
- Acceso al repositorio del proyecto en GitHub

---

## Instalación del proyecto

### 1. Descargar el proyecto desde GitHub

- Clonar el repositorio o descargarlo desde GitHub.

### 2. Copiar el proyecto en XAMPP

- Copiar la carpeta del proyecto dentro de la carpeta `htdocs` de XAMPP:

  C:\xampp\htdocs\sneakerLab

---

## Configuración de la base de datos

### 3. Arrancar servicios

Abrir XAMPP y arrancar los servicios:
- Apache
- MySQL

---

### 4. Crear la base de datos

1. Abrir el navegador y acceder a:

   http://localhost/phpmyadmin

2. Crear una nueva base de datos con el nombre:

   sneakerlab

---

### 5. Importar la base de datos

1. Seleccionar la base de datos `sneakerlab`
2. Ir a la pestaña **Importar**
3. Seleccionar el archivo:

   sneakerlab.sql

4. Pulsar el botón **Continuar**

---

## Configuración de conexión

- El archivo de conexión a la base de datos se encuentra en:

  config/db.php


## Los datos de conexión utilizados son los siguientes:

```php
$host = "localhost";
$db   = "sneakerlab";
$user = "root";
$pass = "mpa2026+";

 ```  
Abrir el navegador y acceder a la siguiente URL:

http://localhost/sneakerLab/public/home.php

## Notas

- La base de datos ya está incluida en el archivo sneakerlab.sql.

- Los datos de acceso (usuarios de prueba) se encuentran documentados en el informe del proyecto.

- El proyecto está configurado para ejecutarse en entorno local.

## Autor
- Proyecto desarrollado por Pedro Rueda.

