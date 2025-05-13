CREATE DATABASE activities;
USE activities;

CREATE TABLE company (
    companyID INT PRIMARY KEY,
    companyName VARCHAR(50),
    companyWhatsapp VARCHAR(200)
);


CREATE TABLE activity (
    activityID INT PRIMARY KEY,
    activityName VARCHAR(50),
    price VARCHAR(15)
);

CREATE TABLE activityInfo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    activityID INT,
    companyID INT,
    activityDescription VARCHAR(100),
    activityIMG VARCHAR(100),
    FOREIGN KEY (activityID) REFERENCES Activity(activityID),
    FOREIGN KEY (companyID) REFERENCES Company(companyID)
);

insert into company(companyID,companyName,companyWhatsapp) value(1,"Jabal","https://api.whatsapp.com/send/?phone=96898689343&text&type=phone_number&app_absent=0");
insert into company(companyID,companyName,companyWhatsapp) value(2,"Burma'e","https://api.whatsapp.com/message/P4VGAAK33JXYI1?autoload=1&app_absent=0");
insert into company(companyID,companyName,companyWhatsapp) value(3,"HIking Adventures","https://chat.whatsapp.com/K49kP7MXGJmHcowHN3e5Kf?fbclid=PAZXh0bgNhZW0CMTEAAacdi17EdtouVXNbIAqYfIHiYN_wpsAIEpU9Kmh0MIky1vD96Qbe0z6dbQ7EfQ_aem_s1sjD26LJ32KXz68Hqu4SA");
insert into company(companyID,companyName,companyWhatsapp) value(4,"Explore and challenge","https://chat.whatsapp.com/DZm8PovbzRF9gU9C0qVUXx?fbclid=PAZXh0bgNhZW0CMTEAAaffYKz292VKiILx2PRSIZH_8wOepEpcKP-STxXlO9k2BXmFZeietj-oesvkAA_aem_Ix6sqr7MvIhis-PhUEsQAg");
insert into company(companyID,companyName,companyWhatsapp) value(5,"Daymaniyat Norana Sea Tour","https://iwtsp.com/96894510101?fbclid=PAZXh0bgNhZW0CMTEAAaefei8D41KVKsuJPt7c03tOC00MlhaFtJst-hDPdmoehwwwGRMiCxIej1lSXg_aem_y_YgByKAJc5JScEvRTYq1Q");

insert into activity (activityID,activityName,price) value(1,"Wadi & Valley","39 OMR");
insert into activity (activityID,activityName,price) value(2,"Conyoning","39 OMR");
insert into activity (activityID,activityName,price) value(3,"Hiking","39 OMR");
insert into activity (activityID,activityName,price) value(4,"Surfing","12 OMR");
insert into activity (activityID,activityName,price) value(5,"Roses Trail","30 OMR");

INSERT INTO activityInfo (activityID, companyID, activityDescription, activityIMG) VALUES
(1, 1, "Public Tour", "img/Jabal-Wadi-Day.jpg"),
(2, 1, "Privet tour for 1-4", "img/Jabal-Conyoning-Day.jpg"),
(3, 1, "Privet tour for 5-8", "img/Jabal-Hiking-Day.jpeg"),
(5, 4, "Privet Group", "img/Explore and challenge-RosesTrail.jpg"),
(4, 3, "Public Tour", "img/HIking Adventures-Surfing.png");

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE feedback (
  id INT AUTO_INCREMENT PRIMARY KEY,
  phone VARCHAR(20),
  email VARCHAR(100),
  password VARCHAR(255),
  gender VARCHAR(10),
  feedback1 TEXT,
  feedback2 TEXT,
  rate INT,
  subscribe BOOLEAN,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
