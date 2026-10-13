# MediStore · PHP & MySQL

A responsive medical store management website with a sales dashboard, medicine inventory, supplier directory, and point-of-sale billing with printable invoices. Billing checks stock and reduces inventory in a database transaction.

## Run locally

1. Install PHP 8+ with PDO MySQL and MySQL 8+ (XAMPP, WAMP, or a similar local stack works).
2. Start MySQL and import `database.sql` using phpMyAdmin or the MySQL client. It creates the `medistore` database and a few sample suppliers and medicines.
3. Edit `config.php` and set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` for your machine.
4. Put this folder in your PHP server's document root, then open `http://localhost/medical-store-management/`. Alternatively, from this directory run `php -S localhost:8000` and open `http://localhost:8000`.

## Pages

- **Overview** — daily sales, inventory counts, low-stock watch, and recent bills.
- **Billing** — search/add medicines, edit quantities, apply a discount and tax rate, select payment method, and create a printable invoice. Stock is verified and updated when the bill is saved.
- **Medicines** — add, edit, search, and archive stock items with expiry and supplier details.
- **Suppliers** — add, edit, and archive partners with contact, address, and GSTIN details.

This is a starter application: set strong database credentials and review your local tax and invoice requirements before using it for live transactions. Sample products and contacts are fictional.
