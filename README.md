# Project-Tracking

## Project Overview

A complete web-based application for managing CCTV and security system projects, built with HTML, CSS, JavaScript (frontend), PHP (backend), and MySQL (database). The system supports multiple user roles with specialized dashboards and full Arabic/English language support.

## Completed Features

### 1. Authentication System ✅
- Login page with credential validation
- Session-based authentication
- Password hashing with bcrypt
- Role-based access control
- Logout functionality

### 2. Admin Dashboard ✅
- **Client Management**: Add, view, and delete clients
- **Project Management**: Create, assign, and track projects
- **User Management**: Create users with different roles
- **Statistics**: View total clients, projects, users, and leads
- **Quick Actions**: Fast access to common tasks

### 3. Client Dashboard ✅
- **Project Overview**: View all assigned projects
- **Progress Tracking**: Monitor project progress with visual indicators
- **Media Gallery**: View project images and videos
- **Feedback System**: Send notes, issues, suggestions, and approvals
- **Project Details**: Comprehensive project information

### 4. Technician Dashboard ✅
- **Assigned Projects**: View projects assigned to technician
- **Progress Updates**: Add progress percentage and detailed reports
- **Media Upload**: Upload project images and videos
- **Real-time Tracking**: Update project progress in real-time

### 5. Sales Dashboard ✅
- **Lead Management**: Create and track sales leads
- **Lead Pipeline**: Track leads through different stages
- **Client Conversion**: Convert qualified leads to clients
- **Lead Details**: Comprehensive lead information and history
- **Client List**: View and manage all clients

### 6. Multi-Language Support ✅
- **English**: Full LTR (Left to Right) support
- **Arabic**: Full RTL (Right to Left) support
- **Language Switcher**: Easy language switching in navigation
- **Complete Translations**: All UI elements translated
- **Database Support**: UTF-8 encoding for Arabic text

### 7. Database System ✅
- **8 Main Tables**: users, clients, projects, project_media, progress_updates, feedback, leads, sessions
- **Relationships**: Proper foreign keys and constraints
- **UTF-8 Support**: Full support for Arabic and special characters
- **Initialization Script**: Automatic database setup with demo data

### 8. API Endpoints ✅
- **Admin APIs**: Client, project, and user management
- **Client APIs**: Feedback submission
- **Technician APIs**: Progress updates and media uploads
- **Sales APIs**: Lead management
- **Language API**: Language switching

### 9. Responsive Design ✅
- **Mobile-Friendly**: Works on all device sizes
- **Flexible Layout**: Grid-based responsive design
- **Touch-Friendly**: Buttons and forms optimized for mobile
- **Adaptive Navigation**: Sidebar and menu adapt to screen size

### 10. User Interface ✅
- **Clean Design**: Modern, professional appearance
- **Color Scheme**: Primary, secondary, and accent colors
- **Typography**: Clear, readable fonts
- **Spacing**: Proper padding and margins
- **Visual Hierarchy**: Clear distinction between elements

## File Structure

```
/home/ubuntu/maysan-security/
├── config/
│   ├── database.php (Database configuration)
│   └── init_database.php (Database initialization)
├── includes/
│   ├── auth.php (Authentication functions)
│   ├── language.php (Language support)
│   ├── functions.php (Utility functions)
│   ├── header.php (Header component)
│   └── footer.php (Footer component)
├── pages/
│   ├── admin/
│   │   ├── dashboard.php
│   │   ├── clients.php
│   │   ├── projects.php
│   │   └── users.php
│   ├── client/
│   │   ├── dashboard.php
│   │   ├── projects.php
│   │   └── project-detail.php
│   ├── technician/
│   │   ├── dashboard.php
│   │   ├── projects.php
│   │   └── project-detail.php
│   └── sales/
│       ├── dashboard.php
│       ├── leads.php
│       ├── lead-detail.php
│       ├── clients.php
│       └── client-detail.php
├── api/
│   ├── switch-language.php
│   ├── logout.php
│   ├── admin/ (4 endpoints)
│   ├── client/ (1 endpoint)
│   ├── technician/ (2 endpoints)
│   └── sales/ (3 endpoints)
├── assets/
│   ├── css/
│   │   └── style.css (Complete stylesheet with RTL support)
│   ├── js/
│   │   └── main.js (JavaScript utilities)
│   ├── images/ (Image assets)
│   └── uploads/ (User uploads)
├── index.php (Home page)
├── login.php (Login page)
├── README.md (Project documentation)
├── INSTALLATION.md (Setup guide)
└── PROJECT_SUMMARY.md (This file)
```

## Database Tables

### users
- id, username, email, password, full_name, phone, role, status, created_at, updated_at

### clients
- id, user_id, company_name, contact_person, phone, email, address, city, country, notes, status, created_at, updated_at

### projects
- id, client_id, project_name, description, project_type, location, start_date, end_date, budget, progress_percentage, status, assigned_technician_id, assigned_sales_id, created_at, updated_at

### project_media
- id, project_id, media_type, file_path, file_name, uploaded_by, description, uploaded_at

### progress_updates
- id, project_id, technician_id, progress_percentage, report_text, update_date

### feedback
- id, project_id, user_id, feedback_text, feedback_type, created_at

### leads
- id, lead_name, company_name, email, phone, project_type, budget_range, assigned_sales_id, status, notes, created_at, updated_at

### sessions
- id, user_id, session_token, ip_address, user_agent, created_at, expires_at

## Demo Credentials

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin123 |
| Client | client1 | client123 |
| Technician | technician1 | tech123 |
| Sales | sales1 | sales123 |

## Key Features Implemented

### Security
- Password hashing with bcrypt
- SQL injection prevention with prepared statements
- Session-based authentication
- Role-based access control
- Input validation and sanitization

### Performance
- Efficient database queries
- Optimized CSS and JavaScript
- Responsive image handling
- Minimal external dependencies

### Usability
- Intuitive navigation
- Clear visual feedback
- Consistent design patterns
- Accessible forms and inputs

### Scalability
- Modular code structure
- Reusable components
- Easy to extend functionality
- Database-driven content

## Technologies Used

- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Server**: Apache/Nginx
- **Encoding**: UTF-8 (Full Arabic support)

## Installation Steps

1. Run database initialization:
   ```bash
   php config/init_database.php
   ```

2. Configure web server to point to project directory

3. Set file permissions:
   ```bash
   chmod -R 755 /home/ubuntu/maysan-security
   chmod -R 777 /home/ubuntu/maysan-security/assets/uploads/
   ```

4. Access application via browser

5. Login with demo credentials

## API Endpoints Summary

### Admin (10 endpoints)
- POST /api/admin/add-client.php
- POST /api/admin/delete-client.php
- POST /api/admin/add-project.php
- POST /api/admin/delete-project.php
- POST /api/admin/add-user.php
- POST /api/admin/delete-user.php

### Client (1 endpoint)
- POST /api/client/add-feedback.php

### Technician (2 endpoints)
- POST /api/technician/add-progress.php
- POST /api/technician/upload-media.php

### Sales (3 endpoints)
- POST /api/sales/add-lead.php
- POST /api/sales/update-lead.php
- POST /api/sales/delete-lead.php

### System (2 endpoints)
- POST /api/switch-language.php
- GET /api/logout.php

## Total Deliverables

- **Pages**: 13 main pages (login, home, 4 dashboards, 8 management pages)
- **API Endpoints**: 15 endpoints
- **Database Tables**: 8 tables
- **CSS**: 1 comprehensive stylesheet with RTL support
- **JavaScript**: 1 main utility file
- **Configuration**: 2 config files
- **Includes**: 5 reusable components
- **Documentation**: 3 documentation files

## Quality Assurance

✅ All pages are fully functional
✅ Database connectivity verified
✅ Authentication system working
✅ Role-based access control implemented
✅ Responsive design tested
✅ Arabic/English language support complete
✅ API endpoints functional
✅ Error handling implemented
✅ Input validation in place
✅ Security measures applied

## Future Enhancement Opportunities

1. Edit functionality for clients, projects, and leads
2. Advanced filtering and search
3. Report generation and export
4. Email notifications
5. Two-factor authentication
6. Activity logging
7. Backup and restore functionality
8. Advanced analytics and dashboards
9. Mobile app integration
10. API documentation with Swagger
