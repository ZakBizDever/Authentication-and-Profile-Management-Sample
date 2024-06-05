# CobblewebAuth

## Description
CobbleAuth is an interface to register, authenticate users and display profiles.

Composed from ``Symfony 4.4 API`` and ``React 18 APP``.

## Requirements

### API
- PHP 8.x
- Composer
- Symfony CLI (optional)

### Front
- NPM

## Setup

1. Clone the repository:

    ```bash
    git clone https://github.com/ZakBizDever/Authentication-and-Profile-Management-Sample.git
    ```

2. Install dependencies:
- API:
    ```bash
    cd api
    composer install
    ```

- Front APP:
    ```bash
    cd front
    npm install
    ```

3. Configure environments variables:

   Create a `.env` files **for both API & Front** and set the necessary variables, such as database connection details and AWS S3 credentials.

   **N.B:** *Refer to provided .env.example files.*


4. Run migrations for API:

    ```bash
    php bin/console doctrine:migrations:migrate
    ```

5. Key pair:

   Generate Key pair for JWT Token generation:
    ```bash
   php bin/console lexik:jwt:generate-keypair
    ```

6. Start the servers:
- API:
    ```bash
    symfony server:start
    ```
- Front APP:
    ```bash
    npm start
    ```
7. Access :

- API at http://localhost:8000
- Front at http://localhost:3000

8. (optional) DDEV environement setup :
    
    *You can use DDEV instead of the above.*
   1. Instal DDEV according to your OS.
   2. Run :
   ```bash
   ddev start
   ```

    
## API Endpoints

### 1. User Registration

- **Endpoint:** `/api/users/register`
- **Method:** POST
- **Request Payload:**
  ```json
  {
    "firstName": "example",
    "lastName": "example",
    "email": "example@example.com",
    "password": "password",
    "avatar": "base64encodedimage"
    "photos[]": "base64encodedimages"
  }
  
- **Response (on success):**
  - `200 OK` - Successful response with the following:
      ```json
    {
       "message": "User registered successfully."
    }
  - `400 Bad Request` - Unable to perform registration due to input validation, existing user or other.

### 2. User Login

- **Endpoint:** `/api/users/login`
- **Method:** POST
- **Request Payload:**
  ```json
  {
    "email": "example@example.com",
    "password": "password"
  }

- **Response (on success):**
    - `200 OK` - Successful response with JWT Token valid for 3600s.
        ```json
      {
         "token": "JSONToken"
      }
    - `400 Bad Request` - Unable to login due to wrong credentials.

### 3. User Profile

- **Endpoint:** `/api/users/me`
- **Method:** GET
- **Headers:**

  ```Authorization: Bearer JSONToken```

  - **Response (on success):**
      - `200 OK` - Valid token, extraction of the user details.
          ```json
        {
             "fullName": "example example",
             "email": "example@example.com",
             "avatar": "https://s3.amazonaws.com/s3bucket/avatar.jpg",
             "photos": [ //either links to AWS S3 or Local storage based on registration.
                 "https://s3.amazonaws.com/s3bucket/photo1.jpg",
                 "https://s3.amazonaws.com/s3bucket/photo2.jpg",
                 "https://s3.amazonaws.com/s3bucket/photo3.jpg",
                 "https://s3.amazonaws.com/s3bucket/photo4.jpg"
             ],
             "storage": "aws", //or "local"
             "active": true,
             "createdAt": "2023-12-31T12:34:56Z",
             "updatedAt": "2023-12-31T12:34:56Z"
        }
      - `400 Bad Request` - Invalid or Not found JWT Token.

## APP Routes

#### 0. Default: redirects /login
#### 1. Login: ```/login```
#### 2. Registration: ```/register```
#### 3. Profile details: ```/profile```
#### X. Not found: redirects to 404

## Bundels

### Symfony API
- Symfony Framework v. 4.4
- Doctrine ORM
- LexikJWTAuthenticationBundle
- NelmioCorsBundle
- Gedmo
- ...

### React APP
- React 18 (latest at the current time)
- react-router-dom : routing.
- react-hook-form : form validation.
- Chakra UI : Material design.
- Axios : HTTP Request.
- Slick Carousel : Photos Slider/Carousel.
- ...

## Commands

### Newsletter

A newsletter is broadcasted via CRON Job each day at 6:00 PM via NewsletterCommand.
#### For immediate Execution:
- Immediate:
```bash
php bin/console app:broadcast-newsletter
```
**N.B:** Check config. files to personalise sender, subject and message.

## File upload Storage
The CobbleAuth app supports 2 distinct types of storage, Local and AWS S3.
In order to switch between storage type, following param is at your service:
```bash
// ./api/config/services.yaml

upload_storage: 'local' //or 'aws'
```

## Authentication

Authentication is required via JWT Token for accessing endpoints other than /register and /login.

## Security

Security is handled including SQL Injection attempts.

Passwords are stored as hashed/encoded.

## Validations & Error Handling 

- **Inputs:** Fields validation is handled on both Client and API sides, and returns/displays appropriate errors.
- **The CobbleAuth API** returns appropriate HTTP status codes along with error messages in JSON format to help you identify and resolve any issues.
- **The Cobble APP** handles HTTP status codes and other client-side errors.

# Bonus: Animated Theme
Uncomment following line to have animated theme.
```bash
//front/public/index.html:11
<div class="background-shapes"></div>
```


