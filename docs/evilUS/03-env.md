# Evil User Story

_**Evil User Story** d'après la proposition de **schoolalexis**_

"_En tant que **personne malveillante**, je veux **avoir accès à la base de données** afin d'**exploiter les mots de passe et autres données ci-trouvant **_"

**Contre-mesure** : En tant que **développeur**, afin d'**empêcher des personnes malveillantes qui souhaitent, à partir de la base de données, se connecter aux comptes des utilisateurs et exploiter leurs mots de passe** (dans le cas de l'application Vetux Line) **je sécurise le fichier `.env` qui contient l'identifiant de connexion à la base de données**.
Pour cela, je créer un fichier `.env.local`, dans lequel il va se trouver les données sensibles. Ce fichier, rend le fichier d'environnement encore plus sécurisé, car celui-ci n'est pas pris en compte dans le logiciel de versionnage Git.

Exemple : 

```bash
// .gitignore

###> symfony/framework-bundle ###
/.env.local
/.env.local.php
/.env.*.local
/config/secrets/prod/prod.decrypt.private.php
/public/bundles/
/var/
/vendor/
###< symfony/framework-bundle ###
```

```bash
DATABASE_URL="mysql://<user>:<password>@127.0.0.1:3306/<database>?serverVersion=<version>"
```

Ainsi même si par inadvertance le fichier `.env` vient à être envoyé vers un dépôt distant, les données sensibles ne seront pas envoyées.