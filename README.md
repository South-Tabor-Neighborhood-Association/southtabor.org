# South Tabor Neighborhood Association (southtabor.org) — Local Development Setup

Welcome! This repository contains the code and configuration for running the South Tabor Neighborhood Association website locally using Docker.

---

## 🛠️ Requirements

Before starting, ensure you have the following installed on your machine:
1. **Git**
2. **Docker Desktop**
   * [Docker Desktop for Mac](https://www.docker.com/products/docker-desktop/) (supports Apple Silicon and Intel)
   * [Docker Desktop for Windows](https://www.docker.com/products/docker-desktop/) (WSL 2 backend recommended)

---

## 🚀 Getting Started

### 1. Repository Structure
The local development environment mounts the website files directly from your host machine:
* `docker-compose.yml` — Orchestrates the local containers.
* `southtabor.org/` — The WordPress document root.
* `southtabor.org/db_backup.sql` — Database backup file (automatically imported on first boot).
* `southtabor.org/wp-config.php` — Database configuration.

### 2. Prepare the Database Backup
Make sure you have a database SQL dump named `db_backup.sql` placed in the `southtabor.org/` folder. This is required for the database container to initialize.
* **Path:** `southtabor.org/db_backup.sql`

### 3. Start the Environment
Run the following command from the root of the project directory:
```bash
docker compose up -d
```
* **Local Site URL:** [http://localhost:8080](http://localhost:8080)
* **Local Database Host & Port:** `localhost:3306`

---

## ⚙️ Local Database Credentials

These are configured in `docker-compose.yml` and `wp-config.php` for local environment mirroring:
* **Host:** `localhost` (or `db` / `mysql.southtabor.dreamhosters.com` inside the Docker network)
* **Database Name:** `southtabor_dreamhosters_`
* **User:** `southtabordreamh`
* **Password:** `your_dev_password`

---

## 💻 OS-Specific Guidelines

### 🍎 macOS Setup
1. **File Sharing:** Ensure that the project directory is shared with Docker (usually enabled by default in *Docker Desktop > Settings > Resources > File sharing*).
2. **Performance:** The default virtualized filesystem mount is fast enough for development. No extra configuration is needed.

### 🪟 Windows Setup (WSL 2 Recommended)
To prevent extreme filesystem slowness and permission conflicts, do **not** run Docker containers directly out of the Windows host filesystem (e.g., `/mnt/c/Users/...` or `C:\Users\...`).
1. **WSL 2 Integration:** Install the Windows Subsystem for Linux (WSL 2) and enable the integration in *Docker Desktop > Settings > General > Use the WSL 2 based engine*.
2. **Clone inside WSL:** Open your WSL terminal (e.g., Ubuntu) and clone the repository directly inside the Linux environment filesystem (e.g., `/home/username/projects/southtabor.org/`).
3. **Run Commands:** Execute `docker compose up -d` from *inside* the WSL terminal. This guarantees optimal database and file access performance.

---

## 📋 Common Commands

### Start Container Services
```bash
docker compose up -d
```

### Stop Services (Preserves Database)
```bash
docker compose down
```

### Reset the Database (Destructive)
To completely wipe your local database changes and re-import a clean copy of `db_backup.sql`:
```bash
docker compose down -v && docker compose up -d
```

### Check Logs
```bash
docker compose logs -f wordpress
```

---

## ⚠️ Important Configuration Notes
* **Local WordPress URL Overrides:** The following lines are set in `southtabor.org/wp-config.php` to bypass production domain redirects:
  ```php
  define( 'WP_HOME',    'http://localhost:8080' );
  define( 'WP_SITEURL', 'http://localhost:8080' );
  ```
  **Do not commit these overrides to production.** They are only intended for local development.
