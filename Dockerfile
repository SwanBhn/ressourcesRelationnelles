# Étape 1 : Utilise une image PHP avec les extensions nécessaires
FROM php:8.2-cli

# Étape 2 : Installe les dépendances système et les extensions PHP nécessaires pour Symfony
RUN apt-get update && apt-get upgrade -y \
    && apt-get install -y unzip curl \
# Étape 3 et 4 combinées : Installe Symfony CLI et Composer
    && curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash \
    && apt-get install symfony-cli -y \
    && curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php \
    && docker-php-ext-install pdo_mysql

# Étape 5 : Définit le répertoire de travail
WORKDIR /app

# Étape 6 : Copie le code de l'application Symfony dans le conteneur
COPY . /app

# Étape 7 : Installe les dépendances de Symfony via Composer
RUN composer install

# Étape 8 : Commande pour démarrer le serveur Symfony
CMD ["symfony", "server:start","--port=8000"]
