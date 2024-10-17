# simple-api-json-file

> Warning:
>
> - There is no security implemented in this sample !
> - Using a text file is not recommended in production.

## Content

- `api.php` containing the api code, and some methods to replace missing `json_encode`, `json_decode` and `file_put_contents`.

    ```php
    function json_encode_custom($data) {
    }
    function json_decode_custom($json) {
    }
    function custom_file_put_contents($filename, $data) {
    }
    ```

- `data.json` with some sample data.

## Calling the api

```sh
curl -X GET http://phramusca.free.fr/samples/server-side/php/simple-api-json-file/api.php
curl -X POST -H "Content-Type: application/json" -d '{"id":3,"name":"Pierre"}' http://phramusca.free.fr/samples/server-side/php/simple-api-json-file/api.php
curl -X PUT -H "Content-Type: application/json" -d '{"id":2,"name":"Jeanette"}' http://phramusca.free.fr/samples/server-side/php/simple-api-json-file/api.php
curl -X DELETE -H "Content-Type: application/json" -d '{"id":1}' http://phramusca.free.fr/samples/server-side/php/simple-api-json-file/api.php
```
