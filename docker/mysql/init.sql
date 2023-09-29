CREATE DATABASE azorium;
CREATE USER 'azorium'@'192.168.0.2' IDENTIFIED BY 'azorium';
CREATE USER 'azorium'@'localhost' IDENTIFIED BY 'azorium';
GRANT ALL PRIVILEGES ON azuriom.* TO 'azuriom'@'192.168.0.2' IDENTIFIED BY 'azorium';
GRANT ALL PRIVILEGES ON azuriom.* TO 'azuriom'@'localhost' IDENTIFIED BY 'azorium';