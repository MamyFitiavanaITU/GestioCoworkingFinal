create database gestioncoworkingfinal;

CREATE table OptionPayants(
    id SERIAL PRIMARY KEY,
    nomOption VARCHAR(255) NOT NULL,
    tarif DECIMAL(10, 2) NOT NULL
);
insert into options (code,"nomOption",prix)VALUES ('OPT1','Imprimante',50000);
insert into options (code,"nomOption",prix)VALUES ('OPT2','Appareil photo',35700);
insert into options (code,"nomOption",prix) VALUES ('OPT3','Vidéo projecteur',86000);
insert into options (code,"nomOption",prix) VALUES ('OPT4','Laptop',91000);


 create table admin (
    id SERIAL PRIMARY KEY,
    nomAdmin VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
      password VARCHAR(255) NOT NULL
 );
 INSERT INTO admin ("nomAdmin", email, password, created_at, updated_at)
VALUES
('Admin', 'admin@gmail.com', 'admin1234', NOW(), NOW());
  create Table clients (
    id SERIAL PRIMARY KEY,
    numerotelephone VARCHAR(15) CHECK (numero_telephone ~ '^\+?[0-9\- ]{7,15}$')
  );

    CREATE table espaceTravail (
    id serial primary key ,
    nom VARCHAR(255) NOT NULL,
    prix_heure DECIMAL(10, 2) NOT NULL
  );
  insert into espace_travail (nom, prix_heure) VALUES ('rubis',65000);
  insert into espace_travail (nom, prix_heure) VALUES ('diamant',55000);
  insert into espace_travail (nom, prix_heure) VALUES ('or',46500);
  insert into espace_travail (nom, prix_heure) VALUES ('amethyste',47000);


    CREATE table reservation (
    id SERIAL PRIMARY KEY,
    ref VARCHAR(255) NOT NULL,
    idEspaceTravail int,
    idClient INT
    dateReservation DATE NOT NULL,
    heureDebut TIME NOT NULL,
    duree int ,
    statut int not null,
    FOREIGN KEY (idClient) REFERENCES clients(id),
    FOREIGN KEY (idEspaceTravail) REFERENCES espaceTravail(id)
  );
     CREATE TABLE Paiements (
    id serial primary key,
    idReservation INT,
    referencesPaiements VARCHAR(255) NOT NULL,
    datePaiement DATE NOT NULL,
    statutValidation int NOT NULL,
    FOREIGN KEY (idReservation) REFERENCES reservation(id)
  );

truncate table options restart identity casacde ;
truncate table clients restart identity cascade ;
truncate table espace_travail restart identity cascade ;
truncate table reservations restart identity cascade ;
truncate table paiements restart identity cascade ;

SELECT
    DATE(paiements."datePaiement") AS date,
    SUM(paiements.montant) AS chiffre_affaire
FROM
    paiements
WHERE
    paiements."statutValidation" = 2
    AND paiements."datePaiement" BETWEEN '2025-01-01' AND '2025-01-31'
GROUP BY
    DATE(paiements."datePaiement")
ORDER BY
    DATE(paiements."datePaiement");

CREATE OR REPLACE FUNCTION top_creneaux_horaires()
RETURNS TABLE(creneau TIME, nombre_reservations INTEGER) AS $$
BEGIN
    RETURN QUERY
    SELECT "heureDebut", COUNT(*)::INTEGER AS nombre_reservations
    FROM reservations
    GROUP BY "heureDebut"
    ORDER BY nombre_reservations DESC;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION top_creneaux_horaires()
RETURNS TABLE(creneau TIME, nombre_reservations INTEGER) AS $$
BEGIN
    RETURN QUERY
    WITH expanded_creneaux AS (
        SELECT "heureDebut" + (INTERVAL '1 hour' * generate_series(0, "duree" - 1)) AS creneau_heure
        FROM reservations
    )
    SELECT creneau_heure::TIME, COUNT(*)::INTEGER AS nombre_reservations
    FROM expanded_creneaux
    GROUP BY creneau_heure
    ORDER BY nombre_reservations DESC
    LIMIT 3;
END;
$$ LANGUAGE plpgsql;


SELECT * FROM top_creneaux_horaires();

CREATE or REPLACE VIEW vue_chiffre_affaire AS
SELECT
    DATE(paiements."datePaiement") AS date,
    SUM(paiements.montant) AS chiffre_affaire
FROM
    paiements
WHERE
    paiements."statutValidation" = 2  -- Paiements validés
    AND paiements."datePaiement" BETWEEN '2025-11-01'
GROUP BY
    DATE(paiements."datePaiement")
ORDER BY
    DATE(paiements."datePaiement");

SELECT
    SUM(CASE WHEN "statutValidation" = 2 THEN montant ELSE 0 END) AS montant_paye,  -- Montant payé (statut = 2)
    SUM(CASE WHEN "statutValidation" = 1 THEN montant ELSE 0 END) AS montant_a_payer,  -- Montant à payer (statut = 1)
    SUM(montant) AS chiffre_affaire_total  -- Chiffre d'affaire total
FROM
    paiements;


 SELECT * FROM reservations WHERE "idEspaceTravail" = 3
AND "dateReservation" = '2025-03-23'
AND ("heureDebut" < '17:00:00'
AND ("heureDebut" + interval '1 hour' * duree) > '15:00:00');




CREATE or replace VIEW  vue_chiffre_affaire AS
SELECT
    '2025-01-11'::DATE AS jour,  -- Date fixe affichée
    COALESCE(SUM(paiements.montant), 0) AS chiffre_affaire_total
FROM
    paiements
WHERE
    paiements."statutValidation" = 2
    AND DATE(paiements."datePaiement") = '2025-01-11';

--filtre un jour SELECT
SELECT
    DATE(p."datePaiement") AS jour,
    COALESCE(SUM(p.montant), 0) AS chiffre_affaire
FROM
    paiements p
WHERE
    p."statutValidation" = 2
GROUP BY
    DATE(p."datePaiement")
ORDER BY
    DATE(p."datePaiement");


-- filtre un jour pour 2 statut
SELECT
    DATE(p."datePaiement") AS jour,
    COALESCE(SUM(p.montant), 0) AS chiffre_affaire
FROM
    paiements p
WHERE
    p."statutValidation" IN (1, 2)
GROUP BY
    DATE(p."datePaiement")
ORDER BY
    DATE(p."datePaiement");

    -- filtre date deux dates
    CREATE OR REPLACE VIEW vue_chiffre_affaire AS
SELECT
    DATE(p."datePaiement") AS jour,
    COALESCE(SUM(p.montant), 0) AS chiffre_affaire_total
FROM
    paiements p
WHERE
    p."statutValidation" = 2  -- Uniquement les paiements validés
GROUP BY
    DATE(p."datePaiement")
ORDER BY
    DATE(p."datePaiement");


-- espace la plus frequente
SELECT et."nom", COUNT(r.id) AS nombre_reservations
FROM espace_travail et
LEFT JOIN reservations r ON et.id = r."idEspaceTravail"
GROUP BY et.id
ORDER BY nombre_reservations DESC
LIMIT 1;
-- top 3 plus freqenter
SELECT et."nom", COUNT(r.id) AS nombre_reservations
FROM espace_travail et
LEFT JOIN reservations r ON et.id = r."idEspaceTravail"
GROUP BY et.id
ORDER BY nombre_reservations DESC
LIMIT 3;

-- option la plus utiliser
SELECT o."nomOption", COUNT(ro.id) AS nombre_reservations
FROM options o
LEFT JOIN reservation_option ro ON o.id = ro."option_id"
GROUP BY o.id
ORDER BY nombre_reservations DESC
limit 1;


-- top 3 option plus utiliser
SELECT o."nomOption", COUNT(ro.id) AS nombre_reservations
FROM options o
LEFT JOIN reservation_option ro ON o.id = ro."option_id"
GROUP BY o.id
ORDER BY nombre_reservations DESC
LIMIT 3;

-- client qui a la plus grande consommation
SELECT c."numerotelephone", SUM(r.duree) AS duree_total
FROM clients c
LEFT JOIN reservations r ON c.id = r."idClient"
GROUP BY c.id
ORDER BY duree_total DESC;

-- top 3 client qui a la plus grande consommation
SELECT c."numerotelephone", SUM(r.duree) AS duree_total
FROM clients c
LEFT JOIN reservations r ON c.id = r."idClient"
GROUP BY c.id
ORDER BY duree_total DESC
LIMIT 3;

-- espace travail qui a la plus grande consommation
SELECT et."nom", SUM(r.duree) AS duree_total
FROM espace_travail et
LEFT JOIN reservations r ON et.id = r."idEspaceTravail"
GROUP BY et.id
ORDER BY duree_total DESC;

-- top 3 espace travail qui a la plus grande consommation
SELECT et."nom", SUM(r.duree) AS duree_total
FROM espace_travail et
LEFT JOIN reservations r ON et.id = r."idEspaceTravail"
GROUP BY et.id
ORDER BY duree_total DESC
LIMIT 3;

-- option qui a la plus grande consommation
SELECT o."nomOption", SUM(ro.quantite) AS quantite_total
FROM options o
LEFT JOIN reservation_option ro ON o.id = ro."option_id"
GROUP BY o.id
ORDER BY quantite_total DESC;


--chiffre d'affaire total
SELECT
    SUM(CASE WHEN "statutValidation" = 2 THEN montant ELSE 0 END) AS montant_paye,
    SUM(CASE WHEN "statutValidation" = 1 THEN montant ELSE 0 END) AS montant_a_payer,
    SUM(montant) AS chiffre_affaire_total
FROM paiements;
 -- chiffre d'affaire par jour

SELECT
    SUM(CASE WHEN "statutValidation" = 2 THEN montant ELSE 0 END) AS montant_paye,
    SUM(CASE WHEN "statutValidation" = 1 THEN montant ELSE 0 END) AS montant_a_payer,
    SUM(montant) AS chiffre_affaire_total
FROM paiements
WHERE "datePaiement" = '2025-02-08';
-- -- chiffre d'affaire entre 2 dates
SELECT
    SUM(CASE WHEN "statutValidation" = 2 THEN montant ELSE 0 END) AS montant_paye,
    SUM(CASE WHEN "statutValidation" = 1 THEN montant ELSE 0 END) AS montant_a_payer,
    SUM(montant) AS chiffre_affaire_total
FROM paiements
WHERE "datePaiement" = '2025-02-08';
WHERE "datePaiement" BETWEEN '2025-02-01' AND '2025-02-08';

-- chiffre d'affaire par espace
SELECT
    e."nom" AS espace_travail,
    SUM(CASE WHEN p."statutValidation" = 2 THEN p."montant" ELSE 0 END) AS montant_paye,
    SUM(CASE WHEN p."statutValidation" = 1 THEN p."montant" ELSE 0 END) AS montant_a_payer,
    SUM(p."montant") AS chiffre_affaire_total
FROM
    espace_travail e
JOIN
    reservations r ON e.id = r."idEspaceTravail"
JOIN
    paiements p ON r.id = p."idReservation"
GROUP BY
    e."nom";

-- chiffre d'affaire d'un espace de travail
SELECT
    e."nom" AS espace_travail,
    SUM(p."montant") AS chiffre_affaire_valide
FROM
    espace_travail e
JOIN
    reservations r ON e.id = r."idEspaceTravail"
JOIN
    paiements p ON r.id = p."idReservation"
WHERE
    p."statutValidation" = 2
GROUP BY
    e."nom";
SELECT
    e."nom" AS espace_travail,
    SUM(p."montant") AS chiffre_affaire_non_valide
FROM
    espace_travail e
JOIN
    reservations r ON e.id = r."idEspaceTravail"
JOIN
    paiements p ON r.id = p."idReservation"
WHERE
    p."statutValidation" = 1
GROUP BY
    e."nom";
-- chiffre_affaire espace_travail
SELECT
    e."nom" AS espace_travail,
    SUM(p."montant") AS chiffre_affaire_total
FROM
    espace_travail e
LEFT JOIN
    reservations r ON e.id = r."idEspaceTravail"
LEFT JOIN
    paiements p ON r.id = p."idReservation"
GROUP BY
    e."nom"
ORDER BY
    e."nom" ASC;

-- chiffe afafire option
SELECT
    o."nomOption" AS option_nom,
    SUM(o."prix" * r."duree") AS chiffre_affaire_option
FROM
    options o
JOIN
    reservation_option ro ON o.id = ro."option_id"
JOIN
    reservations r ON ro."reservation_id" = r.id
GROUP BY
    o."nomOption"
ORDER BY
    o."nomOption" ASC;


-- chiffe affaire total
SELECT SUM(montant) AS chiffre_affaires
FROM paiements
WHERE "statutValidation" = 2;

-- calcul chiffee affaire paye et non paye
CREATE OR REPLACE FUNCTION calcul_chiffre_affaire_total()
RETURNS TABLE (
    montant_paye DECIMAL,
    montant_a_payer DECIMAL,
    chiffre_affaire_total DECIMAL
) AS $$
BEGIN
    RETURN QUERY
    SELECT
        -- Montant payé (paiements validés)
        COALESCE(SUM(p."montant"), 0) AS montant_paye,

        -- Montant à payer (réservations statut = 1 ou 2 et paiements non validés)
        COALESCE(SUM(CASE
            WHEN r."statut" IN (1, 2)
            AND NOT EXISTS (SELECT 1 FROM paiements p2
                            WHERE p2."idReservation" = r."id"
                            AND p2."statutValidation" = 2)
            THEN (e."prix_heure" * r."duree") +
                 COALESCE((SELECT SUM(o."prix" * r."duree")
                           FROM reservation_option ro
                           JOIN options o ON ro."option_id" = o."id"
                           WHERE ro."reservation_id" = r."id"), 0)
            ELSE 0
        END), 0) AS montant_a_payer,

        -- Chiffre d'affaires total = montant payé + montant à payer
        COALESCE(SUM(p."montant"), 0) +
        COALESCE(SUM(CASE
            WHEN r."statut" IN (1, 2)
            AND NOT EXISTS (SELECT 1 FROM paiements p2
                            WHERE p2."idReservation" = r."id"
                            AND p2."statutValidation" = 2)
            THEN (e."prix_heure" * r."duree") +
                 COALESCE((SELECT SUM(o."prix" * r."duree")
                           FROM reservation_option ro
                           JOIN options o ON ro."option_id" = o."id"
                           WHERE ro."reservation_id" = r."id"), 0)
            ELSE 0
        END), 0) AS chiffre_affaire_total
    FROM reservations r
    LEFT JOIN espace_travail e ON r."idEspaceTravail" = e."id"
    LEFT JOIN paiements p ON r."id" = p."idReservation" AND p."statutValidation" = 2;
END;
$$ LANGUAGE plpgsql;


-- statut 1 et 2
CREATE OR REPLACE FUNCTION calcul_chiffre_affaire_total()
RETURNS TABLE (
    montant_paye DECIMAL,
    montant_a_payer DECIMAL,
    chiffre_affaire_total DECIMAL
) AS $$
BEGIN
    RETURN QUERY
    SELECT
        -- Montant payé (paiements validés avec statut 1 ou 2)
        COALESCE(SUM(p."montant"), 0) AS montant_paye,

        -- Montant à payer (réservations statut = 1 ou 2 et paiements non validés)
        COALESCE(SUM(CASE
            WHEN r."statut" IN (1, 2)
            AND NOT EXISTS (SELECT 1 FROM paiements p2
                            WHERE p2."idReservation" = r."id"
                            AND p2."statutValidation" = 2)
            THEN (e."prix_heure" * r."duree") +
                 COALESCE((SELECT SUM(o."prix" * r."duree")
                           FROM reservation_option ro
                           JOIN options o ON ro."option_id" = o."id"
                           WHERE ro."reservation_id" = r."id"), 0)
            ELSE 0
        END), 0) AS montant_a_payer,

        -- Chiffre d'affaires total = montant payé + montant à payer
        COALESCE(SUM(p."montant"), 0) +
        COALESCE(SUM(CASE
            WHEN r."statut" IN (1, 2)
            AND NOT EXISTS (SELECT 1 FROM paiements p2
                            WHERE p2."idReservation" = r."id"
                            AND p2."statutValidation" = 2)
            THEN (e."prix_heure" * r."duree") +
                 COALESCE((SELECT SUM(o."prix" * r."duree")
                           FROM reservation_option ro
                           JOIN options o ON ro."option_id" = o."id"
                           WHERE ro."reservation_id" = r."id"), 0)
            ELSE 0
        END), 0) AS chiffre_affaire_total
    FROM reservations r
    LEFT JOIN espace_travail e ON r."idEspaceTravail" = e."id"
    LEFT JOIN paiements p ON r."id" = p."idReservation"
    AND p."statutValidation" IN (1, 2); -- Paiements validés avec statut 1 ou 2
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION calcul_chiffre_affaire_totalparjour(
    date_debut DATE,
    date_fin DATE
)
RETURNS TABLE (
    montant_paye DECIMAL,
    montant_a_payer DECIMAL,
    chiffre_affaire_total DECIMAL
) AS $$
BEGIN
    RETURN QUERY
    SELECT
        -- Montant payé (paiements validés)
        COALESCE(SUM(p."montant"), 0) AS montant_paye,

        -- Montant à payer (réservations statut = 1 ou 2 et paiements non validés)
        COALESCE(SUM(CASE
            WHEN r."statut" IN (1, 2)
            AND NOT EXISTS (SELECT 1 FROM paiements p2
                            WHERE p2."idReservation" = r."id"
                            AND p2."statutValidation" = 2)
            THEN (e."prix_heure" * r."duree") +
                 COALESCE((SELECT SUM(o."prix" * r."duree")
                           FROM reservation_option ro
                           JOIN options o ON ro."option_id" = o."id"
                           WHERE ro."reservation_id" = r."id"), 0)
            ELSE 0
        END), 0) AS montant_a_payer,

        -- Chiffre d'affaires total = montant payé + montant à payer
        COALESCE(SUM(p."montant"), 0) +
        COALESCE(SUM(CASE
            WHEN r."statut" IN (1, 2)
            AND NOT EXISTS (SELECT 1 FROM paiements p2
                            WHERE p2."idReservation" = r."id"
                            AND p2."statutValidation" = 2)
            THEN (e."prix_heure" * r."duree") +
                 COALESCE((SELECT SUM(o."prix" * r."duree")
                           FROM reservation_option ro
                           JOIN options o ON ro."option_id" = o."id"
                           WHERE ro."reservation_id" = r."id"), 0)
            ELSE 0
        END), 0) AS chiffre_affaire_total
    FROM reservations r
    LEFT JOIN espace_travail e ON r."idEspaceTravail" = e."ids"
    LEFT JOIN paiements p ON r."id" = p."idReservation" AND p."statutValidation" = 2
    WHERE p."datePaiement" BETWEEN date_debut AND date_fin;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE VIEW vue_chiffre_affaire_totalunjour AS
    SELECT * FROM calcul_chiffre_affaire_totalparjour('2025-01-01', '2025-02-02');

CREATE OR REPLACE FUNCTION calcul_chiffre_affaire_totalunjour(
    dateJour DATE
)
RETURNS TABLE (
    montant_paye DECIMAL,
    montant_a_payer DECIMAL,
    chiffre_affaire_total DECIMAL
) AS $$
BEGIN
    RETURN QUERY
    SELECT
        -- Montant payé (paiements validés pour ce jour)
        COALESCE(SUM(p."montant"), 0) AS montant_paye,

        -- Montant à payer (réservations en attente pour ce jour)
        COALESCE(SUM(CASE
            WHEN r."statut" IN (1, 2)
            AND NOT EXISTS (
                SELECT 1 FROM paiements p2
                WHERE p2."idReservation" = r."id"
                AND p2."statutValidation" = 2
            )
            THEN (e."prix_heure" * r."duree") +
                 COALESCE((
                     SELECT SUM(o."prix" * r."duree")
                     FROM reservation_option ro
                     JOIN options o ON ro."option_id" = o."id"
                     WHERE ro."reservation_id" = r."id"
                 ), 0)
            ELSE 0
        END), 0) AS montant_a_payer,

        -- Chiffre d'affaires total = montant payé + montant à payer
        COALESCE(SUM(p."montant"), 0) +
        COALESCE(SUM(CASE
            WHEN r."statut" IN (1, 2)
            AND NOT EXISTS (
                SELECT 1 FROM paiements p2
                WHERE p2."idReservation" = r."id"
                AND p2."statutValidation" = 2
            )
            THEN (e."prix_heure" * r."duree") +
                 COALESCE((
                     SELECT SUM(o."prix" * r."duree")
                     FROM reservation_option ro
                     JOIN options o ON ro."option_id" = o."id"
                     WHERE ro."reservation_id" = r."id"
                 ), 0)
            ELSE 0
        END), 0) AS chiffre_affaire_total
    FROM reservations r
    LEFT JOIN espace_travail e ON r."idEspaceTravail" = e."id"
    LEFT JOIN paiements p ON r."id" = p."idReservation" AND p."statutValidation" = 2
    WHERE p."datePaiement" = dateJour;
END;
$$ LANGUAGE plpgsql;

SELECT * FROM calcul_chiffre_affaire_totalunjour('2025-03-23');

CREATE TABLE reservations_clients (
    id SERIAL PRIMARY KEY,
    idReservation BIGINT NOT NULL,
    idClientReservant BIGINT NOT NULL,
    idClientReserve BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    CONSTRAINT fk_reservation FOREIGN KEY (idReservation) REFERENCES reservations(id) ON DELETE CASCADE,
    CONSTRAINT fk_client_reservant FOREIGN KEY (idClientReservant) REFERENCES clients(id) ON DELETE CASCADE,
    CONSTRAINT fk_client_reserve FOREIGN KEY (idClientReserve) REFERENCES clients(id) ON DELETE CASCADE
);

SELECT
    r.id AS "reservation_id",
    r.ref AS "reservation_ref",
    r."dateReservation",
    r."heureDebut",
    r."duree",
    r."statut" AS reservation_statut,
    c1."numerotelephone" AS client_reservant_numerotelephone,
    c2."numerotelephone" AS client_reserve_numerotelephone
FROM
    reservations_clients rc
JOIN
    reservations r ON rc.idreservation = r.id
JOIN
    clients c1 ON rc.idclientreservant = c1.id
JOIN
    clients c2 ON rc.idclientreserve = c2.id
WHERE
    rc.idclientreservant != rc.idclientreserve;


DELETE FROM reservations
WHERE id BETWEEN 16 AND 22;

DROP TABLE reservations_clients CASCADE;

CREATE OR REPLACE VIEW vue_details_reservations AS
SELECT
    r.id AS reservation_id,
    r.ref AS reservation_ref,
    r."dateReservation",
    r."heureDebut",
    r."duree",
    r."statut" AS reservation_statut,
    e."nom" AS espace_nom,
    c1."numerotelephone" AS client_reservant_numerotelephone,
    c2."numerotelephone" AS client_reserve_numerotelephone
FROM reservations r
JOIN espace_travail e ON r."idEspaceTravail" = e.id
JOIN clients c1 ON r."idClient" = c1.id
LEFT JOIN clients c2 ON r."idClientReserve" = c2.id;

alter table reservations add FOREIGN_key idClientReserve REFERENCES clients(id);


ALTER TABLE reservations
ADD COLUMN idClientReserve INT;

ALTER TABLE reservations
ADD CONSTRAINT fk_idClientReserve
FOREIGN KEY (idClientReserve)
REFERENCES clients(id)
ON DELETE CASCADE;



