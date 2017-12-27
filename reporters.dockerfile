FROM service.base
ADD config/reporter.php ./config/
ADD controllers/Reporters* ./controllers/
ADD migrations/db_reporters ./migrations/db_reporters/
ADD models/Reporter* ./models/
COPY reporter.php ./
CMD ./yii migrate --migrationPath=@app/migrations/db_reporters --db=db_reporters --interactive=0 -c && php reporter.php