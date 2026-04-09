# PHASE 1 — Setup Backend Laravel API
## IvoireShop · Développeur : Ouattara Gnonhoua N'Golo

---

## ÉTAPE 1 — Créer le projet Laravel

```bash
# 1. Créer le projet
composer create-project laravel/laravel ivoireshop-api
cd ivoireshop-api

# 2. Installer les dépendances essentielles
composer require laravel/sanctum
composer require intervention/image
composer require fruitcake/laravel-cors
composer require spatie/laravel-sluggable

# 3. Publier la config Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# 4. Initialiser Git proprement
git init
git add .
git commit -m "chore: initial Laravel project setup"
```

---

## ÉTAPE 2 — Configurer le fichier .env

```env
APP_NAME=IvoireShop
APP_ENV=local
APP_KEY=           # généré automatiquement
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ivoireshop_db
DB_USERNAME=root
DB_PASSWORD=

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost:3000

# Frontend URL (Next.js)
FRONTEND_URL=http://localhost:3000

# Mail (pour les notifications commandes)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="noreply@ivoireshop.ci"
MAIL_FROM_NAME="IvoireShop"

# Storage
FILESYSTEM_DISK=public
```

---

## ÉTAPE 3 — Configurer CORS (config/cors.php)

```php
<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000')],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

---

## ÉTAPE 4 — Configurer Sanctum (config/sanctum.php)

```php
// Dans config/sanctum.php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    Sanctum::currentApplicationUrlWithPort()
))),
```

---

## ÉTAPE 5 — Modifier app/Http/Kernel.php

```php
// Dans la section api middleware
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

---

## ÉTAPE 6 — Créer la base de données

```bash
# Dans MySQL
CREATE DATABASE ivoireshop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Tester la connexion
php artisan db:show
```

---

## ÉTAPE 7 — Créer les Models + Migrations d'un seul coup

```bash
# Créer tous les modèles avec migration, factory, seeder, controller
php artisan make:model Category   -mfs
php artisan make:model Product    -mfs
php artisan make:model Cart       -mfs
php artisan make:model CartItem   -m
php artisan make:model Order      -mfs
php artisan make:model OrderItem  -m
php artisan make:model Address    -m

# Créer les controllers
php artisan make:controller Api/Auth/AuthController       --api
php artisan make:controller Api/Admin/ProductController   --api
php artisan make:controller Api/Admin/CategoryController  --api
php artisan make:controller Api/Admin/OrderController     --api
php artisan make:controller Api/Admin/DashboardController
php artisan make:controller Api/Client/CartController
php artisan make:controller Api/Client/CheckoutController
php artisan make:controller Api/Client/OrderController    --api

# Créer les Form Requests (validation)
php artisan make:request Auth/RegisterRequest
php artisan make:request Auth/LoginRequest
php artisan make:request Product/StoreProductRequest
php artisan make:request Product/UpdateProductRequest
php artisan make:request Order/CheckoutRequest

# Créer les API Resources (formatage réponses)
php artisan make:resource ProductResource
php artisan make:resource ProductCollection
php artisan make:resource CategoryResource
php artisan make:resource OrderResource
php artisan make:resource UserResource

# Créer les Services (logique métier)
mkdir -p app/Services
touch app/Services/CartService.php
touch app/Services/OrderService.php
touch app/Services/PaymentService.php
touch app/Services/ImageService.php

# Créer les Middlewares
php artisan make:middleware IsAdmin
php artisan make:middleware IsClient

# Créer les Notifications
php artisan make:notification OrderConfirmed
php artisan make:notification OrderShipped

echo "✅ Tous les fichiers créés !"
```

---

## ÉTAPE 8 — Vérifier la structure finale

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── Auth/AuthController.php
│   │       ├── Admin/
│   │       │   ├── ProductController.php
│   │       │   ├── CategoryController.php
│   │       │   ├── OrderController.php
│   │       │   └── DashboardController.php
│   │       └── Client/
│   │           ├── CartController.php
│   │           ├── CheckoutController.php
│   │           └── OrderController.php
│   ├── Middleware/
│   │   ├── IsAdmin.php
│   │   └── IsClient.php
│   ├── Requests/
│   │   ├── Auth/
│   │   ├── Product/
│   │   └── Order/
│   └── Resources/
│       ├── ProductResource.php
│       └── OrderResource.php
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Product.php
│   ├── Cart.php
│   ├── CartItem.php
│   ├── Order.php
│   ├── OrderItem.php
│   └── Address.php
├── Services/
│   ├── CartService.php
│   ├── OrderService.php
│   ├── PaymentService.php
│   └── ImageService.php
└── Notifications/
    ├── OrderConfirmed.php
    └── OrderShipped.php
```

---

## ÉTAPE 9 — Commandes finales phase 1

```bash
# Générer la clé d'application
php artisan key:generate

# Créer le lien storage public
php artisan storage:link

# Démarrer le serveur
php artisan serve

# Commit Git
git add .
git commit -m "feat: setup project structure - models, controllers, services"
git push origin main
```

---

## ✅ CHECKLIST PHASE 1

- [ ] Projet Laravel créé
- [ ] Dépendances installées (Sanctum, Intervention, CORS)
- [ ] Fichier .env configuré
- [ ] CORS configuré
- [ ] Base de données créée
- [ ] Tous les Models créés
- [ ] Tous les Controllers créés
- [ ] Tous les Form Requests créés
- [ ] Tous les API Resources créés
- [ ] Services créés
- [ ] Middlewares créés
- [ ] Storage link créé
- [ ] Commit Git initial
