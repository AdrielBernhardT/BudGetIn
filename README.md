<p align="center">
  <img src="public/images/logo/logo.png" height="100" alt="BudGetIn Logo" />
</p>

<p align="center">A website for personal finance management application for tracking income and expenses, organizing transactions, investment, and monitoring financial status efficiently.</p> 
  <p align="center">
    <a href="LICENSE" target="_blank"><img src="https://img.shields.io/badge/license-MIT-green" alt="Package License" /></a>
    <a href="https://laravel.com/" target="_blank"><img src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel" alt="Laravel" /></a>
    <a href="https://www.php.net/" target="_blank"><img src="https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php" alt="PHP"/></a>
    <a href="https://vite.dev/" target="_blank"><img src="https://img.shields.io/badge/Vite-7.0+-646CFF?logo=vite" alt="Vite"/></a>
    <a href="https://vite.dev/" target="_blank"><img src="https://img.shields.io/badge/Tailwind_CSS-4.0+-38B2AC?logo=tailwind-css" alt="Tailwind"/></a>
    <a href="https://alpinejs.dev/" target="_blank"><img src="https://img.shields.io/badge/Alpine.js-3.14+-8BC0D0?logo=alpine.js" alt="Alpine.js"/></a>
  </p>

## Description

BudGetIn is a website designed to help users manage their personal finances in a simple, organized, and efficient way. By allowing users to record income and expenses, categorize transactions, track investment, and manage financial data, BudGetIn provides a clear overview of their financial activities.

The application records and visualizes financial data over different time periods, enabling users to monitor spending patterns, track income, and better understand their financial condition. Through interactive reports and summaries, users can identify unnecessary expenses, evaluate their financial habits, and make more informed budgeting decisions.

In addition to transaction recording and financial monitoring, BudGetIn aims to encourage responsible financial management. By presenting clear financial insights and historical records, the application helps users develop better spending habits, improve budgeting practices, and achieve greater financial stability over time.

## Features

- **User Authentication**  
  Secure account management with features including:
  - User registration
  - Login & logout
  - Password change
  - Account recovery

- **Profile Management**  
  Manage personal profile information and account settings.

- **Multi-Account Management**  
  Organize finances across multiple accounts, including:
  - Bank accounts
  - E-wallets
  - Cash accounts
  - Separate balance tracking for each account

- **Expense Category Management**
  - Managing custom expense categories
  - Customize budget for every category

- **Income & Expense Tracking**  
  Record and organize financial transactions by:
  - Adding income and expense records
  - Edit income and expense records
  - Delete income and expense records

- **Financial Dashboard**  
  View a comprehensive summary of personal finances, including:
  - Current balance
  - Total income
  - Total expenses
  - Financial Insight
  - Financial Statistic

- **Investment Management**  
  Track and manage investment portfolios by:
  - Recording investment assets
  - Monitoring portfolio growth
  - Setting investment goals and reminders

- **Financial Reports**  
  Export financial reports in PDF format for documentation, analysis, and future reference.

## How It Works

1. Users register or log in to the website.
2. Users add account and categories.
3. Users add income and expense to start financial tracking.
4. Add investment to start track investment.
5. Users can view the report for transactions and investment, and print pdf report.

## Tech Stack

| Category               | Technology                                                    |
| ---------------------- | ------------------------------------------------------------- |
| **Backend**            | PHP 8.2, Laravel 12                                           |
| **Frontend**           | Vite, Tailwind CSS v4, Alpine.js                              |
| **Authentication**     | Laravel Socialite (Google OAuth)                              |
| **UI & Visualization** | ApexCharts, SweetAlert2, Flatpickr, FullCalendar, JSVectorMap |
| **Database**           | MySQL (Development), PostgreSQL (Production)                  |
| **Deployment**         | Heroku                                                        |

## Installation

1. Clone the repository:

```bash
git clone https://github.com/darrentimotius/budgetin.git
cd budgetin
```

2. Install dependencies:

```bash
composer install
npm install
```

3. Run migrations:

```bash
php artisan migrate
```

4. Run the application:

```bash
php artisan serve
```

```bash
npm run dev
```

## Environment Variables

To run this project, you will need to create ```.env``` file in the root folder. You can duplicate ```.env.example```, rename it to ```.env```, and change the database setup to your database preference.

## License

ZAPP! is licensed under [MIT](LICENSE)

## Contributing

Contributions are always welcome!

Feel free to fork this repository, create a new branch, and submit a pull request.


## Authors

Developed by the **BudGetIn Team**.

| Name                         | GitHub Username |
|------------------------------|-----------------|
| Darren Timotius Raphael      | [@darrentimotius](https://github.com/darrentimotius) |
| Fance Satria Nusantara           | [@fancesatria](https://github.com/fancesatria) |
| Helen Febriyanto       | [@helenfebriyanto](https://github.com/helenfebriyanto) |
| Syarifana Amalia Putri  | [@syarifanaamalia](https://github.com/syarifanaamalia) |
| Adriel Bernhard Tanuhariono | [@AdrielBernhardT](https://github.com/AdrielBernhardT) |