# IvoireShop API

## Description
API REST pour la boutique e-commerce IvoireShop — Mode africaine, Abidjan, Côte d'Ivoire.

## Stack technique
- **Framework** : Laravel 11
- **Auth** : Laravel Sanctum
- **BDD** : MySQL 8
<!-- - **Paiement** : CinetPay (Orange Money, MTN, Wave) -->

## Installation
```bash
git clone https://github.com/...
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## API Endpoints
| Méthode | Route | Description |
|---------|-------|-------------|
| POST | /api/v1/auth/login | Connexion |
| GET | /api/v1/products | Liste produits |

## Demo
[ivoireshop.vercel.app](https://ivoireshop.vercel.app)