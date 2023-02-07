# Instalación

# Instalar php

Instalar php8.1 con las extensiones requeridas por Symfony 5.4.*

# Instalar symfony tool

https://symfony.com/download

# Instalar PostgreSQL 

Seguir instrucciones oficiales.

Crear una nueva base de datos y un usuario para el proyecto, el nombre de la base de datos puede ser `indices_medicion`.

# Configurar variable de entorno

Copiar `.env` a `.env.local`. Actualizar la variable `DATABASE_URL` con los datos de usuario, contraseña y nombre de base datos.

# Instalar composer

Ejecutar dentro del proyecto `composer install` para instalar las librerias.

Al ejecutar composer se activan comandos de symfony como la limpieza de cache, el comando para limpiar cache es `bin/console cache:clear`.

# Ejecutar las migraciones o actualizacion de esquema de doctrine

Si el proyecto cuenta con migraciones el comando seria el siguiente:
`bin/console doctrine:migrations:execute`

Si el proyecto no cuenta con migraciones podemos ejecutar el siguiente comando para que Doctrine genere todas las tablas en nuestra base de datos. Tomar en cuenta los siguientes parametros, `--force` ejecuta las queries, `--dump-sql` muestra todas las querie en la terminal.
`bin/console doctrine:schema:update --dump-sql --force`

# Usuario administrador
Podemos crear un usuario super administrador con el siguiente comando:
`bin/console sonata:user:create --super-admin dev dev@example.com`

# Ejecutar la aplicación
Para ejecutar la aplicación en modo de desarrollo podemos utilizar el comando `symfony`, se instala desde la documentación de Symfony.

**Producción:** Para ejecutar el proyecto en producción debemos configurar un servidor web, por ejemplo apache2 o nginx. También debemos habilitar el modo producción en las variables de entorno:
`APP_ENV=prod`

## Instalar apache2

```
<VirtualHost *:80>
    ServerName 165.22.180.120
    # ServerAlias www.domain.tld

    DocumentRoot /srv/web-apps/IndicesYMedicion/public
    DirectoryIndex /index.php
    <Directory /srv/web-apps/IndicesYMedicion/public>
        AllowOverride All
        Order Allow,Deny
        Allow from All

        FallbackResource /index.php
    </Directory>

    # uncomment the following lines if you install assets as symlinks
    # or run into problems when compiling LESS/Sass/CoffeeScript assets
    # <Directory /srv/web-apps/IndicesYMedicion>
    #     Options FollowSymlinks
    # </Directory>

    # optionally disable the fallback resource for the asset directories
    # which will allow Apache to return a 404 error when files are
    # not found instead of passing the request to Symfony
    <Directory /srv/web-apps/IndicesYMedicion/public/bundles>
        DirectoryIndex disabled
        FallbackResource disabled
    </Directory>

    ErrorLog /var/log/apache2/indicesymedicion_error.log
    CustomLog /var/log/apache2/indicesymedicion_access.log combined

    # optionally set the value of the environment variables used in the application
    #SetEnv APP_ENV prod
    #SetEnv APP_SECRET <app-secret-id>
    #SetEnv DATABASE_URL "mysql://db_user:db_pass@host:3306/db_name"
</VirtualHost>
```