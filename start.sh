#!/bin/bash

cd backend/ && docker-compose up -d
cd ../frontend/ && docker-compose up -d
cd ../

docker exec api -it php artisan migrate:fresh --seed

echo "Backend and frontend are up and running."
echo "You can access the frontend at http://localhost:5173 and the backend at http://localhost:8000."
echo "To stop the services, run ./stop.sh"