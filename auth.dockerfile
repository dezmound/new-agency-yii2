FROM service.base
ADD config/auth.php ./config/
ADD controllers/Auth* ./controllers/
ADD migrations/db_auth ./migrations/db_auth/
ADD models/Auth* ./models/
COPY auth.php ./
CMD ./yii migrate --migrationPath=@app/migrations/db_auth --db=db_auth --interactive=0 -c && php auth.php