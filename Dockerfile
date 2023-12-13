FROM mongo:latest

ENV MONGO_INITDB_ROOT_USERNAME=admin
ENV MONGO_INITDB_ROOT_PASSWORD=Marcelek123
ENV MONGO_INITDB_DATABASE=wypozyczalnia

COPY init-db.js /docker-entrypoint-initdb.d/

EXPOSE 27017

CMD ["mongod"]
