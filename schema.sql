CREATE DATABASE doingdone;
USE doingdone;
CREATE TABLE project (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR
);
CREATE TABLE task (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dateCreate DATETIME,
  dateDone DATETIME,
  name TEXT,
  file TEXT,
  dateLimit DATETIME,
  projectId TEXT,
  usersId TEXT
);
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dateReg DATETIME,
  email CHAR,
  name CHAR,
  password CHAR(32),
  contacts TEXT,
  projectId TEXT,
  taskId TEXT
);
CREATE UNIQUE INDEX id ON project(id);
CREATE UNIQUE INDEX id ON task(id);
CREATE UNIQUE INDEX id ON users(id);
CREATE UNIQUE INDEX email ON users(email);
