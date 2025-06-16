DROP DATABASE IF EXISTS movies;
CREATE DATABASE movies;
USE movies;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE movies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  user_id INT NOT NULL,
  published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE votes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  movie_id INT NOT NULL,
  vote_type ENUM('like', 'hate') NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE (user_id, movie_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password)
VALUES 
  ('user1', 'user1@example.com', '$2y$10$TI9N7jjmlPXmXUMEvbwEeukCOwwG0OPewE.PW/QG06IyiCkayXaBi'),
  ('user2', 'user2@example.com', '$2y$10$0xOhtEhBxHjx9Wr8xDye2eVsak1RDbfx9Okubet39oxU8HQRzfJDS');

INSERT INTO movies (title, description, user_id)
VALUES
  ('Inception', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod', 1),
  ('Interstellar', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod', 1),
  ('The Matrix', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod', 2),
  ('Star Wars: Episode 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod', 2);