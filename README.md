# Project Setup Guide

This guide will walk you through setting up and running the project using Docker.

## Prerequisites

Ensure you have the following installed on your system:
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## Installation & Setup

Follow these steps to get the project up and running:

### Step 1: Start the Containers
Run the following command to start the necessary services in the background:
```sh
docker-compose up -d
```

### Step 2: Run Database Migrations
```
docker-compose exec app php bin/console doctrine:migrations:migrate
```

### Step 3: Create an Admin User
```
docker-compose exec app php bin/console app:create-user admin@admin.com 1234 ROLE_ADMIN
```

### Step 4: Access the Admin Panel
```
http://localhost:8000/admin
```
### Step 5: Admin Login Credentials
Use the following credentials to log in:
- Username: admin@admin.com
- Password: 1234

### Step 6: Manage Posts & Categories
Navigate to:
- http://localhost:8000/admin/post

Here, you can add categories and create news posts.

### Step 7: Browse & Interact
Visit the main site at:
- http://localhost:8000/

Feel free to browse posts and add comments.



