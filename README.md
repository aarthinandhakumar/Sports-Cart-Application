# Sports Cart Application

## Overview
The Sports Cart Application is designed to provide an intuitive shopping experience for sports products. It includes both customer and admin interfaces, allowing users to browse products, manage their shopping cart, and for admins to oversee product inventory and order history.

## Technologies Used
- **Backend**: PHP
- **Database**: MySQL
- **API**: RESTful APIs for data interaction

## Prerequisites
Before you begin, ensure you have the following installed:
- [XAMPP](https://www.apachefriends.org/index.html), [WAMP](http://www.wampserver.com/en/), or [MAMP](https://www.mamp.info/en/) (for running a local Apache server with PHP support)
- A web browser (e.g., Chrome, Firefox)

## Installation Steps
1. **Download and Install XAMPP/WAMP/MAMP:**
   - Download XAMPP from [here](https://www.apachefriends.org/index.html) or choose WAMP/MAMP as per your OS.
   - Install the server environment by following the installation instructions on the website.

2. **Setup the Project Folder:**
   - Create and name the project folder.
   - Download or clone the project repository.

3. **Move the Project Folder to the Server Directory:**
   - For XAMPP, move the folder to the `htdocs` directory.
   - For WAMP, move the folder to the `www` directory.
  
4. **Database Connection**
   - The `initDB.sql` seed file contains the SQL script to create and populate the database. It also includes `DROP` statements for re-creating tables if needed.
   - In the `database.php` file, update the database credentials (`host`, `username`, `password`, `dbname`) as per your local setup.

5. **Start the Server:**
   - Open the XAMPP/WAMP/MAMP control panel.
   - Start the Apache server.
   - Start MySQL server

## Key Features
- **User Interfaces**:
  - Homepage with options to switch between Customer and Admin views.
  - Search functionality for products by name or description.
- **Shopping Cart**:
  - Customers can select quantities, add items to their cart, update quantities, or remove items.
  - Alerts are displayed for products that are out of stock.
- **Admin Interface**:
  - Admins can review customers and their order histories.
  - Admins can manage inventory by adding, deleting, or updating products.

## Application URLs
- **Home Page**: http://localhost/sports_cart/index.php
- **Customer View**: Access via the homepage. Different colour and view is rendered.
- **Admin View**: Access via the homepage. Different colour and view is rendered.

## REST API Endpoints
- **Product List API**:
  - JSON: `http://localhost/sports_cart/api/product_list.php?format=json`
  - XML: `http://localhost/sports_cart/api/product_list.php?format=xml`
- **Product by Name API**:
  - JSON: `http://localhost/sports_cart/api/product_by_name.php?name=Basketball&format=json`
  - XML: `http://localhost/sports_cart/api/product_by_name.php?name=fitbit&format=xml`
- **Products by Price Range API**:
  - JSON: `http://localhost/sports_cart/api/product_by_price_range.php?low=50&high=100&format=json`
  - XML: `http://localhost/sports_cart/api/product_by_price_range.php?low=60&high=300&format=xml`

**License**

This project is licensed under the MIT License - see the [LICENSE](License.txt) file for details.

