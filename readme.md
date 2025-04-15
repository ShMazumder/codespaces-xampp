Here's a sample README file for your project setup:

---

# Docker XAMPP and MySQL Setup

This repository provides a Dockerized development environment with XAMPP (Apache + PHP) and MySQL, allowing easy local development with persistent data storage for MySQL and web page content.

## 🚀 Getting Started

Follow these steps to set up and run the development environment locally using Docker.

### Prerequisites

- **Docker** installed on your machine. If you don't have it installed, follow the official instructions: [Docker Installation](https://docs.docker.com/get-docker/)
- **Docker Compose** installed on your machine. Instructions: [Install Docker Compose](https://docs.docker.com/compose/install/)

### 1. Clone the Repository

Clone this repository to your local machine:

```bash
git clone <repository-url>
cd <repository-folder>
```

### 2. Build and Start Containers

Run the following command to build and start the Docker containers:

```bash
docker compose up --build -d
```

This will:
- Start two services: `xampp` (Apache + PHP) and `db` (MySQL).
- Expose the web page on `http://localhost`.
- Bind your `./xampp/my_pages` folder to the `/www` folder inside the container.

### 3. Access the Application

Once the containers are up and running, you can access your application at:

```
http://localhost
```

Your PHP file(s) should be placed inside the `./xampp/my_pages` directory, which will be mounted to the `/www` directory inside the XAMPP container.

---

## 🛠️ Docker Setup

### `docker-compose.yml`

The `docker-compose.yml` file defines two services:

- **xampp**: The XAMPP server (Apache + PHP).
- **db**: The MySQL server.

```yaml
version: '3.8'

services:
  xampp:
    image: tomsik68/xampp
    ports:
      - "80:80" # Access the web server on localhost:80
    volumes:
      - ./xampp/my_pages:/www # Mount your web pages
      - ./xampp/my_apache_conf/:/opt/lampp/apache2/conf.d # Custom Apache configurations
      - ./xampp/mysql/mydb:/opt/lampp/var/mysql/mydb # Persistent MySQL data
    restart: always
    depends_on:
      - db  # Ensure MySQL is ready before starting XAMPP

  db:
    image: mysql:5.7
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: mydb
      MYSQL_USER: root
      MYSQL_PASSWORD: root
    volumes:
      - db_data:/var/lib/mysql # Persistent MySQL data

volumes:
  db_data:
```

### 4. **Customizing Apache Configuration**

- Apache configurations can be added or modified inside the `./xampp/my_apache_conf` folder. This will be mounted to `/opt/lampp/apache2/conf.d` in the container.

### 5. **Persistent Data**

- MySQL data is stored persistently in `./xampp/mysql/mydb` on your host machine. Changes made to the database inside the container will be reflected here.

---

## 📄 Example Code (index.php)

This project includes a basic `index.php` file to test the PHP and MySQL connection. Below is the code inside `index.php`:

```php
<?php
echo ("Hello, World!<br /><br />");
echo ("You're using PHP " . phpversion() . ".<br />");

// Test database
$serverName = "db";
$databaseName = "mydb";
$dbUsername = "root";
$dbPassword = "root";
$pdoOpts = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
$serverUri = "mysql:host={$serverName};dbname={$databaseName};charset=utf8mb4";

echo ("Connecting to: <a href=\"\">{$serverUri}</a><br />");

try {
    $pdo = new PDO($serverUri, $dbUsername, $dbPassword, $pdoOpts);
} catch (Exception $ex) {
    echo ("Could not connect to the database: " . $ex->getMessage() . "<br />");
    error_log($e->getMessage());
    die();
}

echo ("Database OK<br />");

try {
    $dbVersion = $pdo->query("SELECT VERSION()")->fetch()["VERSION()"];
} catch (Exception $ex) {
    echo ("Could not connect to the database version info: " . $ex->getMessage() . "<br />");
    error_log($e->getMessage());
    die();
}

echo ("Detected database: {$dbVersion}<br />");
?>
```

---

## 🛠️ Troubleshooting

### **MySQL Connection Issues**

If you encounter issues with MySQL not being available, check the following:

1. **Check the `db` container's status:**

   ```bash
   docker compose ps
   ```

   Ensure that the `db` container is running.

2. **Check the logs for any errors:**

   ```bash
   docker compose logs db
   ```

3. **Ensure XAMPP waits for MySQL to be ready:**  
   The `depends_on` configuration ensures that the XAMPP container waits for the MySQL container to be ready before it starts.

4. **Retry connection logic in PHP:**  
   If MySQL takes longer to start, you might want to retry the connection in your PHP code. Example code is provided above.

### **Access Permissions Issue**

If you encounter permission errors when accessing files (e.g., `index.php`), ensure that the files in `./xampp/my_pages` have the correct permissions for Docker to access them. Try changing the permissions:

```bash
chmod -R 755 ./xampp/my_pages
```

---

## 🔄 Stopping and Removing Containers

To stop and remove the containers:

```bash
docker compose down
```

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

Feel free to adjust this README to your project's needs. It should give a clear understanding of setting up and using the Dockerized environment with XAMPP and MySQL.