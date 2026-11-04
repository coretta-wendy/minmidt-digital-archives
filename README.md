# MINMIDT Digital Archives Platform

A web-based digital archiving platform designed to improve the management, preservation, and retrieval of administrative records within the Ministry of Mines, Industry and Technological Development (MINMIDT), Cameroon.


![Project](https://img.shields.io/badge/Project-Academic%20Project-blue)
![Type](https://img.shields.io/badge/Type-Web%20Application-green)
![Backend](https://img.shields.io/badge/Backend-PHP-777BB4)
![Database](https://img.shields.io/badge/Database-MySQL-4479A1)
![Frontend](https://img.shields.io/badge/Frontend-HTML%20%7C%20CSS%20%7C%20JavaScript-orange)
![Framework](https://img.shields.io/badge/Framework-Bootstrap-7952B3)

## Project Overview

This project focuses on the digital management of administrative records, including decrees, decisions, and administrative orders.

It was developed to address challenges associated with physical document storage and time-consuming manual searches. The platform provides a centralized solution for recording, managing, and retrieving administrative documents through advanced search capabilities and role-based access control.

## Key Features

- **Administrative record management:** Create, view, update, and delete administrative records.
- **Advanced search:** Search records using relevant attributes such as reference number, subject, recipient, signatory, and signature date.
- **Real-time filtering:** Combine server-side SQL searches with client-side JavaScript filtering.
- **Excel import:** Import administrative record data from Excel files.
- **User management:** Manage user accounts and their access rights.
- **Role-based access control:** Restrict operations according to assigned user permissions.
- **Document access:** Retrieve and download archived administrative documents.
- **Dashboard and statistics:** Provide an overview of administrative records and platform activity.

## Technology Stack

| Category | Technologies |
|---|---|
| Backend | PHP |
| Database | MySQL |
| Database access | PHP Data Objects (PDO) |
| Frontend | HTML, CSS, JavaScript |
| CSS framework | Bootstrap |
| Dependency management | Composer |
| Local development environment | WampServer / Apache |

### Libraries and tools

The project documentation also identifies the use of:

- PhpSpreadsheet – Excel file processing.
- DataTables – interactive data tables.
- Chart.js – data visualization.
- UML – system modeling.
- Agile Scrum – project development methodology.

## Application Architecture

The application follows a web-based architecture with PHP handling server-side operations, MySQL managing application data, and HTML, CSS, JavaScript, and Bootstrap providing the user interface.

PDO is used to interact with the database.

## Security

The application includes authentication and role-based access control.

The PHP implementation uses password verification and prepared SQL statements in the relevant operations.

Sensitive configuration values, database credentials, and private documents should not be committed to the repository.

## Getting Started

### Prerequisites

Make sure the following tools are installed:

- PHP
- MySQL
- Apache
- Composer
- A web browser
- WampServer or another compatible local PHP environment

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/YOUR_GITHUB_USERNAME/YOUR_REPOSITORY_NAME.git
   ```

2. Move into the project directory:

   ```bash
   cd YOUR_REPOSITORY_NAME
   ```

3. Install PHP dependencies:

   ```bash
   composer install
   ```

4. Create a MySQL database for the application.

5. Configure the database connection using the project's configuration file. Update the database host, name, username, and password according to your local environment.

6. Import the database schema and any required initial data, if the SQL files are included in the repository.

7. Configure Apache to serve the project directory.

8. Open the application in your browser using the local URL configured for your environment.

> **Note:** Installation instructions may need to be adapted to the actual project folder structure and database configuration.

## Project Context

This project was developed as part of an academic project focused on the optimization of administrative records management through digital archiving and advanced search capabilities.

**Research topic:**  
*Optimisation de la gestion des actes administratifs par une plateforme d'archivage numérique dotée de capacités de recherche avancée : cas du MINMIDT.*

## Author

**NDYM Coretta Wendy**

Software Engineering graduate | Master's degree in Management Information Systems

## License

No open-source license has been specified for this repository.