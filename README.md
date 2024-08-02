# Instalación del Proyecto Laravel con Laragon

Este archivo te guiará a través de los pasos necesarios para instalar y configurar el proyecto Laravel disponible en el repositorio [cocoproyecto](https://github.com/danireiros/cocoproyecto.git) utilizando Laragon.

## Requisitos

Antes de comenzar, asegúrate de tener lo siguiente instalado en tu sistema:

- **[Laragon](https://laragon.org/)**: Un entorno de desarrollo local para PHP, MySQL, y otros servicios.
- **[Git](https://git-scm.com/)**: Para clonar el repositorio.

## Pasos para la Instalación

1. **Clona el Repositorio**

   Abre una terminal y ejecuta el siguiente comando para clonar el repositorio del proyecto:

   `git clone https://github.com/danireiros/cocoproyecto.git`

   Esto descargará el proyecto en un directorio llamado `cocoproyecto`.

2. **Configura Laragon**

   1. Abre Laragon.
   2. En la ventana de Laragon, haz clic en el botón **Menu** (generalmente en la parte superior derecha) y selecciona **www** para abrir el directorio `www` de Laragon.
    3. Mueve o copia el directorio `cocoproyecto` al directorio `www` de Laragon.

3. **Configura el Archivo `.env`**

   1. Abre el directorio del proyecto (`cocoproyecto`) en un editor de texto.
   2. Renombra el archivo `.env.example` a `.env`.
   3. Abre el archivo `.env` y configura la conexión a la base de datos. Asegúrate de que los detalles coincidan con tu configuración de Laragon:

      ```
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=cocoproyecto
      DB_USERNAME=root
      DB_PASSWORD=
      ```

4. **Instala las Dependencias del Proyecto**

   1. Abre una terminal y navega al directorio del proyecto (`cocoproyecto`):

      `cd www/cocoproyecto`

   2. Ejecuta el siguiente comando para instalar las dependencias del proyecto utilizando Composer:

      `composer install`

5. **Genera la Clave de Aplicación**

   Ejecuta el siguiente comando para generar una clave de aplicación:

   `php artisan key:generate`

6. **Configura la Base de Datos**

   Asegúrate de que la base de datos `cocoproyecto` exista en tu entorno de Laragon. Puedes crearla utilizando phpMyAdmin o mediante la línea de comandos:

   `mysql -u root -e "CREATE DATABASE cocoproyecto;"`

7. **Ejecuta las Migraciones y Seeders**

   Ejecuta las migraciones para crear las tablas necesarias en la base de datos:

   `php artisan migrate`

   El proyecto incluye seeders para usuarios, puedes ejecutarlos también:

   `php artisan db:seed`

   Con el administrador podra crear proyectos y asignar roles a usuarios 
   email: admin@gmail.com
   contraseña: password

8. **Inicia el Servidor**

   1. En Laragon, haz clic en el botón **Start All** para iniciar los servicios de Apache y MySQL.
   2. Asegúrate de que el proyecto esté accesible en tu navegador visitando [http://cocoproyecto.test](http://cocoproyecto.test) o [http://localhost/cocoproyecto/public](http://localhost/cocoproyecto/public).

## Troubleshooting

- **Error de Conexión a la Base de Datos**: Asegúrate de que la configuración en el archivo `.env` es correcta y que la base de datos `cocoproyecto` existe.
- **Problemas con Dependencias**: Si encuentras problemas durante la instalación de dependencias con Composer, intenta ejecutar `composer update` o `composer install` nuevamente.

## Recursos Adicionales

- [Documentación de Laravel](https://laravel.com/docs)
- [Documentación de Laragon](https://laragon.org/docs/)
- [Composer](https://getcomposer.org/)

¡Listo! Ahora deberías tener tu proyecto Laravel funcionando localmente en Laragon. Si tienes alguna pregunta o encuentras problemas, no dudes en consultar la documentación o buscar ayuda en la comunidad.
