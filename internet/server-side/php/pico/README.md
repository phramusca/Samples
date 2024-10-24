# pico

[Pico](https://picocms.org/) "A stupidly simple & blazing fast, flat file CMS. Making the web easy."

> Attention:
>
> - La dernière version date de **2020** ([2.1.4](https://github.com/picocms/Pico/releases/tag/v2.1.4)) mais ce qui nous arrange vus les vieilles version de php disponible sur les pages perso de *free.fr*. En effet pico nécéssite seulement PHP 5.3.6+ et les extensions *dom* and *mbstring*.
> - Avec *free.fr*, pas de SLL donc pas de https, donc les mots de passe sont en clair dans la communication http.

## Installation

- [Télécharger la dernière version de Pico](https://github.com/picocms/Pico/releases/latest)
- Dézipper
- Remplacer le contenu du .htaccess par le suivant (pas d'url rewriting chez free, et besoin de PHP 5.3.6 minimum)

    ```apache
    php56 1

    # Prevent file browsing
    Options -Indexes -MultiViews
    ```

- Téléverser (uploader) le tout sur *ftpperso.free.fr* dans /pico (par exemple)
- Se connecter à http://monpseudo.free.fr/pico

## Démo

[http://phramusca.free.fr/pico](http://phramusca.free.fr/pico)