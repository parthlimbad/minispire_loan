# Minispire Loan

Minispire Loan is a digital platform for providing loans to its users.

## Description

Minispire Loan is backend RESTFUL API provider having following 2 user roles:

-   Client (Customer) User

-   Admin User

### Client / Customer User actions:

-   Can register to the system.
-   Can login to the system.
-   Can logout of the system.
-   Can create the loan with weekly repayments
-   Can reyap the loan repayment.
-   Can see list of the their own loans only.

### Admin User actions:

-   Can login to the system
-   Can logout of the system.
-   Can update the loan status (approve or pending)

### Installation and Run Project

**NOTE** : It is expected that Apache and MySQL server are insalled in the system.

**INSTALLATION :**

```
- git clone https://github.com/parthlimbad/minispire_loan.git
- cd minispire_loan
- composer install
```

-   Create Empty Database with name same as DB_DATABASE value in .env file :

```
- php artisan create:database
```

-   Migrate the database

```
-   php artisan migrate
```

-   Install and configure dependencies and seed necessary data:

```
- php artisan passport:install
- php artisan passport:keys
- php artisan db:seed --class=RoleSeeder
- php artisan db:seed --class=AdminSeeder
```

**RUN THE PROJECT :**

```
- php artisan serve
```

### Minispire Loan Postman Collection:

Minispire Loan Postman Collection (_Minispire Loan.postman_collection.json_) is included in this repo containing all API requests for above mentioned Client and Admin Actions.

**RUN TEST CASES:**

```
- php artisan test  --testsuite=Feature --filter UserTest
- php artisan test  --testsuite=Feature --filter LoanTest
```
