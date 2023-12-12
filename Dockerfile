# Użyj oficjalnego obrazu MongoDB
FROM mongo:latest

# Ustaw zmienną środowiskową MONGO_INITDB_ROOT_USERNAME i MONGO_INITDB_ROOT_PASSWORD
ENV MONGO_INITDB_ROOT_USERNAME=admin
ENV MONGO_INITDB_ROOT_PASSWORD=Marcelek123
ENV MONGO_INITDB_DATABASE=wypozyczalnia

# Kopiowanie skryptu inicjalizacyjnego
COPY init-db.js /docker-entrypoint-initdb.d/

# Port, na którym będzie nasłuchiwać MongoDB
EXPOSE 27017

# Uruchom serwer MongoDB z domyślnymi ustawieniami
CMD ["mongod"]
