# Application Symfony de chat

## Installation
1) Cloner le projet : 

```
git clone https://github.com/tombruaire/symfony-chat.git
```

2) Renseigner les valeurs ci-dessous dans le fichier `.env` :
- `app` (user)
- `!ChangeMe!` (password)
- `app` (database)

3) Installer les dépendances de Symfony :

```
composer install
```

4) Créer la base de données :

```
php bin/console doctrine:database:create
```

5) Créer les tables : 

```
php bin/console doctrine:migrations:migrate
```

6) Installer les dépendances Node.js :

```
npm install
```

7) Charger les ressources (css, js, img, etc...) : 

```
npm run dev
```

8) Si nécessaire, modifier la version de PHP dans le fichier `.php-version`


9) Démarrer l'application : 

```
symfony serv:start
```

10) Accéder au chat
- <a href="http://127.0.0.1:8000">http://127.0.0.1:8000</a>