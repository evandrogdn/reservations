#!/bin/bash

cd backend/ && docker-compose down
cd ../frontend/ && docker-compose down
cd ../
echo "Backend and frontend have been stopped."
echo "You can start them again by running ./start.sh"