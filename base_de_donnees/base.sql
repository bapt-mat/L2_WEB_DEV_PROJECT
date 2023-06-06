DROP TABLE IF EXISTS commande_article;
DROP TABLE IF EXISTS article;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS reponses_quiz;
DROP TABLE IF EXISTS questions_quiz;
DROP TABLE IF EXISTS quiz;
DROP TABLE IF EXISTS contact;
DROP TABLE IF EXISTS chatbot;
DROP TABLE IF EXISTS likes;
DROP TABLE IF EXISTS cours;
DROP TABLE IF EXISTS forum_messages;
DROP TABLE IF EXISTS forum_sujet;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS membres_perm;
DROP TABLE IF EXISTS perm;
DROP TABLE IF EXISTS membres;



CREATE TABLE IF NOT EXISTS membres (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    pseudo VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    salt VARCHAR(255) NOT NULL,
    photo_profil BOOL NOT NULL DEFAULT FALSE,
    date_inscription DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_connexion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    en_ligne BOOL NOT NULL DEFAULT FALSE,
    ban BOOL NOT NULL DEFAULT FALSE,
    CONSTRAINT membres_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS perm (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    CONSTRAINT perm_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS membres_perm (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_membre BIGINT UNSIGNED NOT NULL,
    id_perm BIGINT UNSIGNED NOT NULL,
    CONSTRAINT membres_perm_pk PRIMARY KEY (id),
    CONSTRAINT membres_perm_membres_fk FOREIGN KEY (id_membre) REFERENCES membres(id),
    CONSTRAINT membres_perm_perm_fk FOREIGN KEY (id_perm) REFERENCES perm(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS messages (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    mess TEXT NOT NULL,
    id_destinataire BIGINT UNSIGNED NOT NULL,
    id_expediteur BIGINT UNSIGNED NOT NULL,
    message_lu BOOL NOT NULL DEFAULT FALSE,
    CONSTRAINT messages_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS forum_sujet (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    sujet VARCHAR(255) NOT NULL,
    id_auteur BIGINT UNSIGNED NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT forum_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS forum_messages (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    message TEXT NOT NULL,
    id_sujet BIGINT UNSIGNED NOT NULL,
    id_auteur BIGINT UNSIGNED NOT NULL,
    date_message DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    image_path VARCHAR(255) NOT NULL DEFAULT '',
    numero_page BIGINT UNSIGNED NOT NULL DEFAULT 1,
    CONSTRAINT forum_messages_pk PRIMARY KEY (id),
    CONSTRAINT forum_messages_sujet_fk FOREIGN KEY (id_sujet) REFERENCES forum_sujet(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS cours (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    matiere VARCHAR(255) NOT NULL,
    id_auteur BIGINT UNSIGNED NOT NULL,
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    chemin_fichier VARCHAR(255) NOT NULL,
    nb_likes BIGINT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT cours_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS likes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_cours BIGINT UNSIGNED NOT NULL,
    id_membre BIGINT UNSIGNED NOT NULL,
    CONSTRAINT likes_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS chatbot(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    mot VARCHAR(255) NOT NULL,
    reponse VARCHAR(255) NOT NULL,
    CONSTRAINT chatbot_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS contact(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    objet VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    CONSTRAINT contact_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS commande(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_membre BIGINT UNSIGNED NOT NULL,
    date_commande DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    adresse_livraison VARCHAR(255) NOT NULL,
    prix_total FLOAT NOT NULL,
    CONSTRAINT commande_pk PRIMARY KEY (id),
    CONSTRAINT commande_membres_fk FOREIGN KEY (id_membre) REFERENCES membres(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS article(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom_article VARCHAR(255) NOT NULL,
    prix FLOAT NOT NULL,
    path_image VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    CONSTRAINT article_pk PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS commande_article(
    id_commande BIGINT UNSIGNED NOT NULL,
    id_article BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS quiz(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_auteur BIGINT UNSIGNED NOT NULL,
    nom VARCHAR(255) NOT NULL,
    descr VARCHAR(255) NOT NULL,
    theme ENUM('Histoire', 'Géographie', 'Sport', 'Cinéma', 'Musique', 'Littérature', 'Sciences', 'Art', 'Politique', 'Cuisine', 'Autre') NOT NULL DEFAULT 'Autre',
    duree BIGINT UNSIGNED NOT NULL DEFAULT 0,
    difficulte ENUM('facile', 'moyen', 'difficile') NOT NULL,
    CONSTRAINT quiz_pk PRIMARY KEY (id),
    CONSTRAINT quiz_membres_fk FOREIGN KEY (id_auteur) REFERENCES membres(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS questions_quiz(
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    id_quiz BIGINT UNSIGNED NOT NULL,
    question VARCHAR(255) NOT NULL,
    reponseA VARCHAR(255) NOT NULL,
    reponseB VARCHAR(255) NOT NULL,
    reponseC VARCHAR(255) NOT NULL,
    reponseD VARCHAR(255) NOT NULL,
    est_juste ENUM('A', 'B', 'C', 'D') NOT NULL,
    CONSTRAINT questions_quiz_pk PRIMARY KEY (id),
    CONSTRAINT questions_quiz_quiz_fk FOREIGN KEY (id_quiz) REFERENCES quiz(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Creation du role administrateur
INSERT INTO perm (nom, description) VALUES ('admin', 'Administrateur du site');

-- Creation du role moderateur

INSERT INTO perm (nom, description) VALUES ('moderateur', 'Modérateur du site');

-- Creation du role professeur

INSERT INTO perm (nom, description) VALUES ('professeur', 'Professeur');

-- Creation du role membre
INSERT INTO perm (nom, description) VALUES ('membre', 'La pleb du site');

-- Creation du compte administrateur
INSERT INTO membres (pseudo, email, password, salt, photo_profil, date_inscription, date_connexion) VALUES ('admin', 'admin@localhost', SHA2('l2_infoadmin', 512), '', DEFAULT, DEFAULT, DEFAULT);

-- Creation du lien entre le compte administrateur et le role administrateur

INSERT INTO membres_perm (id, id_membre, id_perm) VALUES (DEFAULT, 1, 1);



-- Creation de 15 comptes membres pour tester le site, avec tout aléatoire
-- initialisation de la variable @i
INSERT INTO membres (pseudo, email, password, salt, photo_profil, date_inscription, date_connexion) 
VALUES ('membre1', 'membre1@localhost' , SHA2('l2_infomembre1', 512), '', DEFAULT, DEFAULT, DEFAULT),
       ('prof1', 'prof1@localhost' , SHA2('l2_infoprof1', 512), '', DEFAULT, DEFAULT, DEFAULT);
 
-- Modificication de la table membres_perm pour ajouter les membres
INSERT into membres_perm (id, id_membre, id_perm) VALUES (DEFAULT, 2, 4),
                                                        (DEFAULT, 3, 3);

INSERT INTO chatbot (mot, reponse) VALUES ('mathématiques', 'Nous proposons des cours particuliers en mathématiques pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('français', 'Nous proposons des cours particuliers en français pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('anglais', 'Nous proposons des cours particuliers en anglais pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('sciences', 'Nous proposons des cours particuliers en sciences pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('physique', 'Nous proposons des cours particuliers en physique pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('chimie', 'Nous proposons des cours particuliers en chimie pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('biologie', 'Nous proposons des cours particuliers en biologie pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('histoire', 'Nous proposons des cours particuliers en histoire pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('géographie', 'Nous proposons des cours particuliers en géographie pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('philosophie', 'Nous proposons des cours particuliers en philosophie pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('économie', 'Nous proposons des cours particuliers en économie pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('droit', 'Nous proposons des cours particuliers en droit pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('langues', 'Nous proposons des cours particuliers en langues étrangères pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('espagnol', 'Nous proposons des cours particuliers en espagnol pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('allemand', 'Nous proposons des cours particuliers en allemand pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('italien', 'Nous proposons des cours particuliers en italien pour les élèves de tous niveaux.');
INSERT INTO chatbot (mot, reponse) VALUES ('professeur', 'Vous pouvez contacter un professeur par le biais de la messagerie.');
INSERT INTO chatbot (mot, reponse) VALUES ('prof', 'Vous pouvez contacter un professeur par le biais de la messagerie.');
INSERT INTO chatbot (mot, reponse) VALUES ('cours', 'Nos cours sont disponibles sur notre site.');
INSERT INTO chatbot (mot, reponse) VALUES ('soutien', 'Vous pouvez obtenir de laide via notre forum.');
INSERT INTO chatbot (mot, reponse) VALUES ('aide', 'Vous pouvez obtenir de laide via notre forum.');
INSERT INTO chatbot (mot, reponse) VALUES ('forum', 'Vous pouvez disctuer avec les autres élèves via notre forum.');
INSERT INTO chatbot (mot, reponse) VALUES ('bonjour', 'Bonjour comment puis-je vous aider ?');
INSERT INTO chatbot (mot, reponse) VALUES ('salut', 'Bonjour comment puis-je vous aider ?');
INSERT INTO chatbot (mot, reponse) VALUES ('coucou', 'Bonjour comment puis-je vous aider ?');
INSERT INTO chatbot (mot, reponse) VALUES ('hello', 'Bonjour comment puis-je vous aider ?');
INSERT INTO chatbot (mot, reponse) VALUES ('bonsoir', 'Bonsoir comment puis-je vous aider ?');
INSERT INTO chatbot (mot, reponse) VALUES ('aurevoir', 'Aurevoir !');
INSERT INTO chatbot (mot, reponse) VALUES ('merci', 'Je vous en prie !');
INSERT INTO chatbot (mot, reponse) VALUES ('bye', 'Aurevoir !');


INSERT INTO article(nom_article, prix, path_image, description) VALUES("T-shirt blanc logo S-L", 19.99, "images/tshirt_blanc.jpg", "Superbe t-shirt blanc pour habiller vos soirées les plus classes !");
INSERT INTO article(nom_article, prix, path_image, description) VALUES("T-shirt noir", 14.99, "images/tshirt_noir.jpg", "Superbe t-shirt noir pour sortir dehors et faire du sport !");
INSERT INTO article(nom_article, prix, path_image, description) VALUES("T-shirt rouge", 14.99, "images/tshirt_rouge.jpg", "Superbe t-shirt rouge pour une super après-midi piscine entre copains!");
INSERT INTO article(nom_article, prix, path_image, description) VALUES("Mug Blanc logo S-L", 9.99, "images/mug_blanc.jpg", "Magnifique mug pour boire votre café le matin avant d'aller réviser !");
INSERT INTO article(nom_article, prix, path_image, description) VALUES("Mug Noir logo S-L", 9.99, "images/mug_noir.jpg", "Superbe mug pour boire votre tisane après une grosse journée de révisions !");
