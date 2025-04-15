Here is a README file for setting up the Docker XAMPP and MySQL environment in both GitHub Codespaces and a VPS:

---

# Docker XAMPP and MySQL Setup in GitHub Codespaces and VPS

This repository provides a Dockerized development environment with XAMPP (Apache + PHP) and MySQL, allowing you to easily run a local development environment in both GitHub Codespaces and on a VPS.

## 🚀 Getting Started

Follow these steps to set up and run the development environment in **GitHub Codespaces** or a **VPS**.

### Prerequisites

- **Docker** installed on your machine. If you don't have it installed, follow the official instructions: [Docker Installation](https://docs.docker.com/get-docker/)
- **Docker Compose** installed on your machine. Instructions: [Install Docker Compose](https://docs.docker.com/compose/install/)
- **GitHub Codespaces** or **VPS** setup with Docker installed.

---

## 📦 GitHub Codespace Setup

1. **Clone the Repository**  
   Clone this repository into your GitHub Codespace workspace:

   ```bash
   git clone <repository-url>
   cd <repository-folder>
   ```

2. **Set Up the Codespace Configuration**  
   Make sure your repository contains the `.devcontainer/` directory. If not, you can create it and configure the devcontainer setup as follows:

   ```bash
   mkdir -p .devcontainer
   ```

   Then create a `devcontainer.json` file within `.devcontainer/`:

   ```json
   {
     "name": "Docker XAMPP and MySQL",
     "dockerFile": "Dockerfile",
     "context": "..",
     "postCreateCommand": "docker compose up --build -d",
     "forwardPorts": [80],
     "extensions": [
       "ms-azuretools.vscode-docker",
       "felixfbecker.php-debug",
       "ms-vscode.php"
     ]
   }
   ```

   This will automatically build and run the Docker containers when the Codespace is created.

3. **Start Your Codespace**  
   Open your repository in GitHub Codespaces. Codespaces will use the `.devcontainer/` configuration to set up the environment.

4. **Access the Application**  
   After Codespace setup, you can access your application at:

   ```
   http://localhost
   ```

   Your PHP files should be placed inside the `./xampp/my_pages` directory, which is mapped to the `/www` directory inside the Docker container.

---

## 📦 VPS Setup

For setting up the environment on your VPS:

1. **Clone the Repository**  
   Clone this repository to your VPS:

   ```bash
   git clone <repository-url>
   cd <repository-folder>
   ```

2. **Install Docker and Docker Compose**  
   If Docker and Docker Compose are not already installed on your VPS, install them using the following commands:

   ```bash
   # Install Docker
   curl -fsSL https://get.docker.com -o get-docker.sh
   sudo sh get-docker.sh
   sudo usermod -aG docker $USER
   sudo systemctl enable docker

   # Install Docker Compose
   sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
   sudo chmod +x /usr/local/bin/docker-compose
   ```

3. **Start the Containers**  
   Run the following command to start the Docker containers:

   ```bash
   docker compose up --build -d
   ```

4. **Access the Application**  
   Your application will be available on your VPS’s public IP address at port 80:

   ```
   http://<your-vps-ip-address>
   ```

---

## 🛠️ Docker Setup

The `docker-compose.yml` file defines the services:

- **xampp**: The XAMPP server (Apache + PHP).
- **db**: The MySQL server.

```yaml
version: '3.8'

services:
  xampp:
    image: tomsik68/xampp
    ports:
      - "80:80" # Expose the web server on port 80
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

---

## 📄 Example Code (index.php)

Here is an example `index.php` file that connects to the MySQL database:

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
   If MySQL takes longer to start, you might want to retry the connection in your PHP code.

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

This README provides instructions for setting up the Dockerized XAMPP and MySQL environment on both **GitHub Codespaces** and a **VPS**. It includes troubleshooting tips for common issues and further configuration details. Adjust the instructions based on your specific requirements and environment.