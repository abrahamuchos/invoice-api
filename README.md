# Invoice

This is an experimental API to manage invoices and clients. All this by managing your users (they must log in).

The API handles user permissions, thus allowing only the administrator (*admin*) to do certain actions against a basic user.


## ✅ Features

- Show users (only for admin)
- Show customers
- Show invoices
- Show customers with filters
- Show invoices with filters
- Create customers
- Create invoices
- Crete users (only for admin)
- Update customers
- Update invoices
- Delete customers (only for admin)
- Delete invoices (only for admin)
## ⚙️ Tech Stack

- Laravel 10.10
- Postgre 14.12


## 💾 Installation

Install and run

1. Clone and move to folder
```bash
$ git clone git@github.com:abrahamuchos/invoice-api.git
$ cd invoice-api
```

2. Install dependecies
```bash
$  composer install
```

3. Create a copy of the `.env.example` file and rename it to `.env`. Next, configure the necessary environment variables.

4. Generate an application key by running `php artisan key:generate`.

5. Run `php artisan migrate` to create the database tables.

6. Run `php artisandb:seed` to create dummy data and admin user.

7. Run `php artisan serve` to start the Laravel development server.
## Environment Variables

To run this project, you will need to add the following environment variables to your .env file

```
APP_FRONTEND_URL

DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD

MAIL_MAILER
MAIL_HOST
MAIL_PORT
MAIL_USERNAME
MAIL_PASSWORD
MAIL_FROM_ADDRESS
```

## Docs

[Documentation Invoice API - Postman](https://documenter.getpostman.com/view/6168326/2sAYJ3DLjd)

[Invoice API Collection - Postman](https://www.postman.com/abrahamuchos/workspace/public-projects/collection/6168326-d8efea80-34d0-433d-b1d8-a5254fedb48c?action=share&creator=6168326)

You can find a .json with the endpoints in `/docs/Invoice API v1.0.0.postman_collection.json`

## 🧑‍💻 Authors

- [@abrahamuchos](https://github.com/abrahamuchos)
- [Contact mail](mailto:j.abraham29@gmail.com)


## 📄 License

[MIT](https://choosealicense.com/licenses/mit/)

