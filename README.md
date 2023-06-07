# yii2_bartender
bartender mini exampple


APP folder contains the application built with Yii2

Dockerfile is defined for Yii2 

docker-compose.yml handles the creation of services required to have the backend and the db, and connects them together

To launch the application

docker-compose up --build  

navigate to container 
 - php yii migrate
 - php yii queue/listen
 - php yii web-socket-server/start

the website link is http://localhost:8080/site/index

your must be able to see drink types and be able to order a drink

[GET] Get all orders - http://localhost:8080/orders 

[POST] Create an order- http://localhost:8080/bartender/create-order

requiered params 
 drink   
 quantity 
 
 
Steps how I build 
  
 




