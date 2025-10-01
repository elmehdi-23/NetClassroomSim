<?php
session_start();
$lang = [
  "fr" => [
    "title" => "Simulation Réseau Classe",
    "enter_names" => "Entrez vos noms pour rejoindre (2 à 3 élèves)",
    "choose_avatar" => "Choisissez un avatar",
    "connect" => "Se connecter",
    'name_placeholder1' => 'Élève 1',
    'name_placeholder2' => 'Élève 2',
    'name_placeholder3' => 'Élève 3',
    "confirm_presence" => "Je confirme ma présence",
    "already_joined" => "Vous êtes déjà connecté.",
    "list_students" => "Liste des étudiants connectés",
    "avatar" => "Avatar",
    "names" => "Noms",
    "ip" => "Adresse IP",
    "time" => "Heure",
    "reset_table" => "Reset Table",
    "back" => "Back",
    "welcome" => "Welcome"
  ],
  "en" => [
    "title" => "Classroom Network Simulation",
    "enter_names" => "Enter your names to join (2 to 3 students)",
    "choose_avatar" => "Choose an avatar",
    "connect" => "Connect",
    'name_placeholder1' => 'Student 1',
    'name_placeholder2' => 'Student 2',
    'name_placeholder3' => 'Student 3',
    "confirm_presence" => "I confirm my presence",
    "already_joined" => "You have already joined.",
    "list_students" => "Connected students list",
    "avatar" => "Avatar",
    "names" => "Names",
    "ip" => "IP Address",
    "time" => "Time",
    "reset_table" => "Reset Table",
    "back" => "Back",
    "welcome" => "Welcome"
  ],
  "ar" => [
    "title" => "محاكاة شبكة الفصل",
    "enter_names" => "أدخلوا أسمائكم للانضمام (2 إلى 3 ثلاميد(ة))",
    "choose_avatar" => "اختر صورة رمزية",
    "connect" => "انضم",
    'name_placeholder1' => 'ثلميد(ة) 1',
    'name_placeholder2' => 'ثلميد(ة) 2',
    'name_placeholder3' => 'ثلميد(ة) 3',
    "confirm_presence" => "أؤكد حضوري",
    "already_joined" => "لقد انضممت بالفعل.",
    "list_students" => "قائمة الطلاب المتصلين",
    "avatar" => "الصورة الرمزية",
    "names" => "الأسماء",
    "ip" => "عنوان IP",
    "time" => "الوقت",
    "reset_table" => "إعادة تعيين الجدول",
    "back" => "عودة",
    "welcome" => "مرحباً"
  ]
];

if(isset($_GET['lang'])) $_SESSION['lang'] = $_GET['lang'];
$current = $_SESSION['lang'] ?? 'fr';
if(!isset($lang[$current])) $current = 'fr';
?>