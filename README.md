# À vos claviers (V2)

Application Laravel exécutée avec [Laravel Sail](https://laravel.com/docs/sail) :
un seul environnement Docker, le code du dépôt est **monté** dans le conteneur.
Une modification de fichier est visible tout de suite, sans reconstruire quoi que ce soit.

Prérequis : Docker (sous Windows : WSL2). Pas besoin d'installer PHP, Composer ni Node.

## Première installation

```sh
git clone <url-du-depot> a_vos_claviers_V2
cd a_vos_claviers_V2

# 1. Dépendances PHP (conteneur jetable : le dossier vendor/ n'existe pas encore)
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/app" -w /app composer:2 \
    install --ignore-platform-reqs --no-scripts

# 2. Configuration
cp .env.example .env

# 3. Démarrage (le premier démarrage construit l'image : quelques minutes)
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate

# 4. Front
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

L'application répond sur http://localhost:8000.

Astuce : `alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'`
permet d'écrire `sail ...` à la place de `./vendor/bin/sail ...`.

## Au quotidien

```sh
sail up -d          # démarrer
sail npm run dev    # recompilation automatique du front (laisser tourner)
# ... modifier un fichier, rafraîchir le navigateur ...
sail test           # lancer les tests avant de pousser
sail stop           # arrêter
```

| Besoin | Commande |
|---|---|
| Commande Artisan | `sail artisan ...` |
| Ajouter une dépendance PHP | `sail composer require ...` |
| Terminal dans le conteneur | `sail shell` |
| Tout supprimer, base comprise | `sail down -v` |

## Appli exposée (réseau de l'établissement)

Sail est un outil de **développement** : à réserver à un réseau interne ou à une démonstration,
pas à un site ouvert à tous sur Internet. Dans le `.env` du serveur, **avant le premier démarrage** :

```
APP_ENV=production
APP_DEBUG=false
APP_PORT=80
DB_PASSWORD=<un mot de passe solide>
```

(MariaDB enregistre le mot de passe dans son volume à la création : le changer ensuite
demande `sail down -v`, qui efface la base.)

```sh
sail up -d
sail artisan key:generate
sail artisan migrate --force
sail npm ci && sail npm run build
```

Mise à jour : `git pull`, puis si besoin `sail composer install`, `sail artisan migrate --force`,
`sail npm run build`.

Les modifications de fichiers sur le serveur sont visibles immédiatement : il n'y a pas
d'étape de déploiement, donc pas de garde-fou.
