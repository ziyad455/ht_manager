# Use official PHP image
FROM php:8.3-cli

# Set working directory
WORKDIR /app

# Copy project files into the container
COPY . .

# Install any needed PHP extensions if required (you can add mysqli, pdo_mysql, etc.)

# Start PHP's built-in server
CMD ["php", "-S", "0.0.0.0:8080"]
