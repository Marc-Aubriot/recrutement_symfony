Déploiement local :
1 - Copier le repository en local
2 - Avoir node.js, Yarn et Composer d'installer
3 - Créer une database : commande $> php bin/console doctrine:database:create 
4 - Créer un fichier .env.local
5 - modifier la ligne DATABASE_URL="mysql://username:password@127.0.0.1:3306/nomdeladb" pour se connecter à la db
6 - effectuer les migrations avec $> php bin/console make:migration puis $> php bin/console doctrine:migrations:migrate
7 - lancer les serveurs local $> symfony server:start et $> npm encore run dev-serv

Normalement la database est créée avec des datas pré remplies dont l'admin user