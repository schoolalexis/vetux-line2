# Evil User Story

_**Evil User Story** d'après la proposition de *schoolalexis**_

"_En tant que **personne malveillante**, je veux **avoir un accès administrateur** afin d'**exploiter les avatanges administrateurs sur le service**_"

**Contre-mesure** : En tant que **développeur**, afin d'**empêcher des personnes malveillantes qui voudrait avoir accès aux avantages administrateur** (dans le cas de l'application Vetux Line) **je sécurise l'accès aux avantages et services administrateurs, aux utilisateurs ayant le role "ADMIN" uniquement**.
Pour cela, j'utilise le bundle `Sensio` permettant à l'aide du système d'annotation de restreindre l'accès à certaines routes sous condition (ici la condition du rôle "ROLE_ADMIN").

Exemple :

```php
// src/Controller/AdminController.php
[...]

/**
 * @IsGranted("ROLE_ADMIN")
 */
#[Route('/', name: 'admin_')]
class AdminController extends AbstractController
{
    [...]
```
Cette solution permettant d'assurer l'accès aux avantages administrateur, uniquement à ceux possédant le rôle adéquat.

Ainsi un **individu malveillant** ne peut pas avoir accès à ces avantages même s'il obtient un compte utilisateur lambda.