FROM service.base
ADD config/news.php ./config/
ADD controllers/New* ./controllers/
ADD migrations/db_news ./migrations/db_news/
ADD models/New* ./models/
COPY news.php ./
CMD ./yii migrate --migrationPath=@app/migrations/db_news --db=db_news --interactive=0 -c && php news.php