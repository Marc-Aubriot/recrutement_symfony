Déploiement local :
- Copier le repository en local
- Avoir node.js, Yarn et Composer d'installer
- Créer une database : commande dossier> php bin/console doctrine:database:create 
- Créer un fichier .env.local
- modifier la ligne DATABASE_URL="mysql://username:password@127.0.0.1:3306/nomdeladb" pour se connecter à la db
- effectuer les migrations avec dossier> php bin/console make:migration puis dossier> php bin/console doctrine:migrations:migrate
- lancer les serveurs local dossier> symfony server:start et dossier> npm encore run dev-serv

Normalement la database est créée avec des datas pré remplies dont l'admin user
