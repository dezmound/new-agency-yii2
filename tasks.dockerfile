FROM service.base
ADD config/tasks.php ./config/
ADD controllers/Task* ./controllers/
ADD migrations/db_tasks ./migrations/db_tasks/
ADD models/Task* ./models/
COPY tasks.php ./
CMD ./yii migrate --migrationPath=@app/migrations/db_tasks --db=db_tasks --interactive=0 -c && php tasks.php