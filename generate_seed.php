<?php
$password = 'password123';
$hashed = password_hash($password, PASSWORD_BCRYPT);

echo "-- Seed Data for ExamPro\n\n";

// Generate Teachers
echo "-- 5 Teachers\n";
$teachers = ['Dr. Alan Turing', 'Prof. Marie Curie', 'Dr. Albert Einstein', 'Prof. Isaac Newton', 'Dr. Ada Lovelace'];
$teacher_ids = [];
foreach ($teachers as $i => $name) {
    $email = 'teacher' . ($i+1) . '@example.com';
    echo "INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`) VALUES (" . ($i+2) . ", '$name', '$email', '$hashed', 'teacher', 'active');\n";
    $teacher_ids[] = $i+2;
}

// Generate Students
echo "\n-- 20 Students\n";
$students = [
    'Alice Smith', 'Bob Johnson', 'Charlie Brown', 'Diana Prince', 'Evan Wright',
    'Fiona Gallagher', 'George Miller', 'Hannah Abbott', 'Ian Malcolm', 'Julia Roberts',
    'Kevin Hart', 'Luna Lovegood', 'Michael Scott', 'Nina Dobrev', 'Oscar Isaac',
    'Penelope Cruz', 'Quentin Tarantino', 'Rachel Green', 'Steve Rogers', 'Tony Stark'
];
$student_ids = [];
foreach ($students as $i => $name) {
    $email = 'student' . ($i+1) . '@example.com';
    echo "INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`) VALUES (" . ($i+7) . ", '$name', '$email', '$hashed', 'student', 'active');\n";
    $student_ids[] = $i+7;
}

// Generate Subjects
echo "\n-- 5 Subjects\n";
$subjects = ['Computer Science', 'Physics', 'Mathematics', 'Chemistry', 'History'];
foreach ($subjects as $i => $name) {
    $tid = $teacher_ids[$i];
    echo "INSERT INTO `subjects` (`id`, `name`, `assigned_teacher_id`) VALUES (" . ($i+1) . ", '$name', $tid);\n";
}

// Generate Exams
echo "\n-- 5 Exams\n";
echo "INSERT INTO `exams` (`id`, `teacher_id`, `subject_id`, `title`, `total_questions`, `time_limit`, `negative_marking`, `status`) VALUES\n";
echo "(1, 2, 1, 'Intro to Programming', 5, 30, 1, 'active'),\n";
echo "(2, 3, 2, 'Quantum Mechanics Basics', 5, 45, 1, 'active'),\n";
echo "(3, 4, 3, 'Calculus Midterm', 5, 60, 0, 'active'),\n";
echo "(4, 5, 4, 'Organic Chemistry Quiz', 5, 20, 1, 'active'),\n";
echo "(5, 6, 5, 'World War II History', 5, 30, 0, 'active');\n";

// Generate Questions
echo "\n-- Questions for Exam 1\n";
echo "INSERT INTO `questions` (`id`, `subject_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `difficulty`) VALUES\n";
echo "(1, 1, 'What does HTML stand for?', 'Hyper Text Markup Language', 'High Text Markup Language', 'Hyper Tabular Markup Language', 'None of these', 'A', 'easy'),\n";
echo "(2, 1, 'Which language is used for styling web pages?', 'HTML', 'JQuery', 'CSS', 'XML', 'C', 'easy'),\n";
echo "(3, 1, 'What is the output of 2 + 2 in JavaScript?', '22', '4', 'Error', 'Undefined', 'B', 'easy'),\n";
echo "(4, 1, 'Which is not a programming language?', 'Python', 'Java', 'HTML', 'C++', 'C', 'medium'),\n";
echo "(5, 1, 'What does PHP stand for?', 'Personal Home Page', 'Private Home Page', 'PHP: Hypertext Preprocessor', 'Both A and C', 'D', 'medium');\n";
?>
