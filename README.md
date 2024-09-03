# Application Symfony de chat

## Installation
1) Cloner le projet : 

```
git clone https://github.com/tombruaire/symfony-chat.git
```

2) Installer les dépendances de Symfony : 

```
cd symfony-chat
```

```
composer install
```

3) Installer les dépendances Node.js :

```
npm install
```

4) Charger les ressources (css, js, img, etc...) : 

```
npm run dev
```

5) Si nécessaire, modifier la version de PHP dans le fichier `.php-version`

6) Créer la base de données dans le fichier `.env` :
- `app` (user)
- `!ChangeMe!` (password)
- `app` (database)

```
php bin/console doctrine:database:create
```

```
php bin/console doctrine:migrations:migrate
```

7) Démarrer l'application : 

```
symfony serv:start
```

8) Accéder au chat
- <a href="http://127.0.0.1:8000">http://127.0.0.1:8000</a>