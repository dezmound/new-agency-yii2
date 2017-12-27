FROM service.base
ADD config/thematicEvents.php ./config/
ADD controllers/Thematic* ./controllers/
ADD models/Event* ./models/
COPY thematicEvents.php ./
CMD php thematicEvents.php