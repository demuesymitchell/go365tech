FROM wordpress:php8.3-apache

# The base image can end up with both mpm_prefork and mpm_event enabled on
# some platforms (Railway included), which makes Apache refuse to start
# with "More than one MPM loaded." PHP (mod_php) requires the prefork MPM,
# so force that explicitly and make sure the others are off.
RUN (a2dismod mpm_event || true) \
 && (a2dismod mpm_worker || true) \
 && a2enmod mpm_prefork

# Theme + must-use plugin (auto theme activation).
COPY wp-content/themes/threesixfive /var/www/html/wp-content/themes/threesixfive
COPY mu-plugins /var/www/html/wp-content/mu-plugins

# Railway sits behind a proxy that terminates TLS, and assigns the public
# hostname at runtime via RAILWAY_PUBLIC_DOMAIN. This extra config, appended
# to wp-config.php by the base image's entrypoint:
#   - tells WordPress the request was HTTPS even though it arrives as HTTP
#     internally (prevents redirect loops / mixed content)
#   - pins WP_HOME / WP_SITEURL to Railway's public domain when present,
#     so links/admin work immediately without manual DB edits
ENV WORDPRESS_CONFIG_EXTRA="\
if ( isset( \$_SERVER['HTTP_X_FORWARDED_PROTO'] ) && \$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) { \$_SERVER['HTTPS'] = 'on'; } \
if ( getenv('RAILWAY_PUBLIC_DOMAIN') ) { define( 'WP_HOME', 'https://' . getenv('RAILWAY_PUBLIC_DOMAIN') ); define( 'WP_SITEURL', WP_HOME ); }"

EXPOSE 80