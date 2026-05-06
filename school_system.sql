-- Drop existing database and create fresh one
DROP DATABASE IF EXISTS school_system;
CREATE DATABASE school_system;
USE school_system;

-- Users table with unique email constraint
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'teacher', 'admin') NOT NULL,
    gender ENUM('Male', 'Female') DEFAULT NULL,
    district VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Fixed classes: Form 1, 2 (Junior), Form 3, 4 (Senior)
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(50) NOT NULL,
    class_level ENUM('junior', 'senior') NOT NULL
);

-- Insert fixed classes
INSERT INTO classes (class_name, class_level) VALUES
('Form 1', 'junior'),
('Form 2', 'junior'),
('Form 3', 'senior'),
('Form 4', 'senior');

-- Subjects table
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL
);

-- Student-Class assignment (one student, one class at a time)
CREATE TABLE student_classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    assigned_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    UNIQUE(student_id)  -- Each student can only be in ONE class
);

-- Marks table with auto-grade
CREATE TABLE marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    marks INT NOT NULL,
    grade VARCHAR(5),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- Assignments table
CREATE TABLE assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_id INT NOT NULL,
    class_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
);

-- Attendance table
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    date DATE NOT NULL,
    status ENUM('present', 'absent', 'late') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create default admin account
INSERT INTO users (name, email, password, role) VALUES 
('System Administrator', 'admin@solstice.com', '$2y$10$YourHashedPasswordHere', 'admin');

-- ============================================
-- INSERT TEACHERS (13 records with Malawian names)
-- Password hash for 'tea123' is: $2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi
-- ============================================

INSERT INTO users (name, email, password, role, gender, district) VALUES
('Dr. James Banda', 'teacher001@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Male', 'Lilongwe'),
('Mrs. Grace Phiri', 'teacher002@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Female', 'Blantyre'),
('Mr. Peter Mwale', 'teacher003@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Male', 'Mzuzu'),
('Ms. Mary Nyirenda', 'teacher004@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Female', 'Zomba'),
('Prof. John Chirwa', 'teacher005@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Male', 'Lilongwe'),
('Mrs. Esther Kamanga', 'teacher006@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Female', 'Machinga'),
('Mr. Charles Manda', 'teacher007@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Male', 'Dedza'),
('Ms. Ruth Chisale', 'teacher008@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Female', 'Salima'),
('Dr. Joseph Nkhoma', 'teacher009@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Male', 'Thyolo'),
('Mrs. Sarah Banda', 'teacher010@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Female', 'Mulanje'),
('Mr. Daniel Mvula', 'teacher011@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Male', 'Kasungu'),
('Ms. Patricia Likoswe', 'teacher012@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Female', 'Ntcheu'),
('Prof. William Chikhwaza', 'teacher013@solstice.com', '$2y$10$OgTPPBj3.VwhzXjpd5olCOSrYZrX2cjBjzozEJs0OV76RqQM.wUpi', 'teacher', 'Male', 'Mangochi');

-- Use the database
USE school_system;

-- ============================================
-- INSERT STUDENTS (120 records with Malawian names)
-- ============================================
-- Password hash for 'stu123' is: $2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi

INSERT INTO users (name, email, password, role, gender, district) VALUES
('Chikondi Banda', 'student001@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Lilongwe'),
('Mary Phiri', 'student002@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Blantyre'),
('John Mwale', 'student003@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mzuzu'),
('Grace Chimwemwe', 'student004@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Zomba'),
('Peter Kamanga', 'student005@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Machinga'),
('Esther Nyirenda', 'student006@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Salima'),
('Joseph Chisale', 'student007@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Dedza'),
('Martha Banda', 'student008@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Ntcheu'),
('David Mvula', 'student009@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mulanje'),
('Ruth Kaliati', 'student010@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Thyolo');

-- Students 011-020
INSERT INTO users (name, email, password, role, gender, district) VALUES
('Andrew Nkhoma', 'student011@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Karonga'),
('Sarah Chisale', 'student012@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Rumphi'),
('Patrick Mhone', 'student013@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Nkhata Bay'),
('Linda Zgambo', 'student014@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Kasungu'),
('James Kadzakumanja', 'student015@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Ntchisi'),
('Flora Chilima', 'student016@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Dowa'),
('George Mwase', 'student017@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Nkhotakota'),
('Rose Kachingwe', 'student018@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Balaka'),
('Charles Likoswe', 'student019@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mangochi'),
('Margaret Chikhwaza', 'student020@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Mchinji');

-- Students 021-030
INSERT INTO users (name, email, password, role, gender, district) VALUES
('Francis Kadewere', 'student021@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Chitipa'),
('Veronica Chavula', 'student022@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Chikwawa'),
('Noah Chisale', 'student023@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Nsanje'),
('Patricia Mwale', 'student024@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Phalombe'),
('Samuel Chirwa', 'student025@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mwanza'),
('Mercy Kamwendo', 'student026@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Chiradzulu'),
('Daniel Kaunda', 'student027@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Lilongwe'),
('Miriam Nankhuni', 'student028@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Blantyre'),
('Gerald Mbewe', 'student029@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mzuzu'),
('Beatrice Banda', 'student030@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Zomba');

-- Students 031-040
INSERT INTO users (name, email, password, role, gender, district) VALUES
('Isaac Jere', 'student031@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Machinga'),
('Joyce Malata', 'student032@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Salima'),
('Victor Nyalugwe', 'student033@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Dedza'),
('Alice Banda', 'student034@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Ntcheu'),
('Emmanuel Phiri', 'student035@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mulanje'),
('Catherine Kazembe', 'student036@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Thyolo'),
('Timothy Manda', 'student037@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Karonga'),
('Lucy Chipeta', 'student038@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Rumphi'),
('Solomon Changaya', 'student039@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Nkhata Bay'),
('Agnes Mtika', 'student040@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Kasungu');

-- Students 041-050
INSERT INTO users (name, email, password, role, gender, district) VALUES
('Rabson Mwale', 'student041@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Ntchisi'),
('Janet Phoya', 'student042@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Dowa'),
('Benard Chauma', 'student043@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Nkhotakota'),
('Martha Luhanga', 'student044@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Balaka'),
('Steve Mwakhwawa', 'student045@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mangochi'),
('Hellen Nthala', 'student046@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Mchinji'),
('Godfrey Mfune', 'student047@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Chitipa'),
('Edna Kachingwe', 'student048@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Chikwawa'),
('Felix Lyson', 'student049@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Nsanje'),
('Irene Banda', 'student050@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Phalombe');

-- Students 051-060
INSERT INTO users (name, email, password, role, gender, district) VALUES
('Martin Malunga', 'student051@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mwanza'),
('Grace Kawonga', 'student052@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Chiradzulu'),
('Henry Mpundu', 'student053@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Lilongwe'),
('Loveness Jere', 'student054@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Blantyre'),
('Gift Chisale', 'student055@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mzuzu'),
('Rosemary Moyo', 'student056@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Zomba'),
('Steven Banda', 'student057@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Machinga'),
('Regina Mtawali', 'student058@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Salima'),
('Owen Thole', 'student059@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Dedza'),
('Maggie Tsoka', 'student060@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Ntcheu');

-- Students 061-070
INSERT INTO users (name, email, password, role, gender, district) VALUES
('Witness Mfune', 'student061@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mulanje'),
('Eunice Luhanga', 'student062@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Thyolo'),
('Christopher Maseya', 'student063@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Karonga'),
('Carol Mwale', 'student064@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Rumphi'),
('Byson Jumbe', 'student065@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Nkhata Bay'),
('Olivia Phiri', 'student066@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Kasungu'),
('Edwin Manda', 'student067@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Ntchisi'),
('Sharon Chirwa', 'student068@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Dowa'),
('Moses Nkhoma', 'student069@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Nkhotakota'),
('Rachel Fatch', 'student070@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Balaka');

-- Students 071-080
INSERT INTO users (name, email, password, role, gender, district) VALUES
('Kennedy Chisala', 'student071@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mangochi'),
('Dorothy Kachingwe', 'student072@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Mchinji'),
('Micheal Mtika', 'student073@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Chitipa'),
('Joyce Nyondo', 'student074@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Chikwawa'),
('Bernard Mwakhwawa', 'student075@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Nsanje'),
('Grace Banda', 'student076@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Phalombe'),
('Innocent Phiri', 'student077@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mwanza'),
('Thandiwe Mbewe', 'student078@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Chiradzulu'),
('Richard Kadewere', 'student079@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Lilongwe'),
('Rebecca Chavula', 'student080@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Blantyre');

-- Students 081-090
INSERT INTO users (name, email, password, role, gender, district) VALUES
('Zachariah Mvula', 'student081@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mzuzu'),
('Memory Kadzakumanja', 'student082@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Zomba'),
('Yohane Kachingwe', 'student083@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Machinga'),
('Priscilla Likoswe', 'student084@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Salima'),
('Aaron Mwale', 'student085@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Dedza'),
('Eliza Chikhwaza', 'student086@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Ntcheu'),
('Fredrick Banda', 'student087@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mulanje'),
('Gloria Chisale', 'student088@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Thyolo'),
('Humphrey Nyirenda', 'student089@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Karonga'),
('Miriam Mzembe', 'student090@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Rumphi');

-- Students 091-100
INSERT INTO users (name, email, password, role, gender, district) VALUES
('Smart Manda', 'student091@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Nkhata Bay'),
('Florence Malunga', 'student092@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Kasungu'),
('Rodrick Lungu', 'student093@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Ntchisi'),
('Cecilia Kawonga', 'student094@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Dowa'),
('Brian Mwase', 'student095@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Nkhotakota'),
('Linda Thole', 'student096@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Balaka'),
('Alfred Phiri', 'student097@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mangochi'),
('Ethel Banda', 'student098@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Mchinji'),
('Nelson Chirwa', 'student099@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Chitipa'),
('Ruth Nankhuni', 'student100@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Chikwawa');

-- Students 101-110
INSERT INTO users (name, email, password, role, gender, district) VALUES
('Samson Mtika', 'student101@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Nsanje'),
('Charity Nyirenda', 'student102@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Phalombe'),
('Patrick Chisale', 'student103@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mwanza'),
('Mercy Mwale', 'student104@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Chiradzulu'),
('Davis Kamanga', 'student105@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Lilongwe'),
('Dorcas Banda', 'student106@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Blantyre'),
('Lameck Jere', 'student107@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Mzuzu'),
('Violet Phiri', 'student108@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Zomba'),
('Elias Mwase', 'student109@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Machinga'),
('Naomi Kachingwe', 'student110@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Salima');

-- Students 111-120
INSERT INTO users (name, email, password, role, gender, district) VALUES
('Clement Manda', 'student111@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Male', 'Dedza'),
('Ester Chavula', 'student112@solstice.com', '$2y$10$4/XZowUSUTNSgnKDNCk6VeLgtuDwyI8J0O1pd14Q5nC4fl4gSAgyi', 'student', 'Female', 'Ntcheu');

-- ============================================
-- INSERT SUBJECTS (13 secondary school subjects)
-- ============================================

INSERT INTO subjects (subject_name) VALUES
('Mathematics'),
('English Language'),
('Biology'),
('Chemistry'),
('Physics'),
('History'),
('Geography'),
('Civics and Moral Education'),
('Computer Science'),
('Economics'),
('Accounting'),
('Chichewa Language'),
('Physical Education');

SELECT 
    u.id AS teacher_id,
    u.name AS teacher_name,
    u.email,
    s.id AS subject_id,
    s.subject_name AS assigned_subject
FROM users u
CROSS JOIN subjects s
WHERE u.role = 'teacher' 
AND u.id = s.id
ORDER BY u.id;