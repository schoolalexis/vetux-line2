# Evil User Story

_**Evil User Story** d'après la proposition de **schoolalexis**_

"_En tant que **personne malveillante**, je veux **avoir accès à la base de données** afin d'**exploiter les mots de passes et autres données ci-trouvant**_"

**Contre-mesure** : En tant que **développeur**, afin d'**empêcher des personnes malveillantes qui sauteraient, à partir de la base de données, se connecter aux comptes des utilisateurs et exploiter leurs mots de passes** (dans le cas de l'application Vetux Line) **j'utiliserais le hashage pour protéger les mots de passes**.
Pour cela, deux solutions, sont applicables sur mon projet Vetux Line. Le premier serait d'utiliser la fonction de hashage dès l'appel de la méthode `setPassword` à l'aide de la class `UserPasswordEncoderInterface`. C'est d'ailleur cette solution qui a été appliquée ici. La deuxième solution, quant à elle, est d'utiliser la fonction de hashage, directement dans l'entité `Admin`, à l'aide de la fonction native de PHP `password_hash`.

Exemple :

- Solution n°1 :

```php
<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Admin;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new Admin();
        $user->setUsername("admin");
        $user->setPassword($this->passwordEncoder->encodePassword($user, "admin"));
        $manager->persist($user);
        $manager->flush();
    }
}
```

- Solution n°2

```php
// src/Entity/Admin.php
[...]

    public function setPassword(string $password): self
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);

        return $this;
    }

[...]
```

Les deux solutions donnant un mot de passe hasher à la fin, dans la base de données.

![hash_password](./img/hash_password.png)

Ainsi même si un **individu malveillant** réussissait à récupérer le mot de passe entrée dans la base de données, il ne pourrait pas se connecter à l'application (ici Vetux Line).