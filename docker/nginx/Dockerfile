FROM nginx:alpine

# Copy nginx config
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy static assets and PHP vendor from app build
COPY --from=smart_parking_system-app:latest /var/www/html/public /var/www/html/public
COPY --from=smart_parking_system-app:latest /var/www/html/vendor /var/www/html/vendor

# Create storage symlink and directory for uploaded files (shared via volume)
RUN mkdir -p /var/www/html/storage/app/public \
    && ln -s /var/www/html/storage/app/public /var/www/html/public/storage
