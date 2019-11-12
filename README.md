# TCReviewSystem API
Simple Client Review API Solution

API JSON: https://github.com/KenMorencyTC/TCReviewSystem/blob/master/wwwroot/rating/openapi.json

SWAGGER API: https://app.swaggerhub.com/apis/KenMorencyTC/tc-ratings_reviews_system/2.0

GitHub Repository: https://github.com/KenMorencyTC/TCReviewSystem

Component Description: 

TC Review System API provides users the ability to login, submit a review and view existing reviews. It was developed in JS/PHP on Azure Web App processing GET/POST requests and delivers responses in JSON format. All reviews stored in MySQL database. Users not authenticated on the server are prompted for username and password checked against the database. 

User Interface developed in PHP and includes login functionality supported my MySQL database. The user interface, at /reviews/index.php, uses AJAX requests and dynamically updates content based on user input. I have opted for basic Authentication and not to implement OAuth for simplicity sake and it was not a requirement for this prototype.

Database is simple MySQL database with two tables: ratings & users.

```
RATINGS:
CREATE TABLE `ratings` (
`id` int(6) UNSIGNED NOT NULL,
`rating` int(6) NOT NULL,
`review` varchar(250) DEFAULT NULL,
`userid` varchar(255) NOT NULL,
`reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```
```
USERS:
CREATE TABLE `users` (
`id` int(11) NOT NULL,
`username` varchar(255) NOT NULL,
`password` varchar(255) NOT NULL,
`created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
```

Rationale: 

PHP is a very common language and supported by most platforms. PHP provides a low overhead solution to provisioning proof of concept prototypes. 

MySQL is a widely used open source database and supports most typical operations required for prototyping.

JQuery is leveraged to facilitate AJAX requests. A very common JS library used everywhere.

Bootstrap CSS used to quickly and easily prototype attractive UIs for web applications.

