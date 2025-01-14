# Internet Samples

Pieces of code for web sites or web APIs.

## Client Side

### Javascript

- [markdown](client-side/javascript/markdown/README.md) : rendering markdown on client side using [Showdown](https://github.com/showdownjs/showdown).

## Server Side

### PhP

#### Pages Perso Free.fr

Voir le site [les.pages.perso.chez.free.fr](http://les.pages.perso.chez.free.fr/) pour plus d'informations "sur tous les aspects de l'utilisation des pages perso chez Free".

##### Versions de Php

Voir [la migration vers PHP 5.6 et PHP 8](http://les.pages.perso.chez.free.fr/migrations/php5v6.io) sur `les.pages.perso.chez.free.fr`.

- Par défaut, on a `PHP 4.4.3-dev` ([phpinfo](http://phramusca.free.fr/samples/internet/server-side/php/phpinfo/4.4.3-dev/phpinfo.php))
- On peut avoir `PHP 5.1.3RC4-dev` en mettant `php 1` (ou `php 5`) dans un .htaccess ([phpinfo](http://phramusca.free.fr/samples/internet/server-side/php/phpinfo/5.1.3RC4-dev/phpinfo.php))
- `PHP 5.6.34` avec `php56 1` dans un .htaccess. (En Beta version, mais depuis 2018 ...) ([phpinfo](http://phramusca.free.fr/samples/internet/server-side/php/phpinfo/5.6.34/phpinfo.php))

##### SSL / HTTPS

> **ATTENTION** avec *free.fr*
>
> - *free.fr* n'a toujours pas implémenté SSL, donc **pas de https**, donc les mots de passe sont en clair ! ([Mais ce serait en cours (nouvelle du 24 Mars 2024)...](https://www.busyspider.fr/Actu/news_24743_Free-les-pages-perso-sont-en-cours-de-migration-en-version-securisee-https-comme-annonce-lors-de-la-journee-des-communautes-free.php))
> - **Le mot de passe SQL doit avoir entre 8 et 10 caractères**. Bien que l'interface *free.fr* n'impose pas cette contrainte, il vous sera impossible de vous connecter avec un mot de passe plus long ou court.

##### .htaccess

Voir [Le .htaccess des pages perso](http://les.pages.perso.chez.free.fr/le-htaccess-des-pages-perso.io) sur `les.pages.perso.chez.free.fr` pour plus d'infos les .htaccess particuliers des pages persos de *free.fr*

Examples:

```shell
## Passage register globals off
SetEnv REGISTER_GLOBALS 0
# https://stackoverflow.com/questions/3593210/what-are-register-globals-in-php

## identifiant de session placé dans le cookie
SetEnv SESSION_USE_TRANS_SID 0
# https://www.php.net/manual/en/session.configuration.php#ini.session.use-trans-sid

```

#### PhP Samples

- [simple-api-json-file](server-side/php/simple-api-json-file/README.md): une simple API utilisant un simple fichier json comme "base de données"
- [simple-api-mysql](server-side/php/simple-api-mysql/README.md): une simple API avec authentification et base MySQL.
- [simple-api-pdo](server-side/php/simple-api-pdo/README.md): une simple API avec authentification, base MySQL mais utilisant [PHP Data Objects (PDO)](https://www.php.net/manual/fr/book.pdo.php)
- [pico](server-side/php/pico/README.md): Comment installer Pico, un "flat-file CMS", en bon Français un manager de contenu de site internet, avec back office mais sans base de données.

> Ces examples fonctionnent avec `PHP Version 5.1.3RC4-dev` les pages perso de *free.fr*
>
> Mais j'ai eu des soucis avec des pages perso crées récemment au niveau connexion mysql. J'ai du passer à `PHP 5.6.34`
