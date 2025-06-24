# Use the official Nginx image from Docker Hub
FROM nginx:alpine

# Remove the default Nginx configuration file
RUN rm /etc/nginx/conf.d/default.conf

# Copy the custom Nginx configuration file from the docker directory
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Copy the static website files from the public directory to Nginx's web root
COPY public/ /usr/share/nginx/html

# Ensure proper permissions for Nginx user
# The nginx user in alpine is 'nginx', group 'nginx', uid/gid 101
RUN chown -R nginx:nginx /usr/share/nginx/html && chmod -R 755 /usr/share/nginx/html

# Expose port 80 (Nginx default)
EXPOSE 80

# Command to run Nginx in the foreground
CMD ["nginx", "-g", "daemon off;"]
