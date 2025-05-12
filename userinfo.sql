create database users;
use users;

create table users (
    id int primary key auto_increment,
    userType enum('customer', 'businessOwner') not null,
    gender enum('male', 'female') not null,
    firstName varchar(50) not null,
    lastName varchar(50) not null,
    username varchar(50) not null,
    birthday date not null,
    email varchar(100) unique not null,
    phoneNumber varchar(20) not null);

insert into users (userType, gender, firstName, lastName, username, birthday, email, phoneNumber) 
value ('customer', 'female', 'Fatma', 'Alzaidy', 'fatima_z', '1995-06-12', 'fatima@example.com', '00968-91234567');

insert into users (userType, gender, firstName, lastName, username, birthday, email, phoneNumber) 
value ('businessOwner', 'male', 'Salim', 'Alshahri', 'salim_s', '1999-03-08', 'salim@example.com', '00968-92345678');

insert into users (userType, gender, firstName, lastName, username, birthday, email, phoneNumber) 
value ('customer', 'female', 'Noor', 'Alharthi', 'noor_h', '2000-11-22', 'noor@example.com', '00968-93456789');

insert into users (userType, gender, firstName, lastName, username, birthday, email, phoneNumber) 
value ('customer', 'male', 'Khalid', 'Alhinai', 'khalid_h', '2005-09-10', 'khalid@example.com', '00968-94567890');

insert into users (userType, gender, firstName, lastName, username, birthday, email, phoneNumber) 
value ('businessOwner', 'female', 'Aisha', 'Albusaeedi', 'aisha_b', '2003-05-17', 'aisha@example.com', '00968-95678901');

INSERT INTO users (userType, gender, firstName, lastName, username, birthday, email, phoneNumber) 
value ('customer', 'male', 'Haitham', 'Almaqbali', 'haitham_m', '1998-12-01', 'haitham@example.com', '00968-96789012');

insert into users (userType, gender, firstName, lastName, username, birthday, email, phoneNumber) 
value ('businessOwner', 'female', 'Maryam', 'Albalushi', 'maryam_b', '2012-08-25', 'maryam@example.com', '00968-97890123');
