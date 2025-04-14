#!/bin/bash

cd backend/ && composer install --ignore-platform-reqs -o -n && docker-compose up -d
cd ../frontend/ && npm install && docker-compose up -d
cd ../

docker exec -it api php artisan migrate:fresh --seed

echo "Backend and frontend are up and running."
echo "You can access the frontend at http://localhost:5173 and the backend at http://localhost:8000."
echo "To stop the services, run ./stop.sh"