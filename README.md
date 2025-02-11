<div align="center">
    <img src="https://raw.githubusercontent.com/JRamosEEC/JJLMovies-PublicPortfolio/refs/heads/main/images/logo-icon.png">
</div>

# JJL Movie Review Web-Application/Website Project

### Project By : Justin D. Ramos

### Project Link : https://www.jramosportfolio.com

### Contributors
- Kibert, Elvis
- Lance, Lespiansse
- Botelho, Jacob


This project was initially a team oriented final assignment referred to as the "Capstone Project". This assignment marked the last step of the Associate Degree in Software Engineering at New England Institute of Technology.

The requirements of the final was for me and my classmates to come up with a marketable idea and implement it into a fully functional CRUD application.

The submission for the final project was simply php, html, and css running on a local host to show our idea of a movie review site. The idea of the site being that indie film producers could use the site to throw around ideas and get some form of feedback all the while we would protect individuals original idea rights to avoid copyright infringement.

The programming at the time was rudimentary and my teammates contributions were limited so I over hauled the entire project to be of my own solutions, though credit to the idea itself can be shared with my classmates. I use this application as a way to progress my knowledge in different technologies and practice them by implementing them into a final product that can be demonstrated.

## Important Notes

### File Hierarchy


```JJLMovies:
├───Backend
│   ├───Config
│   ├───NodeServer
│   │   ├───DB
│   │   └───S3-URL
│   └───PHP-DB
├───Frontend
│   ├───Admin_Functions
│   ├───Blueprints
│   ├───CSS
│   ├───JavaScript
│   │   ├───AdminPanel
│   │   ├───ChatBox
│   │   ├───Cookies
│   │   ├───ImageUpload
│   │   ├───Nav
│   │   ├───NotificationPopup
│   │   ├───PaymentGateway
│   │   └───Profile
│   ├───Login-Signup
│   ├───MoviePage
│   └───UserProfile
└───images
```

### Logins & Test Accounts
 - Accounts can be created and so can the movies by them as well, but most of the current accounts are easily accesible to view already created test data (Including the chat box messages between them and other users)
 - Most accounts I make to test the site can be accessed with the display username and password being the same
 - For example login 987654321 as username and password and same with 123456789
 - The posts, reviews, and messages are simply lorem ipsum for the purposes of testing, but the site is free to explore

 ### Payment System
- The donation feature is on TEST MODE through stripe!
- Genuine payment information will not be processed (I have tested this)
- Stripe offers a variety of test card data here https://stripe.com/docs/testing
- My goto for testing payments is Card Number: 4242 4242 4242 4242 Exp: (Any Future Date) CVC: (Any three-digit number)

## Architecture
I recently migrated two separated clouds into one stronger architecture and therefore I will present the original architecture as well as the new post mirgation architecture.

### Hosted On AWS Cloud
| PRE-Migration                                           |                 POST-Migration                                                                            |
| ------------------------------------------------------- | --------------------------------------------------------------------------------------------------------- |
| EC2 instance running Linux 2 AMI                        |                 ECS & ECR & Docker W/ Auto-Scaling EC2 Intstances Running Containerized Application (Linux Alpine) |
| NGINX reverse proxy                                     |                 NGINX Reverse Proxy (Added Stronger Caching & Now Uses HTTP 2)                                     |
| Apache Web Server                                       |                 Apache Web Server                                                                                  |
| PHP & Node running on the EC2 Instance                  |                 PHP & Node Running As ECS Containerized Task's                                                     |
| Strict Content Security Policy                          |                 Strict Content Security Policy                                                                     |
| TLS/SSL certificates (Utilizing AWS Certs)              |                 TLS/SSL Certificate (Let's Encrypt W/ Automate Renewal)                                            |
| Route 53 Name Servers                                   |                 Route 53 Name Servers                                                                              |
| S3 Bucket for object storage                            |                 S3 Bucket For Object Storage                                                                       |
| IAM users for separated access                          |                 IAM Users W/ Separated Access                                                                      |
| RDS for data storage                                    |                 RDS Data Storage                                                                                   |
| Manual Build & Deployment                               |                 Automated CI/CD Build & Deployment System                                                          |

### Server Side In PHP
- Modularized PHP to serve dynamic web pages
- Backend PHP for data storage with SQL queries
- Javascript to make the current page dynamic
- CSS to provide styling

### API's & Web Services With Node.js
- Hosted on the same instance through a different port for every API (One EC2 instance to save costs)
- Routing to the api port is done through NGINX
- Express for rest api's
- Socket.io for sustained transmission
- CORS for added security
- MySQL for data storage
- Stripe for payment processing

### Data Storage With MySQL
- Hosted on and RDS instance in AWS
- Strict security group for connections
- Separate MySQL user with specified access
- Sensitive information either hashed or encrypted

## AWS Cloud Design Diagram (POST-Migration)
<div align="center">
    <img src="https://raw.githubusercontent.com/JRamosEEC/JJLMovies-PublicPortfolio/refs/heads/main/images/Foodi_CloudDiagram.png">
</div>

## AWS Cloud Design Diagram (PRE-Migration)
<div align="center">
    <img src="https://raw.githubusercontent.com/JRamosEEC/JJLMovies-PublicPortfolio/refs/heads/main/images/Ramos_JJLCloud.drawio.png">
</div>

## Database ERD
<div align="center">
    <img src="https://raw.githubusercontent.com/JRamosEEC/JJLMovies-PublicPortfolio/refs/heads/main/images/Ramos_JJL_Movies_DB_ERD.png">
</div>

## Base Application Features

### Data Storage & CRUD Functionality
- User accounts
- Movie posts
- User reviews
- User follower relationships
- User messages
- Movies and all their data can be edited including poster images
- Movies can also be deleted by their owners

### Search Functionality
- Uses an SQL query to find similar movie names and allows users to find specific results
- Uses PHP and AJAX to request data and update page with the results without refreshing

### User Following
- Navigate to user accounts through their movie posts
- Under their account is all of their movies
- Additionally you can follow them or be followed by others
- This enables messaging back and fourth to followers

### Movie Reviews & Ratings
- Displayed under a movie's page
- Must be logged in to post a review
- Reviews can be seen by anyone
- Movies display an averaged rating

### Admin & Owner Accounts
- Used for moderation
- Admins can delete user accounts and all their data if community guidelines are violated
- My 'owner' account role is currently how accounts get upgrade or downgrade user roles to create admins

### Basic Website Functions
- Movie share links
- Copy to clipboard
- Notification Popup

## Key Application Features

### Image & File Upload To An S3 Bucket
- Node web service that'll take proper requests and respond with a secure, AWS generated, token/URL that'll allow the user to post a file to the S3 Bucket
- Only currently used for movie poster images, but I will be implementing profile images and possibly movie banners later on

### Messaging System Through Websockets Or Ajax Polling
- Created initially for a webservices class assignment that wanted a restful API, but I wanted also wanted to learn a new technology so instead I ended up making two version that work with each other
- One is a web socket used for instantaneous back and fourth message delivery over a sustained connection to a web service in the backend
- The other uses AJAX polling to a Restful API that's running in the backend
- A slider changes which message system you are currently on and they seamlessly work together
- Both web services store messaging data to the database through the same functions and queries
- Web sockets will also try to find a connection to the recipient push the message to them if they are online (This done with user id's to manage connections)
- The SQL queries use a pool of MySQL connections for additional optimization

### Payment Gateway
- Donation feature in the navigation
- Implemented with Stripe's API's to act as a payment processor
- User communicates to stripe to produce a transaction token based on a provided public token
- User sends transaction token to my payment gateway web service which confirms the transaction with a private token
