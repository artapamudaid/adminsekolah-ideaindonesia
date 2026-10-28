FROM dunglas/frankenphp:php8.4-alpine

# Patch all Alpine packages to eliminate CVEs
RUN apk update && apk upgrade --no-cache \
    && apk add --no-cache ca-certificates curl unzip \
    && rm -rf /var/cache/apk/* /tmp/*

# Install required PHP extensions for CodeIgniter 3
RUN install-php-extensions \
	mysqli \
	pdo_mysql \
	gd \
	intl \
	zip \
	opcache

# Replace the default Caddyfile with our own
COPY Caddyfile /etc/frankenphp/Caddyfile
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Copy the application code
COPY . /app/public

# Ensure CodeIgniter 3 system files are present if not included in repository
RUN if [ ! -f /app/public/system/core/CodeIgniter.php ]; then \
        curl -fsSL https://github.com/bcit-ci/CodeIgniter/archive/refs/tags/3.1.13.zip -o /tmp/codeigniter.zip \
        && unzip -q /tmp/codeigniter.zip -d /tmp \
        && rm -rf /app/public/system \
        && cp -R /tmp/CodeIgniter-3.1.13/system /app/public/system \
        && rm -rf /tmp/codeigniter.zip /tmp/CodeIgniter-3.1.13; \
    fi

# Set permissions
RUN mkdir -p /app/public/application/logs /app/public/media /app/public/uploads /app/public/system/sessions \
    && chown -R www-data:www-data /app/public
