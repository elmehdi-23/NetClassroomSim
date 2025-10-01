# NetClassroomSim v3.2  
📅 Release date: 2025-10-01  

## Overview  
NetClassroomSim is a local classroom network simulation project designed for computer science classes.  
It allows students to connect to a simulated classroom server, register their names, and appear on a shared dashboard (`table.php`).  
This project helps students learn about local networking, IP addresses, and client/server interactions.  

## New Features in v3.2  
- 🌍 **IP Geolocation / Hostname display**  
  - Local subnet IPs (192.168.*, 10.*, 172.16.*, 127.*, ::1) show their **hostname** if available.  
  - Public IPs show **geolocation info** (city, region, country, ISP) using `ip-api.com` (with caching).  

- 🏆 **Leaderboard added**  
  - **Fastest student to connect** (first entry).  
  - **Most connections** (student name appearing most often).  
  - **Most unique avatars** (student using different avatars).  
  - **Most popular avatar overall** (avatar chosen most often).  

- 🌐 **New language: Arabic (ar)**  
  - Full Arabic translations added in `lang.php`.  
  - Language selector updated with 🇺🇸 EN and 🇲🇦 AR buttons.  

- 👥 **Student counter**  
  - `table.php` now displays the **total number of connected students** at the top of the page.  

## File Changes in v3.2  
- `lang.php` → Added Arabic translations (`ar`).  
- `table.php` → Added leaderboard, IP/hostname display, student counter, language buttons.  
- `index.php` → Added Arabic (🇲🇦 AR) language button alongside 🇺🇸 EN and 🇫🇷 FR.  

## Requirements  
- PHP 7.4+ (tested with PHP 8.x).  
- JSON file read/write permissions:  
  - `students.json` (stores student data).  
  - `ipgeo_cache.json` (stores geolocation cache).  
- Internet access (optional) for public IP geolocation.  

## Installation & Usage  
1. Extract the project into your **WampServer/XAMPP/Localhost `www/` folder**.  
2. Start Apache/MySQL.  
3. Access the project from a student machine via browser:  
   - Teacher PC (server): `http://<teacher-ip>/NetClassroomSim/index.php`  
   - Student PCs (clients): same URL, enter names and choose avatar.  
4. Teacher can view all connected students at:  
   - `http://<teacher-ip>/NetClassroomSim/table.php`  

## Resetting Student List  
- Only allowed from the **localhost (127.0.0.1 / ::1)**.  
- Visit: `http://localhost/NetClassroomSim/table.php?reset=1`  

## Notes  
- If `ip-api.com` requests fail (e.g., outgoing HTTP blocked), public IPs will display raw IP only.  
- External geolocation is cached for 24 hours in `ipgeo_cache.json`.  
- This tool is for **local classroom teaching** — not intended for public internet deployment.  

---

👨‍🏫 Created for educational purposes by a computer science teacher.  
