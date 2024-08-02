<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalación del Proyecto Laravel con Laragon</title>
</head>
<body>
    <h1>Instalación del Proyecto Laravel con Laragon</h1>
    <p>Este archivo te guiará a través de los pasos necesarios para instalar y configurar el proyecto Laravel disponible en el repositorio <a href="https://github.com/danireiros/cocoproyecto.git">cocoproyecto</a> utilizando Laragon.</p>

    <h2>Requisitos</h2>
    <p>Antes de comenzar, asegúrate de tener lo siguiente instalado en tu sistema:</p>
    <ul>
        <li><a href="https://laragon.org/">Laragon</a>: Un entorno de desarrollo local para PHP, MySQL, y otros servicios.</li>
        <li><a href="https://git-scm.com/">Git</a>: Para clonar el repositorio.</li>
    </ul>

    <h2>Pasos para la Instalación</h2>
    <ol>
        <li><strong>Clona el Repositorio</strong>
            <p>Abre una terminal y ejecuta el siguiente comando para clonar el repositorio del proyecto:</p>
            <pre><code>git clone https://github.com/danireiros/cocoproyecto.git</code></pre>
            <p>Esto descargará el proyecto en un directorio llamado <code>cocoproyecto</code>.</p>
        </li>

        <li><strong>Configura Laragon</strong>
            <ol>
                <li>Abre Laragon.</li>
                <li>En la ventana de Laragon, haz clic en el botón <strong>Menu</strong> (generalmente en la parte superior derecha) y selecciona <strong>www</strong> para abrir el directorio <code>www</code> de Laragon.</li>
                <li>Mueve o copia el directorio <code>cocoproyecto</code> al directorio <code>www</code> de Laragon.</li>
            </ol>
        </li>

        <li><strong>Configura el Archivo <code>.env</code></strong>
            <ol>
                <li>Abre el directorio del proyecto (<code>cocoproyecto</code>) en un editor de texto.</li>
                <li>Renombra el archivo <code>.env.example</code> a <code>.env</code>.</li>
                <li>Abre el archivo <code>.env</code> y configura la conexión a la base de datos. Asegúrate de que los detalles coincidan con tu configuración de Laragon:
                    <pre><code>DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cocoproyecto
DB_USERNAME=root
DB_PASSWORD=</code></pre>
                </li>
            </ol>
        </li>

        <li><strong>Instala las Dependencias del Proyecto</strong>
            <ol>
                <li>Abre una terminal y navega al directorio del proyecto (<code>cocoproyecto</code>):
                    <pre><code>cd www/cocoproyecto</code></pre>
                </li>
                <li>Ejecuta el siguiente comando para instalar las dependencias del proyecto utilizando Composer:
                    <pre><code>composer install</code></pre>
                </li>
            </ol>
        </li>

        <li><strong>Genera la Clave de Aplicación</strong>
            <p>Ejecuta el siguiente comando para generar una clave de aplicación:
                <pre><code>php artisan key:generate</code></pre>
            </p>
        </li>

        <li><strong>Configura la Base de Datos</strong>
            <p>Asegúrate de que la base de datos <code>cocoproyecto</code> exista en tu entorno de Laragon. Puedes crearla utilizando phpMyAdmin o mediante la línea de comandos:
                <pre><code>mysql -u root -e "CREATE DATABASE cocoproyecto;"</code></pre>
            </p>
        </li>

        <li><strong>Ejecuta las Migraciones y Seeders</strong>
            <p>Ejecuta las migraciones para crear las tablas necesarias en la base de datos:
                <pre><code>php artisan migrate</code></pre>
            </p>
            <p>Si el proyecto incluye seeders, puedes ejecutarlos también:
                <pre><code>php artisan db:seed</code></pre>
            </p>
        </li>

        <li><strong>Inicia el Servidor</strong>
            <ol>
                <li>En Laragon, haz clic en el botón <strong>Start All</strong> para iniciar los servicios de Apache y MySQL.</li>
                <li>Asegúrate de que el proyecto esté accesible en tu navegador visitando <a href="http://cocoproyecto.test">http://cocoproyecto.test</a> o <a href="http://localhost/cocoproyecto/public">http://localhost/cocoproyecto/public</a>.</li>
            </ol>
        </li>
    </ol>

    <h2>Troubleshooting</h2>
    <ul>
        <li><strong>Error de Conexión a la Base de Datos</strong>: Asegúrate de que la configuración en el archivo <code>.env</code> es correcta y que la base de datos <code>cocoproyecto</code> existe.</li>
        <li><strong>Problemas con Dependencias</strong>: Si encuentras problemas durante la instalación de dependencias con Composer, intenta ejecutar <code>composer update</code> o <code>composer install</code> nuevamente.</li>
    </ul>

    <h2>Recursos Adicionales</h2>
    <ul>
        <li><a href="https://laravel.com/docs">Documentación de Laravel</a></li>
        <li><a href="https://laragon.org/docs/">Documentación de Laragon</a></li>
        <li><a href="https://getcomposer.org/">Composer</a></li>
    </ul>

    <p>¡Listo! Ahora deberías tener tu proyecto Laravel funcionando localmente en Laragon. Si tienes alguna pregunta o encuentras problemas, no dudes en consultar la documentación o buscar ayuda en la comunidad.</p>
</body>
</html>
