# Pico

[Pico CMS](https://picocms.org/)

"A stupidly simple & blazing fast, flat file CMS. Making the web easy."

> Attention:
>
> - La dernière version date de **2020** ([2.1.4](https://github.com/picocms/Pico/releases/tag/v2.1.4)) mais ce qui nous arrange vus les vieilles version de php disponible sur les pages perso de *free.fr*. En effet pico nécéssite seulement PHP 5.3.6+ et les extensions *dom* and *mbstring*.
> - Avec *free.fr*, pas de SLL donc pas de https, donc les mots de passe sont en clair dans la communication http.

## Démo

- Installation de base:
  - [http://phramusca.free.fr/pico](http://phramusca.free.fr/pico)
- En rajoutant un index.md:
  - [http://phramusca.free.fr/pico-with-content](http://phramusca.free.fr/pico-with-content)

## Installation

- Installation de base:
  - [Télécharger la dernière version de Pico](https://github.com/picocms/Pico/releases/latest)
  - Dézipper
  - Remplacer le contenu du .htaccess par le suivant (pas d'url rewriting chez *free.fr*, et besoin de PHP 5.3.6 minimum)

      ```apache
      php56 1

      # Prevent file browsing
      Options -Indexes -MultiViews
      ```

  - Téléverser (uploader) le tout sur *ftpperso.free.fr* dans /pico (par exemple)
  - Se connecter à http://monpseudo.free.fr/pico
- Ajouter du contenu
  - Simplement placer un fichier markdown dans le dossier `pico/content`, par exemple `myfirstcontent.md` avec par exemple:

  ``````markdown
  # Titre de niveau 1

  ## Titre de niveau 2

  ### Titre de niveau 3

  **Texte en gras** et *italique*, avec du ~~barré~~.

  > Ceci est une citation.

  - Élément 1
  - Élément 2
  - Élément 3

  1. Élément numéroté 1
  2. Élément numéroté 2

  [Lien vers Google](https://www.google.com) et une image :  
  ![Texte alternatif](https://via.placeholder.com/150)

  `Code inline` et un bloc de code :

  ```php
  print("Hello World!")
  ```

  | En-tête 1 | En-tête 2 |
  |-----------|-----------|
  | Cellule 1 | Cellule 2 |
  ``````

- Plus d'infos: [Creating content](https://picocms.org/docs/#creating-content)

### Plugins

- [Liste des plugins](https://picocms.org/plugins/)

- [Pico Editor](https://github.com/astappiev/pico-editor)
  - Apporte un éditeur de fichier markdown en ligne et un gestionnaire de fichiers.
  - Installation:
    - `cd plugins && git clone https://github.com/astappiev/pico-editor.git PicoEditor`
    - Créer un fichier `config/config.yml`:

      ```yaml
      # Pico Editor Configuration
      PicoEditor:
        enabled: true                           # Activer ou non le plugin.
        password: SHA512-HASHED-PASSWORD        # Spécifier le mot de passe voulu, hashé ne SHA512 (https://sha512.online/).
        url: editor                             # Pour changer l'url de l'éditeur.
      ```
    - Créer un dossier `sessions` à la **racine** du site (/).
    - Téléverser (uploader) le tout sur *ftpperso.free.fr* dans /pico-with-editor (par exemple)
    - Se connecter à [http://monpseudo.free.fr/pico-with-editor/?editor](http://phramusca.free.fr/pico-with-editor/?editor)

### Themes

- [Liste des thèmes](https://picocms.org/themes/)
