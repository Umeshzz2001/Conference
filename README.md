Conference Management System
This project is a Conference Management System that facilitates participant registration and QR-based check-ins for a conference. The system is built as a web application and includes a database backend for data storage.

How to Set Up and Run the Application
Setup the Environment:

Install WAMPServer on your system.
Ensure that PHP and MySQL are working properly.
Download the Project:

Download the zip file and extract it.
Database Setup:

Import the provided SQL files into your MySQL server to create the necessary database and tables.
Open phpMyAdmin or any MySQL client.
Use the first SQL file to create the database structure.(Create the conference database.sql)
Use the second SQL file to set up the tables and any initial data.(conference.sql)
Ensure the database name is conference to match the application configuration.
Configure PHP:

Ensure the phpqrcode-master library is included in your project directory.(its allocated in projectfolder)
Place the project folder in the www directory of your WAMPServer installation.
Run the Application:

Start WAMPServer and navigate to http://localhost/22IT0479/index.html in your browser to access the registration page.
Used index.html to navigate through out the project
Features Implemented
User Registration:

Participants can register by providing their name, email, phone number, password, and track selection.
Validates input fields and checks for email uniqueness.
QR Code Generation:

Generates a unique QR code for each participant containing their registration details.
Saves the QR code in the uploads/ directory for later use.
User can download it.
Database Integration:

Uses the conference.sql file to set up the database and tables.
Stores participant details securely using hashed passwords.
Responsive Design:

The user interface is designed for easy navigation on both desktop and mobile devices.
Future Enhancements
Add email notifications for registration confirmation.
Include real-time participant updates during the conference.
Author
Umesh Sandeepa Hiripitiya

