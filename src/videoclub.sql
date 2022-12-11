DROP TABLE IF EXISTS peliculas CASCADE;

CREATE TABLE peliculas (
    id      bigserial       PRIMARY KEY NOT NULL UNIQUE,
    codigo  numeric(4)      NOT NULL UNIQUE,
    titulo  varchar(255)    NOT NULL,
    genero  varchar(255)    NOT NULL   
);

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios (
    id          bigserial     PRIMARY KEY NOT NULL UNIQUE,
    usuario     varchar(255)   NOT NULL UNIQUE,
    password    varchar(255)   NOT NULL 
);

DROP TABLE IF EXISTS historial CASCADE;

CREATE TABLE historial (
    id          bigserial PRIMARY KEY,
    created_at  timestamp NOT NULL DEFAULT localtimestamp(0),
    usuario_id  bigint    NOT NULL REFERENCES usuarios(id)
);


DROP TABLE IF EXISTS peliculas_historial CASCADE;

CREATE TABLE peliculas_historial (
    pelicula_id bigint NOT NULL REFERENCES peliculas(id),
    historial_id bigint NOT NULL REFERENCES historial(id),
    PRIMARY KEY(pelicula_id, historial_id)
);


INSERT INTO peliculas(titulo, codigo ,genero)
            VALUES('titulo1', 1111, 'terror'),
                ('titulo2', 2222, 'fantasía'),
                ('titulo3', 3333, 'comedia'),
                ('titulo4', 4444, 'drama');

INSERT INTO usuarios (usuario, password)
    VALUES ('admin', crypt('admin', gen_salt('bf', 10))),
           ('pepe', crypt('pepe', gen_salt('bf', 10)));
