# 📝 Blog PHP - Système de Gestion de Contenu

Un moteur de blog robuste et léger construit en PHP natif (Vanilla PHP), conçu pour offrir une gestion simplifiée d'articles avec une séparation claire entre la logique métier et la présentation.

## 🚀 Fonctionnalités Clés

### 🔐 Espace Administrateur (Back-Office)
- **Gestion Complète des Articles (CRUD) :** Création, lecture, mise à jour et suppression d'articles.
- **Système d'Upload d'Images :** Téléchargement sécurisé d'images de couverture avec validation (taille, format).
- **Gestion des Slugs SEO :** Génération automatique de slugs pour des URLs optimisées.
- **Tableau de Bord :** Vue d'ensemble pour la gestion du contenu.

### 👥 Espace Utilisateur (Front-Office)
- **Consultation :** Lecture fluide des articles avec mise en page dédiée.
- **Authentification :** Système complet d'inscription et de connexion sécurisé.
- **Gestion des Rôles :** Distinction stricte entre les rôles `Admin` et `User`.

### 🛠 Aspects Techniques
- **Architecture Propre :** Utilisation de `ob_start()` pour un système de layouts flexible.
- **Sécurité :** Requêtes préparées (PDO) contre les injections SQL et protection des accès par session.
- **Messages Flash :** Retour visuel instantané pour les actions utilisateur (succès, erreur, info).

## 🛠 Stack Technique

- **Langage :** PHP 8.1+ (utilisation des Enums et Match expressions)
- **Base de données :** MySQL avec interface PDO
- **Frontend :** HTML5 / CSS3 (Templates modulaires)

## 📦 Installation

1. **Cloner le dépôt :**
   ```bash
   git clone https://github.com/russel-fullstack/php-blog-cfpc.git
   ```

2. **Configuration de la Base de Données :**
   - Créez une base de données MySQL.
   - Importez le fichier SQL (si disponible) ou créez les tables `users` et `articles`.
   - Modifiez `database/database.php` avec vos identifiants.

3. **Serveur Local :**
   Si vous utilisez le serveur intégré de PHP :
   ```bash
   php -S localhost:8000
   ```

## 📂 Structure du Projet

- `app/` : Cœur du système (Enums, Helpers, Logique).
- `database/` : Configuration de la connexion à la base de données.
- `resources/views/` : Fichiers de templates (Layouts et Vues).
- `storage/` : Répertoire de stockage pour les images uploadées.
- `flash.php` : Utilitaire de gestion des messages de session.

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une Issue ou à soumettre une Pull Request pour améliorer ce projet.

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.
