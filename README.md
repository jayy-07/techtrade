# TechTrade

TechTrade is an online marketplace designed for buying, selling, and trading tech gadgets. The platform provides a seamless experience for users to engage in tech transactions, offering features such as user registration, product listings, order management, and secure payment processing through Paystack.

![image](https://github.com/user-attachments/assets/509e983b-37db-4092-a4b4-972d2891cc89)
![Screenshot 2025-03-13 220047](https://github.com/user-attachments/assets/7fd7481b-5606-4d90-ba02-ac871d82e52c)
![Screenshot 2025-03-13 220155](https://github.com/user-attachments/assets/0202d883-fb66-461d-b657-d959a9af280b)




## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Database Schema](#database-schema)
- [Payment Integration](#payment-integration)
- [Error Handling](#error-handling)
- [Contributing](#contributing)
- [License](#license)

## Features

- **User Authentication**: Register and login functionality for users.
- **Product Management**: Browse and manage tech products.
- **Order Management**: View and manage orders.
- **Secure Payments**: Integrated with Paystack for secure transactions.
- **Trade-In Program**: Trade old devices for credit towards new purchases.

## Installation

### Prerequisites

- PHP 8.2 or higher
- MariaDB 10.4 or higher
- Composer for PHP dependency management
- A web server (e.g., Apache, Nginx)

### Steps

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/yourusername/techtrade.git
   cd techtrade
   ```

2. **Install Dependencies:**
   Use Composer to install PHP dependencies.
   ```bash
   composer install
   ```

3. **Database Setup:**
   - Create a new database in MariaDB.
   - Import the SQL dump file located at `db/techtrade.sql` to set up the database schema and initial data.

4. **Configuration:**
   - Update the database credentials in `settings/db_cred.php`.
   - Set the environment and Paystack keys in `settings/config.php` (startLine: 1, endLine: 19).

5. **Run the Application:**
   - Start your web server and navigate to the project directory.
   - Access the application via `http://localhost/techtrade`.

## Usage

- **Homepage**: Access the homepage to explore the marketplace and its features.
- **User Registration**: Register a new account to start buying or selling tech products.
- **Product Listings**: Browse available products and add them to your cart.
- **Checkout**: Proceed to checkout and complete your purchase using Paystack.

## Database Schema

The database schema includes tables for users, products, orders, payments, and more. Key tables include:

- **users**: Stores user information and roles.
- **products**: Contains product details and associations with brands.
- **orders**: Manages order records and their statuses.
- **payments**: Tracks payment transactions and statuses.

Refer to the SQL dump file `db/techtrade.sql` for the complete schema (startLine: 1, endLine: 601).

## Payment Integration

The project uses Paystack for secure payment processing. Key files involved in payment integration include:

- **Payment Initialization**: `actions/initialize_payment.php` (startLine: 1, endLine: 71)
- **Checkout Process**: `js/checkout.js` (startLine: 1, endLine: 53)

## Error Handling

Error handling is implemented throughout the application to ensure smooth operation. Common errors are logged in `php-error.log` (startLine: 1, endLine: 35).

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request for any improvements or bug fixes.

## License

This project is licensed under the MIT License. See the `LICENSE` file for more details.
