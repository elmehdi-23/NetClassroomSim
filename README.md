# NetClassroomSim v3.1.1 
Classroom Local Network Activity

## 📖 Overview
**NetClassroomSim** is a classroom simulation project designed for teaching **local networking** and **client-server interaction** in a fun and visual way.  
Students connect to a local classroom server, submit their names and choose an avatar. Submissions are displayed in real-time on the teacher’s screen (`table.php`) to demonstrate how a server collects and shares data.

This project replaces MySQL with a simple **JSON file** for storage, making it lightweight and easy to deploy.

## 🚀 Features
- 🌍 **Multilingual support**: English (EN) and French (FR).
- 🎭 **Avatar selection**: 5 SVG avatars stored in `/avatars/`.
- 🕐 **Submission log**: Name, PC IP, avatar, and submission time are saved.
- 🔄 **One-time submission per PC** (students cannot submit multiple times unless reset).
- 👨‍🏫 **Teacher-only reset**:
  - A **Reset Table** button is visible only on `localhost / 127.0.0.1 / ::1`.
  - Clicking it clears all data (`students.json`).
- 📡 **Live Data Refresh**:
  - `table.php` updates automatically every few seconds via AJAX.
- 🔁 **Redo option**: Students can redo their submission if already connected.
- ✅ **Special Student #3 Rule**:
  - Student 3 must check a confirmation box ("Exact or not") before submitting.
- 📂 **Lightweight storage**: Uses `students.json` instead of a database.

---

## 📂 Project Structure
NetClassroomSim-v3.1.1/
│
├── index.php # Student entry page
├── table.php # Teacher's data table (live update + reset)
├── save.php # Handles saving submissions to JSON
├── lang.php # Language definitions (EN/FR)
├── students.json # Data storage (auto-created/reset)
│
├── avatars/ # Avatar choices

## 🔑 Teacher Controls
- Only **localhost/127.0.0.1/::1** can see and use the **Reset Table** button.
- Reset clears `students.json`.

## 🆕 What’s New in v3.1.1
- Added **auto-refresh** for `table.php` (live data updates).
- Added **reset button** (teacher only).
- Added **Back** button support (multilingual).
- Improved **lang.php** with `"reset_table"` and `"back"`.
- Enforced **Student #3 confirmation check** before submitting.

## 🖥️ How to Run
1. Copy the project folder into your local server (e.g., `htdocs/NetClassroomSim-v3.1.1` for XAMPP).
2. Start **Apache** (no MySQL required).
3. Students open their browser and visit:

## 📚 Educational Goal
This project is meant to **simulate client-server communication** in a classroom:
- Students act as clients.
- Teacher’s screen (`table.php`) acts as the server’s data visualization.
- Helps learners understand networking concepts in a **hands-on, interactive** way.

## 📝 License
This project is free for educational use.  
Created with ❤️ for classroom learning.