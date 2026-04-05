#!/bin/bash

# Ultimate POS Docker Setup Script

echo "🚀 Setting up Ultimate POS with Docker..."

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file from .env.example..."
    cp .env.example .env
    echo "✅ .env file created. Please update it with your configuration if needed."
else
    echo "✅ .env file already exists."
fi

# Build and start containers
echo "🔧 Building and starting Docker containers..."
docker-compose build
docker-compose up -d

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 30

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
docker-compose exec app composer install

# Generate Laravel key
echo "🔑 Generating Laravel application key..."
docker-compose exec app php artisan key:generate

# Run database migrations
echo "🗄️ Running database migrations..."
docker-compose exec app php artisan migrate

# Clear and cache Laravel configurations
echo "🧹 Clearing and caching Laravel configurations..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:clear
docker-compose exec app php artisan view:cache

# Set proper permissions
echo "🔐 Setting proper permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chown -R www-data:www-data /var/www/bootstrap/cache

echo ""
echo "🎉 Ultimate POS is now running!"
echo ""
echo "📱 Application URL: http://localhost:8080"
echo "🗄️ PHPMyAdmin URL: http://localhost:8081"
echo "🔧 Database Host: db"
echo "🔧 Database Name: ultimate_pos"
echo "🔧 Database User: ultimate_pos_user"
echo "🔧 Database Password: secret"
echo ""
echo "📝 To view logs: docker-compose logs -f"
echo "🛑 To stop: docker-compose down"
echo "🔄 To restart: docker-compose restart"
echo ""
echo "💡 You can now access your Ultimate POS application at http://localhost:8080"
