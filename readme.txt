Begin by renaming .env.example to .env (cp .env.example .env) then start the API and frontend services, navigate to the root directory of the project (movie-world) and run the following command in your terminal:

docker-compose up -d --build

This will build the necessary Docker images and start the containers in detached mode.

Once the setup is complete:

The frontend will be accessible at: http://localhost:3000/movies

All API requests will be handled by the backend at: http://localhost:8000

Test users credential:

user1@example.com
password1
user2@example.com
password2
