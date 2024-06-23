# Laboratory Equipment Management System

This project is a web-based Laboratory Equipment Management System developed using PHP and MySQL for 5th semester Database Management Systems subject at University of Jaffna. The system facilitates the management of laboratory equipment reservations and ensures smooth handling of equipment requests by both students and administrators.

## Features

### For Students:
- **Unified Login Page**: Students can log in using the same page as the admin.
- **Place Requests**: Students can place requests for equipment based on availability.
- **View Available Equipment**: Students can view the list of available equipment before placing a request.

### For Administrators:
- **Unified Login Page**: Admins can log in using the same page as students.
- **Manage Requests**: Admins can accept or reject equipment requests placed by students.
- **View Requests**: Admins can view all pending, accepted, and rejected requests.

## Pages

### 1. Login Page
![Login Page](https://github.com/janith99hansidu/Laboratory-Equipment-Management-System/blob/main/Assets/login.png)
*The unified login page for both students and administrators.*

### 2. User Page (Student Dashboard)
![User Page](https://github.com/janith99hansidu/Laboratory-Equipment-Management-System/blob/main/Assets/student.png)
*Dashboard for students to view available equipment and place requests.*

### 3. Admin Page (Admin Dashboard)
![Admin Page](https://github.com/janith99hansidu/Laboratory-Equipment-Management-System/blob/main/Assets/admin.png)
*Dashboard for administrators to manage equipment requests.*

## Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/your-repository/lab-equipment-management.git
   cd lab-equipment-management
   ```

2. **Set Up the Database**
   - Import the `lab_management.sql` file into your MySQL database.
   - Update the database configuration in the `config.php` file.

3. **Configure the Web Server**
   - Ensure you have PHP and MySQL installed.
   - Place the project in your web server's root directory (e.g., `htdocs` for XAMPP).

4. **Run the Application**
   - Open your web browser and navigate to `http://localhost/lab-equipment-management`.

## Usage

### Student Workflow
1. **Login**: Use your student credentials to log in.
2. **View Equipment**: Check the availability of the equipment.
3. **Place Request**: Submit a request for the desired equipment.
4. **Track Requests**: Monitor the status of your requests from your dashboard.

### Admin Workflow
1. **Login**: Use your admin credentials to log in.
2. **View Requests**: Access all pending equipment requests.
3. **Manage Requests**: Accept or reject requests as needed.
4. **Monitor Inventory**: Keep track of equipment availability and usage.

## Technologies Used
- **Front-end**: HTML, CSS, JavaScript
- **Back-end**: PHP
- **Database**: MySQL

## Contributing
We welcome contributions to improve this project. Please fork the repository and submit pull requests for any enhancements or bug fixes.

## License
This project is licensed under the MIT License.

## Contact
For any questions or suggestions, please contact [janithhansiduyapa1@gmail.com].

---