# Docker Setup for Ultimate POS

This Docker setup will help you run the Ultimate POS Laravel application locally using Docker containers.

## Prerequisites

- Docker installed on your system
- Docker Compose installed on your system

## Quick Start

1. **Run the setup script:**
   ```bash
   ./docker-setup.sh
   ```

   This script will:
   - Create a `.env` file from `.env.example` if it doesn't exist
   - Build and start all Docker containers
   - Install Composer dependencies
   - Generate Laravel application key
   - Run database migrations
   - Clear and cache Laravel configurations
   - Set proper file permissions

2. **Access your application:**
   - **Ultimate POS Application:** http://localhost:8080
   - **PHPMyAdmin (Database Management):** http://localhost:8081
     - Server: `db`
     - Username: `root`
     - Password: `root`

## Manual Setup (Alternative)

If you prefer to set up manually:

1. **Create environment file:**
   ```bash
   cp .env.example .env
   ```

2. **Build and start containers:**
   ```bash
   docker-compose build
   docker-compose up -d
   ```

3. **Install dependencies and setup Laravel:**
   ```bash
   docker-compose exec app composer install
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate
   docker-compose exec app php artisan config:cache
   docker-compose exec app php artisan route:cache
   docker-compose exec app php artisan view:cache
   ```

## Services Included

- **app:** PHP 8.1-FPM with required extensions
- **webserver:** Nginx web server
- **db:** MySQL 5.7 database
- **redis:** Redis for caching and sessions
- **phpmyadmin:** Database management interface

## Database Configuration

The database is configured with these credentials:
- **Host:** `db`
- **Database:** `ultimate_pos`
- **Username:** `ultimate_pos_user`
- **Password:** `secret`

## Useful Commands

- **View logs:** `docker-compose logs -f`
- **Stop containers:** `docker-compose down`
- **Restart containers:** `docker-compose restart`
- **Access app container:** `docker-compose exec app bash`
- **Access database container:** `docker-compose exec db mysql -u root -p`

## File Structure

```
├── Dockerfile                 # PHP-FPM container configuration
├── docker-compose.yml        # Multi-container orchestration
├── nginx/
│   └── conf.d/
│       └── default.conf      # Nginx configuration
├── php/
│   └── local.ini             # PHP configuration
├── .env.example              # Environment variables template
└── docker-setup.sh           # Automated setup script
```

## Troubleshooting

If you encounter issues:

1. **Check container status:** `docker-compose ps`
2. **View logs:** `docker-compose logs [service-name]`
3. **Rebuild containers:** `docker-compose build --no-cache`
4. **Reset database:** `docker-compose down -v && docker-compose up -d`

## Development

For development, you can modify files locally and changes will be reflected in the containers due to volume mounts.

## Security Note

This setup is intended for local development. For production deployment, please review and update security configurations, especially database passwords and environment variables.
