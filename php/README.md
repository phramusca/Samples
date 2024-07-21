# PhP

Those pieces of code runs with `PHP Version 5.1.3RC4-dev`.

Fait pour fonctionner avec les pages perso de *free.fr* ([phpinfo](http://raphael.camus.free.fr/phpinfo)).

> **ATTENTION** avec *free.fr*:
>
> - *free.fr* n'a toujours pas implémenté SSL, donc **pas de https**, donc les mots de passe sont en clair ! ([Mais ce serait en cours (nouvelle du 24 Mars 2024)...](https://www.busyspider.fr/Actu/news_24743_Free-les-pages-perso-sont-en-cours-de-migration-en-version-securisee-https-comme-annonce-lors-de-la-journee-des-communautes-free.php))
> - **Le mot de passe SQL doit avoir entre 8 et 10 caractères**. Bien que l'interface *free.fr* n'impose pas cette contrainte, il vous sera impossible de vous connecter avec un mot de passe plus long ou court.

- [simple-api-json-file](simple-api-json-file/README.md): une simple API utilisant un simple fichier json comme "base de données"
- [simple-api-mysql](simple-api-mysql/README.md): une simple API avec authentification et base MySQL.
