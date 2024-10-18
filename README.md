# Software Requirements Specification (SRS) for Blog Post System

## 1. Introduction

### 1.1 Purpose
The purpose of this document is to define the requirements for the Blog Post System, which is being developed using HTML, CSS, JavaScript, and PHP. The system will allow users to create, manage, and publish blog posts. Authentication and authorization mechanisms will ensure that only registered users can create and manage posts. User and post data will be stored in a database for long-term access.

### 1.2 Scope
The Blog Post System will include features such as user registration, authentication, authorization, post creation, editing, deletion, and multimedia integration. The system will store user and post data in a MySQL database, with role-based access control for users and admin functionality for managing content.

### 1.3 Definitions, Acronyms, and Abbreviations
- **HTML**: HyperText Markup Language
- **CSS**: Cascading Style Sheets
- **JavaScript**: Client-side scripting language
- **PHP**: Server-side scripting language
- **MySQL**: Database Management System
- **CRUD**: Create, Read, Update, Delete

### 1.4 References
- HTML, CSS, JavaScript, and PHP documentation
- MySQL Database Reference

## 2. System Overview

The Blog Post System is a web-based application that enables users to register, log in, and create blog posts. Authorized users will be able to create, edit, and delete posts, while administrators will have additional control over users and posts. Users will be able to view and comment on published posts. The system will ensure responsive design and secure access to features through authentication and authorization processes.

## 3. Functional Requirements

### 3.1 User Registration and Authentication
- **User Registration**: Users can register using their email, username, and password. The system will store this information in the database.
- **Login/Logout**: Registered users can log in to the system by providing their credentials. Logged-in users can log out when desired.
- **Password Recovery**: Users can recover their password using a reset link sent to their registered email.

### 3.2 Authorization
- **Role-Based Access**: The system will differentiate between regular users and administrators. 
    - **User Role**: Can create, edit, delete their own posts.
    - **Admin Role**: Can manage users and their posts.
- **Access Control**: Certain actions (like deleting other users' posts) will be restricted to admin users only.

### 3.3 Blog Post Management
- **Create Post**: Users can create a new blog post, which includes a title, content, and optional images or videos.
- **Edit Post**: Users can edit their own posts.
- **Delete Post**: Users can delete their own posts.
- **Publish Post**: Posts can be saved as drafts or published immediately.
- **View Posts**: Users and visitors can view published posts in a public blog view.

### 3.4 Database Management
- **User Data**: Store user registration data, including username, email, and hashed passwords, in the MySQL database.
- **Post Data**: Store blog post data including title, content, multimedia URLs, and timestamps in the MySQL database.
- **User-Post Relationship**: Each post will be linked to a user through a user ID.

### 3.5 Commenting and Social Sharing
- **Comments**: Registered users can comment on blog posts.
- **Social Sharing**: Posts can be shared on social media platforms via integrated share buttons.

## 4. Non-Functional Requirements

### 4.1 Performance
- The system should be able to handle multiple users simultaneously with minimal latency. 
- Efficient database queries will be used to ensure fast retrieval of posts and user data.

### 4.2 Usability
- The system will have a clean, responsive design to ensure it works well on all devices (desktops, tablets, smartphones).
- An intuitive UI will make it easy for users to navigate, create, and manage posts.

### 4.3 Security
- **Password Security**: User passwords will be hashed and stored securely in the database using PHP's `password_hash()` function.
- **Input Validation**: Forms will have client-side and server-side validation to prevent injection attacks.
- **Session Management**: User sessions will be securely managed, with session timeouts and protection against session hijacking.
- **Database Security**: Protection against SQL injection will be implemented using prepared statements.

### 4.4 Scalability
- The system will be designed to allow for future expansion, such as adding more features or handling an increased number of users.
- The database structure will support additional tables and relationships as new features are introduced.

### 4.5 Maintainability
- The system will be modular to allow for easy maintenance and updates.
- Proper documentation and comments will be provided in the codebase to facilitate future modifications.

## 5. System Architecture

### 5.1 Client-Side
- **HTML/CSS**: Used for rendering and styling the user interface.
- **JavaScript**: Used for form validation, dynamic content loading, and client-side logic.

### 5.2 Server-Side
- **PHP**: Responsible for handling user requests, interacting with the database, and serving dynamic content.
- **Database (MySQL)**: Stores user and post data in tables, ensuring relational integrity.

### 5.3 Database Schema
- **Users Table**: Stores user details (ID, username, email, hashed password).
- **Posts Table**: Stores post details (ID, title, content, user_id, timestamps).
- **Comments Table**: Stores comments linked to posts.

## 6. Use Case Scenarios

### 6.1 User Registration
- A new user visits the site and registers by providing a username, email, and password. The data is validated and stored in the database.

### 6.2 Create a Blog Post
- A registered user logs in, navigates to the blog post creation page, fills out the form with a title, content, and optionally uploads an image or embeds a video. They then save the post as a draft or publish it.

### 6.3 Admin Manages Users
- The admin logs in, views a list of users, and can delete users or manage their blog posts.

## 7. Conclusion

The Blog Post System provides a secure, scalable, and user-friendly platform for users to create, manage, and share their blog posts. By implementing proper authentication, authorization, and database management, the system ensures that both users and administrators can efficiently interact with the system, while maintaining security and performance.
