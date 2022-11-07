# Sistema de Gestion de Logistica de E-Commerce

# Caracteristicas

1. Productos
2. Categorias de Producto
3. Ventas
4. Proveedores
5. Control de Acceso (roles y permisos)
6. Reportes
7. Usuarios
8. Perfil de Usuario
9. Ajustes
10. Panel de Control
11. Notificaciones de Stock

# Instalacion

1. Ir a la carpeta del proyecto

2. Instalar paquetes necesarios con composer

```
composer install
```

3. Crear la base de datos 

4. Renombrar .env.example a .env o copiar el archivo y cambiarle el nombre a .env.

```
cp .env.example ./.env
```
5. Generar la clave del proyecto
```
php artisan key:generate
```

6. Configurar la coneccion a la base de datos el el archivo .env

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=logistica
DB_USERNAME=root
DB_PASSWORD=
```
7. Importar la base de datos completa, o ejecutar las migraciones

```
php artisan migrate --seed
```
8. Iniciar el servidor local para ejecutar la aplicacion
```
php artisan serve
```

9. abrir la direccion:
```
http://127.0.0.1:8000
```

# Claves de Acceso

```
 email: admin@admin.com
 contraseña: admin

 email: luis@email.com
 contraseña: luis123

 email: juan@email.com
 contraseña: juan123
```

# Como agregar producto para vender

1 Agregar categoria

2 Agregar proveedor

3. Hacer la adquisicion del producto del proveedor.

4. Al ejecutar la adquisicion se agrega los productos deseados.

5. El producto ya esta disponible para venta.

6. Al ser notificado de productos fuera de stock, actualizar la cantidad del producto adquirido o ejecutar nueva adquisicion.


