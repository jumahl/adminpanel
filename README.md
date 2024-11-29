## Requisitos previos

Antes de instalar Laravel, asegúrate de tener los siguientes requisitos:

- **PHP >= 8.2** (con las siguientes extensiones habilitadas: `BCMath`, `Ctype`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `Tokenizer`, `XML`)
- **Composer** (para gestionar dependencias de PHP)
- **MySQL/MariaDB/PostgreSQL** (o cualquier otro sistema de gestión de bases de datos compatible)
- **Servidor Web** (Apache, Nginx, etc.)
- **Node.js** y **npm/yarn** (para gestionar paquetes de frontend si se usan)

## Instalación

Sigue los pasos para clonar el proyecto e instalar las dependencias:

### 1. Clonar el repositorio

Clona este repositorio en tu máquina local:
```bash
git clone https://github.com/elrincondeisma/plantilla-laravel
cd plantilla-laravel
```

### 2. Instalar dependencias

Instala las dependencias necesarias para el proyecto:
```bash
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
```

## Configuración

Sigue los pasos para configurar el proyecto tanto para el uso en local como en el docker:
### 0. Para crear la imagenes en caso de que se use docker
```bash
docker-compose up -d
```
#### nota:
Tener en cuenta que si se utiliza el docker se debe e tener el volumen creado y la base de datos creada si no te va a pedir que la creees.

### 1. Corre las migraciones
```bash
php artisan migrate
```

### 2. Crea la cuenta de administrador de FilamentPHP
```bash
php artisan hexa:account --create
```
### 3. una vez creado el producto se hace el enlace par aque se muestren las imagenes
```bash
php artisan storage:link
```


