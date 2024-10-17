# simple-api-mysql

> Warning:
>
> - Avec free.fr, pas de SLL donc pas de https, donc les mots de passe sont en clair dans la communication http.
> - Le script n'est pas bien sécurisé: la gestion des erreurs, les potentielles injections sql, ... n'ont pas été traités.

## Content

- `api.php` containing the api code, and some methods to replace missing `json_encode` and `json_decode`.

    ```php
    function json_encode_custom($data) {
    }
    function json_decode_custom($json) {
    }
    ```

- `create_admin.php` to create the admin user (can only be called one time).
- `config.xxx.php` are mysql configuration files.

## Setup

- Adapt `config.xxx.php` files to you configuration
- Create those 2 tables:

    ```sql
    CREATE TABLE samples_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    );

    CREATE TABLE samples_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100)
    );
    ```

## Calling the api

- Create the admin user:
  - Update `$username` in `create_admin.php`
  - Run `curl -X GET http://phramusca.free.fr/samples/server-side/php/simple-api-mysql/create_admin.php`

- GET, POST, PUT or DELETE items:

```sh
curl -u admin:wDq4KKVT -X GET http://phramusca.free.fr/samples/server-side/php/simple-api-mysql/api.php
curl -u admin:wDq4KKVT -X POST -d '{"name":"John Doe", "email":"john.doe@example.com"}' -H "Content-Type: application/json" http://phramusca.free.fr/samples/server-side/php/simple-api-mysql/api.php
curl -u admin:wDq4KKVT -X PUT -d '{"id":1, "name":"Jane Doe", "email":"jane.doe@example.com"}' -H "Content-Type: application/json" http://phramusca.free.fr/samples/server-side/php/simple-api-mysql/api.php
curl -u admin:wDq4KKVT -X DELETE -d '{"id":1}' -H "Content-Type: application/json" http://phramusca.free.fr/samples/server-side/php/simple-api-mysql/api.php
```
