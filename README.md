# Gestion de Bibliothèque - Projet Final

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)

## 📚 À propos du projet

Application web de gestion de bibliothèque développée avec le framework Laravel. Cette application permet de gérer une bibliothèque avec des fonctionnalités complètes pour les utilisateurs et les administrateurs.

### 🏗️ Architecture Front-end
- **Blade** : Moteur de template intégré à Laravel pour le rendu des vues
- **Front-office** : Espace public et utilisateur avec une interface conviviale
- **Back-office** : Espace d'administration sécurisé pour la gestion complète

## ✨ Fonctionnalités

### 👥 Gestion des utilisateurs
- Ajout des utilisateurs par l'administrateur uniquement
- Authentification sécurisée
- Profil utilisateur personnalisable
- Système de rôles (administrateur, gestionnaire, utilisateur)
- Tableau de bord personnalisé selon le rôle

### 📖 Gestion des ouvrages (Front-office)
- Catalogue des livres avec recherche avancée
- Filtrage par catégorie, auteur, disponibilité
- Détails complets des ouvrages avec photos

### 🛠️ Administration (Back-office)
- Gestion complète du catalogue
- Suivi des emprunts et retours
- Gestion des utilisateurs et des rôles
- Tableau de bord administratif avec statistiques
- Détails complets des livres (auteur, éditeur, catégorie, etc.)
- Gestion des stocks

### 🔄 Gestion des emprunts
- Emprunt de livres
- Système de réservation
- Gestion des retours
- Historique des emprunts

### ⚖️ Gestion des amendes
- Calcul automatique des pénalités de retard
- Paiement en ligne via PayPal et Stripe
- Suivi des paiements

### 💬 Système de commentaires
- Notation des livres
- Avis et commentaires des utilisateurs

## 🛠️ Prérequis

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL 5.7+
- Serveur web (Apache/Nginx)

## 🚀 Installation

1. **Cloner le dépôt**
   ```bash
   git clone [URL_DU_DEPOT]
   cd Projet_Final
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances JavaScript**
   ```bash
   npm install
   npm run dev
   ```

4. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurer la base de données**
   - Créer une base de données MySQL
   - Mettre à jour le fichier `.env` avec les informations de connexion

6. **Exécuter les migrations et les seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Démarrer le serveur**
   ```bash
   php artisan serve
   ```

## 🔐 Comptes de test

- **Administrateur**
  - Email: admin@example.com
  - Mot de passe: admin123

- **Utilisateur standard (client)**
  - Email: client@example.com
  - Mot de passe: client123

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 👥 Auteur

- Youssou GNING

## 🙏 Remerciements

- À l'équipe Laravel pour ce formidable framework
- À tous les contributeurs qui ont rendu ce projet possible
