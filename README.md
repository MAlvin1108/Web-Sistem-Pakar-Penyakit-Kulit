# Web-Sistem-Pakar-Penyakit-Kulit
# Web-Sistem-Pakar-Penyakit-Kulit

This is a web-based expert system for diagnosing skin diseases. The application is built using the Laravel framework with Inertia.js and React, providing a modern and interactive user experience. It allows for the management of diseases, symptoms, and diagnostic rules, as well as a user-facing interface for performing a diagnosis.

## Features

Based on the application's code, here are its key features:

* **User Management**: The system includes a user authentication system with roles and permissions, powered by Laravel Breeze and Spatie/laravel-permission.
* **Disease Management**: Administrators can manage and view a list of skin diseases, each with its own data and associated symptoms.
* **Symptom Management**: The system includes a module for managing symptoms (`gejala`) which are used in the diagnostic process.
* **Rule-based Diagnosis**: The core of the system uses a set of rules to determine a diagnosis based on the symptoms selected by the user.
* **Diagnosis History**: The system keeps a history of diagnoses performed, allowing users and administrators to view past diagnostic results.

## Technology Stack

* **Framework**: Laravel
* **Frontend**: Inertia.js, React, Tailwind CSS
* **Database**: MySQL (configured in `.env` file)
* **Dependencies**:
    * `tightenco/ziggy` for route handling in Inertia.js
    * `livewire/livewire` for dynamic frontend components
    * `spatie/laravel-permission` for role-based access control
    * `jantinnerezo/livewire-activitylog` for activity logging

## Installation and Setup

To get this project up and running on your local machine, follow these steps:

1.  **Clone the repository**:
    ```bash
    git clone [https://github.com/malvin1108/web-sistem-pakar-penyakit-kulit.git](https://github.com/malvin1108/web-sistem-pakar-penyakit-kulit.git)
    cd web-sistem-pakar-penyakit-kulit
    ```

2.  **Install PHP dependencies**:
    ```bash
    composer install
    ```

3.  **Install Node.js dependencies**:
    ```bash
    npm install
    ```

4.  **Set up the environment file**:
    Create a `.env` file by copying the `.env.example` file.
    ```bash
    cp .env.example .env
    ```

5.  **Generate application key**:
    ```bash
    php artisan key:generate
    ```

6.  **Configure the database**:
    In your `.env` file, configure your database connection details (e.g., `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

7.  **Run migrations and seeders**:
    This will create the necessary tables and populate the database with initial data, including a default admin user.
    ```bash
    php artisan migrate --seed
    ```

8.  **Run the application**:
    Start the Laravel development server and the Vite server.
    ```bash
    php artisan serve
    npm run dev
    ```

The application will be available at `http://localhost:8000`.

## Default Credentials

A default admin user is created during the seeding process. You can use the following credentials to log in:

* **Username**: `admin`
* **Password**: `password`

