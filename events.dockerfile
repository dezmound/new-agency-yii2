FROM service.base
ADD config/events.php ./config/
ADD controllers/Event* ./controllers/
ADD migrations/db_events ./migrations/db_events/
ADD models/Event* ./models/
COPY events.php ./
CMD ./yii migrate --migrationPath=@app/migrations/db_events --db=db_events --interactive=0 -c && php events.php