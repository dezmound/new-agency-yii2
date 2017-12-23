FROM service.base
ADD config/users.php ./config/
ADD controllers/User* ./controllers/
ADD migrations/db_users ./migrations/db_users/
ADD models/User* ./models/
COPY users.php ./
CMD php users.php