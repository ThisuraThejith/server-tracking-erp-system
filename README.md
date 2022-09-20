# Server Tracking ERP System

This is a simple server tracking and managesment system which provides functionalities for users to create, read and delete servers including ram modules.

## Technologies Used

* <b>PHP 8.0.5</b>
* <b>PHPUnit 9.5</b>
* <b>Symfony 5.4</b>
* <b>Respect Validation 2.2</b>
* <b>MySQL 8.0</b>
* <b>Vue3</b>
* <b>Axios</b>
* <b>Docker</b>
* <b>Docker-Compose</b>

## Key Functionalities - Backend

  * Create a server
  * Get all servers
  * Get a server by assetId
  * Delete servers
  * Add ram modules to server
  * Get ram modules of a server
  * Remove ram modules from server

## Key Functionalities - Frontend

  * List all servers
  * Delete servers
  * List ram modules of a server
  * Delete ram modules of a server (Until only one ram module is left)
  * Create servers

## Pre-requisites

  * Docker 
  * Docker-Compose

## Installation (on Ubuntu)

  * Install Docker and Docker-Compose (Refer https://docs.docker.com/engine/install/ubuntu/ and https://docs.docker.com/compose/install/).
  * Clone this repository.
  * Navigate in to the <b>server</b> directory in the cloned directory.
```bash
cd server-tracking-erp-system/server
```
  * First copy the file <b>.env.template</b> by the name <b>.env</b> by executing the following command. (Ideally the mysql credentials should not be put in the file. But as I configured the mysql container with some default DB properties, I added them in to the .env.template file for your ease of installation).

```bash
cp .env.template .env
```
  * Now you can build and up the containers by executing the following command inside the <b>server-tracking-erp-system</b> root directory, 
```bash
docker-compose up -d --build
```
  * After that access the php container by executing,
```bash
docker exec -it erp_php bash
```
  * Navigate to the <b>server</b> directory.
```bash
cd /server
```
  * Install the dependencies by executing,
```bash
composer install
```
  * To execute the DB migrations, in the same directory execute the following command. This will create the database tables and add default data to necessary tables.
```bash
php bin/console doctrine:migrations:migrate
```
  * Now all things are set and you can access the backend server at,
```bash
http://localhost:8080/
```
  * Access the adminer console to view the database by typing the below URL in your web browser. (Provide the relevant credentials in the login screen of adminer)

http://localhost:9001/

  * Access the web application at,

http://localhost:4200/


## Tests Added

* <b>Unit Tests</b> - Added for entity classes.

  * For running the unit tests, in the <b>server</b> directory in the <b>php container</b> execute,
```bash
php bin/phpunit tests
```
or

```bash
./vendor/bin/phpunit tests
```

## Installation (on Windows or Mac)

  * Install Docker Desktop (Refer https://docs.docker.com/docker-for-windows/install/ or https://docs.docker.com/docker-for-mac/install/).
  * Follow the above Ubuntu installation process. With docker, you won't have to do any other OS specific installation steps. Use the docker terminal to run the above commands.

## API Details

  * After cloning, navigate in to the <b>apidoc</b> directory in the <b>server directory</b> and open the <b>index.html</b> file in that directory in a web browser.
  * You will see an ordered, detailed description of the APIs with the details and examples of parameters that should be passed to the APIs, responses that will be received, along with success and error codes.

## Test APIs On Postman

  * Navigate in to the <b>postman</b> directory which is in the <b>server directory</b> and open the file <b>Server App.postman_collection.json</b>.
  * Copy the content inside the file.
  * Open postman app and click on <b>File->Import</b> and in the opened window, select <b>Raw Text</b>.
  
  ![image](https://user-images.githubusercontent.com/15060374/172254648-ef597a38-3e03-4212-bc9e-e2856bd43ec4.png)
  
  * Then paste the copied content and click on continue button. By doing this, the api collection with sample request parameters will be imported to your postman.
  * Now you can send requests to the APIs and see how they behave.

## Frontend Walkthrough

  * Once you navigate to http://localhost:4200/ you will arrive at an interface as follows. This is the <b>All Servers</b> view where the details of all the servers are listed.

  ![image](https://user-images.githubusercontent.com/15060374/191343213-2ac494b9-cb90-4284-a349-e8d7c44cf854.png)
  
  * You can delete a server by clicking on the <b>Delete Server</b> button.
  * When you click on the <b>Ram Details</b> button, you'll be directed to the following interface. This is where all the ram modules of the particular server are listed.

  ![image](https://user-images.githubusercontent.com/15060374/191343896-1b4625ba-fe3e-4436-9452-b3a5f5a5b5ef.png)
  
  * Here you can delete ram modules of the particular server by clicking on the <b>Delete Ram</b> button, but until only one ram module is left. When only one ram module is left the <b>Delete Ram</b> button will be disabled as a server must contain at least one ram.
  * When you click on the <b>Create Server</b> link on the top you'll be directed to the server creation interface which looks like the following image.

  ![image](https://user-images.githubusercontent.com/15060374/191344798-135b02d9-6ec9-416e-81e2-2a3f3f25137c.png)
  * Here you can create servers by adding relevant details.
  * <b>Important points to keep in mind when creating servers</b>
      * As the front end validations are not yet implemented, please keep in mind the following things when inputting the relevant values.
          * <b>Asset ID</b> - Give an integer with maximum 9 digits. (ex:- 123456780)
          * <b>Brand</b> - Provide a valid string value.
          * <b>Name</b> - Provide a valid string value.
          * <b>Price</b> - Provide a valid float value. (ex:- 600.50)
          * Remember to select at least one ram module with a quantity for it.
          * <b>Quantity</b> - Provide a valid integer value. (ex:- 4)
  * After adding proper values you can create a server by clicking on the <b>Create Server</b> button. Once the server is successfully created, you'll be redirected to the <b>All Servers</b> view where you can verify the created server is listed properly.

## Further Improvements If Time Permitted - Backend

* Implement user registration functionality.
* Implement user aunthentication mechanism (ex:- Using access tokens)
* Check api accessibility using the authentication mechanism to secure the APIs.
* Add more tests.

## Further Improvements If Time Permitted - Frontend

* Add front end validations.
* Make the front end more attractive by styling.

## Contact Details

* Email:- ttcphilips@gmail.com
