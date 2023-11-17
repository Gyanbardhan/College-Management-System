# College Management System

The College Management System is a comprehensive web-based application designed to efficiently manage various aspects of a college environment, facilitating smooth interactions between students, instructors, and administrative staff. This project is intended to streamline and enhance the administrative and academic processes within a college setting.

## Key Features:

1. **Department Management:**
   - Each college department is represented, with the ability to offer multiple courses.
   - Every department has a designated head instructor responsible for overseeing departmental activities.

2. **Instructor Management:**
   - Instructors are associated with specific departments.
   - An instructor can be the head of only one department.
   - Instructors have the flexibility to teach multiple courses.

3. **Course Management:**
   - Courses are offered by departments and are taught by instructors.
   - A course can have multiple enrolled students, and students can enroll in multiple courses.

4. **Student Enrollment:**
   - Students can enroll in any number of courses offered by various departments.
   - Each course can have any number of students.

5. **User Authentication and Authorization:**
   - Secure user authentication to ensure that only authorized individuals access the system.
   - Different roles for students, instructors, and administrators with role-specific functionalities.

## Technology Stack:

- **Frontend:**
  - HTML, CSS, JavaScript
  - Use of modern frontend frameworks 

- **Backend:**
  - PHP for server-side scripting
  - MySQL as the relational database management system

## Project Structure:

- **Admin_page1.php,Admin_page2.php,Admin_page3.php,Admin_page4.php,Admin_page5.php,Admin_page6.php,Admin_page7.php**: Admin dashboard for managing users, departments, and other administrative tasks.
- **Student_page.php**: Student dashboard for accessing course information, grades, and other student-related features.
- **Instructor_page1.php,Instructor_page2.php,course-info.php**: Instructor dashboard for managing courses, grades, and other instructor-related functionalities.

## How to Run:

1. Clone the repository to your local machine.
2. Set up a local server environment (e.g., XAMPP).
3. Copy data of college_db to xampp->mysql->data.
4. Configure the database connection in the PHP files.
5. Open the project in a web browser and start exploring the features.

Feel free to contribute, report issues, or suggest improvements to make this College Management System even more robust and user-friendly!
