# Vetux-LineV2
Mise en pratique des connaissances sur :
- POO
- Symfony

Prérequis : base de la programmation, PHP 7.0 > sur votre mahcine de dev.

# Projet
Ce projet a pour but de mettre en oeuvre les connaissances en Symfony, au travers d'un site web traitant de différentes manières la fusion de fichiers CSV.

# Introduction

Dès le début du projet, il est donné un document, donnant les spécificités demandés pour ce projet.
De ce document, se dégage deux étapes.

# Etape 1 : "_La gestion de la fusion !_"

Dans un premier temps, il est spécifié plusieurs points :

- Upload fichier && uniquement des fichiers .csv
````php
// src/Form/CSVType.php

     $builder
            ->add('choice', ChoiceType::class, [
                "label" => "Type de fusion",
                "choices" => [
                    "Sequentiel" => "Sequentiel",
                    "Entrelacer" => "Entralacer"
                ]
            ])
            ->add('mergeFileName', TextType::class, [
                "label" => "Nom du fichier, de la fusion",
                "constraints" => [
                    new NotBlank()
                ]
            ])
[...]

    'mimeTypes' => [
        'text/x-csv',
        'text/csv',
        'application/x-csv',
        'application/csv',],
    "mimeTypesMessage" => "Fichier .csv uniquement !"
    
[...]
````

- Création d'un utilisateur 'admin'

```php
// src/DataFixtures/AppFixtures.php

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = new Admin();
        $user->setUsername("admin");
        $user->setPassword("admin");
        $manager->persist($user);
        $manager->flush();
    }
}

```

- Fusion deux fichiers .csv (en séquentiel)

```php
// src/Controller/AdminController.php

   #[Route('/merge', name: "admin_merge", methods: "GET")]
    public function merge(Request $request): Response
    {
        $mergeForm = $this->createForm(CSVType::class);
        $mergeForm->handleRequest($request);

        if ($mergeForm->isSubmitted() && $mergeForm->isValid())
        {
            $file0 = $mergeForm['file0']->getData();
            $file1 = $mergeForm['file1']->getData();
[...]
```
