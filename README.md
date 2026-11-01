# 🎓 CampusBeacon

**CampusBeacon** is a streamlined, centralized notice board application designed for academic institutions. It bridges the communication gap between professors and students by providing a professional, high-contrast platform for official announcements and academic discussions.

---

## 🚀 Project Vision
The goal is to replace fragmented communication (emails, chat groups, physical boards) with a single source of truth where:
- **Professors** can publish official notices and supporting documents.
- **Students** can stay updated via a live feed and engage in threaded discussions.
- **Admins** ensure the platform's integrity by managing official professor accounts.

## ✨ Key Features

### 🎓 For Professors
- **Markdown-Powered Editor:** Create rich, formatted notices effortlessly.
- **Secure Attachments:** Upload supporting PDF documents for students to view.
- **Notice Lifecycle Management:** Edit or delete the most recent announcement to ensure accuracy.
- **Engagement Tracking:** Monitor and manage student queries via threaded comments.

### 📖 For Students
- **Live Notice Feed:** A chronological, high-contrast feed of all university announcements.
- **Interactive Q&A:** Clarify doubts using a nested comment system for organized discussions.
- **Instant Document Access:** View and download official PDFs directly from the notice.

### 🛡️ For Admins
- **Account Management:** Create and remove professor accounts to maintain a verified environment.
- **System Overview:** Track total users and notices via a centralized Admin Dashboard.

## 🛠️ Tech Stack

| Layer | Technology | Description |
| :--- | :--- | :--- |
| **Backend** | `PHP (Procedural)` | Core logic and server-side processing. |
| **Database** | `MySQL (MySQLi)` | Prepared statements for secure data persistence. |
| **Frontend** | `Bootstrap 5` | Responsive, modern UI based on the "Blowfish" aesthetic. |
| **Parsing** | `Parsedown / Marked` | Rendering Markdown content into clean HTML. |
| **Styling** | `Custom CSS` | High-contrast, minimalist dark theme with interactive elements. |

## ⚙️ Installation & Setup

### 1. Clone the Repository
```bash
git clone git@github.com:titas-ops/CampusBeacon.git
```

### 2. Environment Setup
- Move the `CampusBeacon` folder into your local server's root directory (e.g., `/opt/lampp/htdocs/` or `C:/xampp/htdocs/`).
- Start your **XAMPP/WAMP** (Apache and MySQL) servers.

### 3. Database Configuration
- Import the provided SQL schema into your MySQL database.
- Ensure the database connection settings in `database/db_connect.php` match your local environment.

### 4. Access the App
Open your browser and navigate to:
`http://localhost/CampusBeacon/`

---
*Designed for efficiency, security, and a minimalist user experience.*
