# TestTechBack


## Front

Front réalisé avec twig où l'on trouve une barre de navigation
La barre de navigation permets de retourner à l'accueil si l'on clique sur "TechBack" et permets au visiteur de se connecter ou s'inscrire

Les articles sont affichés dynamiquement et sous plusieurs conditions.
Les articles premium se distinguent des articles gratuit par un jeu de couleur (orange) et leurs contenu ne s'affichent pas.
Le contenu d'un article premium s'affichera uniquement si son Id est présente dans les commandes de l'utilisateur connecté.

Un utilisateur non connecté sera automatiquement redirigé vers la page de login lorsque celui-ci essaye d'accéder à un article premium.

Un Utilisateur connecté n'ayant acheté aucun article ne verra pas non-plus le contenu des articles tant qu'il n'aura pas acheté les dits articles.

Lorsqu'un utilisateur connecté clique sur le bouton "Acheter pour consulter" il est redirigé vers la page de checkout pour pouvoir acheter l'article.

## Back

Backoffice classique réalisé en twig permettant un CRUD des différentes entitées.

L'entité Order ne s'y trouve pas car les commandes sont présentes sur le dashboard Stripe.