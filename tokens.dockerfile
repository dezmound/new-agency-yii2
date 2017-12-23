FROM service.base
ADD config/tokens.php ./config/
ADD controllers/Token* ./controllers/
ADD migrations/db_tokens ./migrations/db_tokens/
ADD models/Token* ./models/
COPY tokens.php ./
CMD ./yii migrate --migrationPath=@app/migrations/db_tokens --db=db_tokens --interactive=0 -c && php tokens.php