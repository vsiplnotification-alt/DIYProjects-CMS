# DIY Projects CMS

A complete PHP-based Content Management System for displaying free IoT and Embedded Systems tutorials.

## Features

✅ **Public Homepage** - Display projects in responsive boxes
✅ **Tutorial Pages** - Full content with rich text support
✅ **Certificate Registration** - Form with payment ID tracking
✅ **Admin Panel** - Add/Edit projects with TinyMCE editor
✅ **Responsive Design** - Mobile-friendly interface
✅ **Database-driven** - MySQL for data persistence

## Installation Steps

### 1. Upload Files to cPanel

1. Use cPanel File Manager or FTP to upload all files to your domain
2. Place all files in the public_html directory

### 2. Create Database in cPanel

1. Go to cPanel → MySQL Database
2. Create a new database (e.g., `diyproject_cms`)
3. Create a new user (e.g., `diyproject_user`)
4. Assign all privileges to the user
5. Note the database name, username, and password

### 3. Configure Database Connection

1. Edit `config/db.php`
2. Update the following:
   ```php
   $db_user = 'your_cPanel_db_user';
   $db_pass = 'your_cPanel_db_password';
   $db_name = 'your_cPanel_db_name';
   ```

### 4. Create Database Tables

1. Navigate to `http://diyprojects.co.in/config/setup.php`
2. The script will create all necessary tables
3. Default admin credentials:
   - Username: `admin`
   - Password: `admin123`
   - **Change these after first login!**

## File Structure

```
/
├── index.php                 # Homepage
├── tutorial.php              # Tutorial page
├── process-certificate.php   # Certificate registration handler
├── certificate-success.php   # Certificate display
├── logout.php               # Logout handler
├── config/
│   ├── db.php              # Database configuration
│   └── setup.php           # Database setup script
├── admin/
│   ├── login.php           # Admin login page
│   ├── dashboard.php       # Admin dashboard
│   ├── add-project.php     # Add new project
│   ├── edit-project.php    # Edit project
│   ├── delete-project.php  # Delete project
│   └── certificates.php    # Manage certificates
├── css/
│   └── style.css           # Responsive styles
├── js/
│   └── script.js           # JavaScript functions
└── README.md               # This file
```

## Usage

### Adding Projects (Admin Panel)

1. Go to `http://diyprojects.co.in/admin/login.php`
2. Login with admin credentials
3. Click "Add New Project"
4. Fill in:
   - Project Title
   - Short Description
   - Image URL (full URL)
   - Tutorial Content (use rich text editor)
5. Click "Add Project"

### Managing Certificates

1. Go to Admin Dashboard
2. Click "Certificates" to view all registrations
3. See registered users and their payment IDs

### Public Features

- **Homepage**: Browse featured tutorials
- **Tutorial Page**: Read full content
- **Certificate Form**: Register after completing tutorial
- **Print Certificate**: Print proof of completion

## Customization

### Styling
- Edit `css/style.css` for colors and layout
- Change navbar color in `.navbar` class
- Customize buttons in `.btn-*` classes

### Editor
- TinyMCE rich text editor included
- Uses free tier (no API key needed)
- Plugins: lists, links, images

## Security Notes

⚠️ **Before going live:**

1. Change admin password immediately
2. Use strong database passwords
3. Enable SSL/HTTPS on your domain
4. Add form validation on server-side
5. Implement CSRF tokens
6. Use prepared statements for database queries

## Troubleshooting

### Database Connection Error
- Verify credentials in `config/db.php`
- Check if database user has proper privileges
- Ensure MySQL is running in cPanel

### TinyMCE Not Loading
- Check internet connection
- Try clearing browser cache
- The editor requires internet for CDN

### Images Not Displaying
- Ensure image URLs are complete (http:// or https://)
- Check if image host is accessible
- Images should be publicly available

## Support

For domain: diyprojects.co.in

For issues or questions, check:
1. cPanel error logs
2. Browser console (F12)
3. Database credentials

## License

Open source for educational purposes.
