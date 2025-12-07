<?php
/**
 * Language and Localization Functions
 * Maysan Al-Riyidh CCTV Security Systems
 */

// Set default language
if (!isset($_SESSION['language'])) {
    $_SESSION['language'] = 'en'; // Default to English
}

// Language strings
$translations = [
    'en' => [
        'home' => 'Home',
        'dashboard' => 'Dashboard',
        'projects' => 'Projects',
        'clients' => 'Clients',
        'technicians' => 'Technicians',
        'sales' => 'Sales',
        'leads' => 'Leads',
        'profile' => 'Profile',
        'settings' => 'Settings',
        'logout' => 'Logout',
        'login' => 'Login',
        
        // Common
        'company_name' => 'Maysan IT',
        'company_N' => 'Company Name',
        'company_slogan' => 'CCTV & Security Systems',
        'add' => 'Add',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'submit' => 'Submit',
        'search' => 'Search',
        'filter' => 'Filter',
        'export' => 'Export',
        'import' => 'Import',
        'back' => 'Back',
        'next' => 'Next',
        'previous' => 'Previous',
        'loading' => 'Loading...',
        'success' => 'Success',
        'error' => 'Error',
        'warning' => 'Warning',
        'info' => 'Information',
        'confirm' => 'Confirm',
        'yes' => 'Yes',
        'no' => 'No',
        
        // Form Labels
        'username' => 'Username',
        'email' => 'Email',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'full_name' => 'Full Name',
        'phone' => 'Phone',
        'address' => 'Address',
        'city' => 'City',
        'country' => 'Country',
        'role' => 'Role',
        'status' => 'Status',
        'active' => 'Active',
        'inactive' => 'Inactive',
        
        // Admin Dashboard
        'admin_dashboard' => 'Admin Dashboard',
        'manage_clients' => 'Manage Clients',
        'manage_projects' => 'Manage Projects',
        'manage_users' => 'Manage Users',
        'manage_technicians' => 'Manage Technicians',
        'manage_sales' => 'Manage Sales',
        'total_clients' => 'Total Clients',
        'total_projects' => 'Total Projects',
        'total_users' => 'Total Users',
        'active_projects' => 'Active Projects',
        'completed_projects' => 'Completed Projects',
        
        // Client Dashboard
        'client_dashboard' => 'Client Dashboard',
        'my_projects' => 'My Projects',
        'project_details' => 'Project Details',
        'project_progress' => 'Project Progress',
        'project_media' => 'Project Media',
        'send_feedback' => 'Send Feedback',
        'view_updates' => 'View Updates',
        
        // Technician Dashboard
        'technician_dashboard' => 'Technician Dashboard',
        'assigned_projects' => 'Assigned Projects',
        'upload_update' => 'Upload Update',
        'add_progress' => 'Add Progress',
        'progress_percentage' => 'Progress Percentage',
        'report' => 'Report',
        'upload_media' => 'Upload Media',
        
        // Sales Dashboard
        'sales_dashboard' => 'Sales Dashboard',
        'manage_leads' => 'Manage Leads',
        'lead_status' => 'Lead Status',
        'convert_to_client' => 'Convert to Client',
        'lead_details' => 'Lead Details',
        
        // Project Fields
        'project_name' => 'Project Name',
        'project_type' => 'Project Type',
        'description' => 'Description',
        'location' => 'Location',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'budget' => 'Budget',
        'progress' => 'Progress',
        'assigned_technician' => 'Assigned Technician',
        'assigned_sales' => 'Assigned Sales',
        
        // Messages
        'welcome' => 'Welcome',
        'login_success' => 'Login successful',
        'logout_success' => 'Logout successful',
        'record_created' => 'Record created successfully',
        'record_updated' => 'Record updated successfully',
        'record_deleted' => 'Record deleted successfully',
        'invalid_credentials' => 'Invalid username or password',
        'unauthorized' => 'You are not authorized to access this page',
        'required_field' => 'This field is required',
        'invalid_email' => 'Invalid email address',
        'password_mismatch' => 'Passwords do not match',
        'file_uploaded' => 'File uploaded successfully',
        'delete_confirm' => 'Are you sure you want to delete this record?',
        
        // Months
        'january' => 'January',
        'february' => 'February',
        'march' => 'March',
        'april' => 'April',
        'may' => 'May',
        'june' => 'June',
        'july' => 'July',
        'august' => 'August',
        'september' => 'September',
        'october' => 'October',
        'november' => 'November',
        'december' => 'December',
        'language' => 'Language',
        'quick_actions' => 'Quick Actions',
        'add_client' => 'Add Client',
        'add_project' => 'Add Project',
        'add_user' => 'Add User',
        'add_lead' => 'Add Lead',
        'view_all' => 'View All',
        'view_details' => 'View Details',
        'view' => 'View',
        'recent_projects' => 'Recent Projects',
        'recent_leads' => 'Recent Leads',
        'no_projects_found' => 'No projects found',
        'no_clients_found' => 'No clients found',
        'no_users_found' => 'No users found',
        'no_leads_found' => 'No leads found',
        'no_updates' => 'No updates yet',
        'no_media' => 'No media uploaded',
        'no_feedback' => 'No feedback yet',
        'contact_person' => 'Contact Person',
        'company' => 'Company',
        'actions' => 'Actions',
        'lead_name' => 'Lead Name',
        'budget_range' => 'Budget Range',
        'new_leads' => 'New Leads',
        'qualified' => 'Qualified',
        'won' => 'Won',
        'lost' => 'Lost',
        'new' => 'New',
        'contacted' => 'Contacted',
        'proposal_sent' => 'Proposal Sent',
        'negotiation' => 'Negotiation',
        'in_progress' => 'In Progress',
        'on_hold' => 'On Hold',
        'planning' => 'Planning',
        'unassigned' => 'Unassigned',
        'select_client' => 'Select Client',
        'image' => 'Image',
        'video' => 'Video',
        'file' => 'File',
        'upload' => 'Upload',
        'message' => 'Message',
        'feedback_type' => 'Feedback Type',
        'send' => 'Send',
        'edit_lead' => 'Edit Lead',
        'client_details' => 'Client Details',
        'projects' => 'Projects',
        'created' => 'Created',
        'updated' => 'Updated',
        'project_management' => 'Project Management',
        'manage_projects_desc' => 'Efficiently manage all your CCTV and security projects in one place',
        'client_management' => 'Client Management',
        'manage_clients_desc' => 'Keep track of all your clients and their information',
        'progress_tracking' => 'Progress Tracking',
        'track_progress_desc' => 'Monitor project progress with real-time updates and media',
        'lead_management' => 'Lead Management',
        'manage_leads_desc' => 'Convert leads to clients with our sales pipeline tools',
        'secure_access' => 'Secure Access',
        'secure_access_desc' => 'Role-based access control for different user types',
        'multilingual' => 'Multilingual Support',
        'multilingual_desc' => 'Full support for Arabic and English with RTL layout',
    ],
    'ar' => [
        'home' => 'الرئيسية',
        'dashboard' => 'لوحة التحكم',
        'projects' => 'المشاريع',
        'clients' => 'العملاء',
        'technicians' => 'الفنيون',
        'sales' => 'المبيعات',
        'leads' => 'العملاء المحتملين',
        'profile' => 'الملف الشخصي',
        'settings' => 'الإعدادات',
        'logout' => 'تسجيل الخروج',
        'login' => 'تسجيل الدخول',
        
        // Common
        'company_name' => 'ميسان الرياض',
        'company_N' => 'اسم الشركة',
        'company_slogan' => 'أنظمة المراقبة والأمان',
        'add' => 'إضافة',
        'edit' => 'تعديل',
        'delete' => 'حذف',
        'save' => 'حفظ',
        'cancel' => 'إلغاء',
        'close' => 'إغلاق',
        'submit' => 'إرسال',
        'search' => 'بحث',
        'filter' => 'تصفية',
        'export' => 'تصدير',
        'import' => 'استيراد',
        'back' => 'رجوع',
        'next' => 'التالي',
        'previous' => 'السابق',
        'loading' => 'جاري التحميل...',
        'success' => 'نجاح',
        'error' => 'خطأ',
        'warning' => 'تحذير',
        'info' => 'معلومات',
        'confirm' => 'تأكيد',
        'yes' => 'نعم',
        'no' => 'لا',
        
        // Form Labels
        'username' => 'اسم المستخدم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'confirm_password' => 'تأكيد كلمة المرور',
        'full_name' => 'الاسم الكامل',
        'phone' => 'الهاتف',
        'address' => 'العنوان',
        'city' => 'المدينة',
        'country' => 'الدولة',
        'role' => 'الدور',
        'status' => 'الحالة',
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        
        // Admin Dashboard
        'admin_dashboard' => 'لوحة تحكم المسؤول',
        'manage_clients' => 'إدارة العملاء',
        'manage_projects' => 'إدارة المشاريع',
        'manage_users' => 'إدارة المستخدمين',
        'manage_technicians' => 'إدارة الفنيين',
        'manage_sales' => 'إدارة المبيعات',
        'total_clients' => 'إجمالي العملاء',
        'total_projects' => 'إجمالي المشاريع',
        'total_users' => 'إجمالي المستخدمين',
        'active_projects' => 'المشاريع النشطة',
        'completed_projects' => 'المشاريع المكتملة',
        
        // Client Dashboard
        'client_dashboard' => 'لوحة تحكم العميل',
        'my_projects' => 'مشاريعي',
        'project_details' => 'تفاصيل المشروع',
        'project_progress' => 'تقدم المشروع',
        'project_media' => 'وسائط المشروع',
        'send_feedback' => 'إرسال ملاحظات',
        'view_updates' => 'عرض التحديثات',
        
        // Technician Dashboard
        'technician_dashboard' => 'لوحة تحكم الفني',
        'assigned_projects' => 'المشاريع المسندة',
        'upload_update' => 'تحميل تحديث',
        'add_progress' => 'إضافة تقدم',
        'progress_percentage' => 'نسبة التقدم',
        'report' => 'التقرير',
        'upload_media' => 'تحميل الوسائط',
        
        // Sales Dashboard
        'sales_dashboard' => 'لوحة تحكم المبيعات',
        'manage_leads' => 'إدارة العملاء المحتملين',
        'lead_status' => 'حالة العميل المحتمل',
        'convert_to_client' => 'تحويل إلى عميل',
        'lead_details' => 'تفاصيل العميل المحتمل',
        
        // Project Fields
        'project_name' => 'اسم المشروع',
        'project_type' => 'نوع المشروع',
        'description' => 'الوصف',
        'location' => 'الموقع',
        'start_date' => 'تاريخ البدء',
        'end_date' => 'تاريخ الانتهاء',
        'budget' => 'الميزانية',
        'progress' => 'التقدم',
        'assigned_technician' => 'الفني المسند',
        'assigned_sales' => 'موظف المبيعات المسند',
        
        // Messages
        'welcome' => 'مرحبا',
        'login_success' => 'تم تسجيل الدخول بنجاح',
        'logout_success' => 'تم تسجيل الخروج بنجاح',
        'record_created' => 'تم إنشاء السجل بنجاح',
        'record_updated' => 'تم تحديث السجل بنجاح',
        'record_deleted' => 'تم حذف السجل بنجاح',
        'invalid_credentials' => 'اسم المستخدم أو كلمة المرور غير صحيحة',
        'unauthorized' => 'أنت غير مصرح بالوصول إلى هذه الصفحة',
        'required_field' => 'هذا الحقل مطلوب',
        'invalid_email' => 'عنوان البريد الإلكتروني غير صحيح',
        'password_mismatch' => 'كلمات المرور غير متطابقة',
        'file_uploaded' => 'تم تحميل الملف بنجاح',
        'delete_confirm' => 'هل أنت متأكد من رغبتك في حذف هذا السجل؟',
        
        // Months
        'january' => 'يناير',
        'february' => 'فبراير',
        'march' => 'مارس',
        'april' => 'أبريل',
        'may' => 'مايو',
        'june' => 'يونيو',
        'july' => 'يوليو',
        'august' => 'أغسطس',
        'september' => 'سبتمبر',
        'october' => 'أكتوبر',
        'november' => 'نوفمبر',
        'december' => 'ديسمبر',
        'language' => 'اللغة',
        'quick_actions' => 'الإجراءات السريعة',
        'add_client' => 'إضافة عميل',
        'add_project' => 'إضافة مشروع',
        'add_user' => 'إضافة مستخدم',
        'add_lead' => 'إضافة عميل محتمل',
        'view_all' => 'عرض الكل',
        'view_details' => 'عرض التفاصيل',
        'view' => 'عرض',
        'recent_projects' => 'المشاريع الأخيرة',
        'recent_leads' => 'العملاء المحتملين الأخيرين',
        'no_projects_found' => 'لم يتم العثور على مشاريع',
        'no_clients_found' => 'لم يتم العثور على عملاء',
        'no_users_found' => 'لم يتم العثور على مستخدمين',
        'no_leads_found' => 'لم يتم العثور على عملاء محتملين',
        'no_updates' => 'لا توجد تحديثات حتى الآن',
        'no_media' => 'لم يتم تحميل وسائط',
        'no_feedback' => 'لا توجد ملاحظات حتى الآن',
        'contact_person' => 'جهة الاتصال',
        'company' => 'الشركة',
        'actions' => 'الإجراءات',
        'lead_name' => 'اسم العميل المحتمل',
        'budget_range' => 'نطاق الميزانية',
        'new_leads' => 'عملاء محتملين جدد',
        'qualified' => 'مؤهل',
        'won' => 'فاز',
        'lost' => 'خسر',
        'new' => 'جديد',
        'contacted' => 'تم الاتصال',
        'proposal_sent' => 'تم إرسال العرض',
        'negotiation' => 'التفاوض',
        'in_progress' => 'جاري التنفيذ',
        'on_hold' => 'معلق',
        'planning' => 'التخطيط',
        'unassigned' => 'غير مسند',
        'select_client' => 'اختر عميل',
        'image' => 'صورة',
        'video' => 'فيديو',
        'file' => 'ملف',
        'upload' => 'تحميل',
        'message' => 'الرسالة',
        'feedback_type' => 'نوع الملاحظات',
        'send' => 'إرسال',
        'edit_lead' => 'تعديل العميل المحتمل',
        'client_details' => 'تفاصيل العميل',
        'projects' => 'المشاريع',
        'created' => 'تم الإنشاء',
        'updated' => 'تم التحديث',
        'project_management' => 'إدارة المشاريع',
        'manage_projects_desc' => 'إدارة جميع مشاريع المراقبة والأمان الخاصة بك في مكان واحد',
        'client_management' => 'إدارة العملاء',
        'manage_clients_desc' => 'تتبع جميع عملائك ومعلوماتهم',
        'progress_tracking' => 'تتبع التقدم',
        'track_progress_desc' => 'مراقبة تقدم المشروع مع التحديثات والوسائط في الوقت الفعلي',
        'lead_management' => 'إدارة العملاء المحتملين',
        'manage_leads_desc' => 'تحويل العملاء المحتملين إلى عملاء باستخدام أدوات خط أنابيب المبيعات',
        'secure_access' => 'الوصول الآمن',
        'secure_access_desc' => 'التحكم في الوصول بناءً على الأدوار لأنواع مستخدمين مختلفة',
        'multilingual' => 'دعم متعدد اللغات',
        'multilingual_desc' => 'دعم كامل للعربية والإنجليزية مع تخطيط RTL',
    ]
];

/**
 * Get translation string
 */
function t($key) {
    global $translations;
    $lang = $_SESSION['language'] ?? 'en';
    return $translations[$lang][$key] ?? $key;
}

/**
 * Set language
 */
function setLanguage($lang) {
    if (in_array($lang, ['en', 'ar'])) {
        $_SESSION['language'] = $lang;
    }
}

/**
 * Get current language
 */
function getCurrentLanguage() {
    return $_SESSION['language'] ?? 'en';
}

/**
 * Get language direction (RTL for Arabic, LTR for English)
 */
function getLanguageDirection() {
    return getCurrentLanguage() === 'ar' ? 'rtl' : 'ltr';
}

/**
 * Get language class for HTML
 */
function getLanguageClass() {
    return getCurrentLanguage() === 'ar' ? 'ar' : 'en';
}

?>
