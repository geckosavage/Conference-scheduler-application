BEGIN TRANSACTION;

DELETE FROM session_speakers;
DELETE FROM registrations;
DELETE FROM session;
DELETE FROM speaker;
DELETE FROM room;
DELETE FROM conference;
DELETE FROM users;

INSERT INTO conference (id, title, description, venue, start_date, end_date, status) VALUES
(1, 'InnovateEd Asia 2026', 'A two-day education technology conference focused on AI-assisted teaching, digital assessment, and student engagement strategies for modern classrooms.', 'Saigon Innovation Center, Ho Chi Minh City', '2026-05-15', '2026-05-16', 'published'),
(2, 'Tech Career Launch Summit', 'An industry-focused summit helping students prepare portfolios, internships, and entry-level software engineering careers.', 'Convention Center, Da Nang', '2026-06-20', '2026-06-21', 'published'),
(3, 'Future Builders Developer Conference', 'A software conference dedicated to backend systems, cloud-native engineering, and practical engineering leadership for young developers.', 'Riverside Expo Hall, Hanoi', '2026-07-10', '2026-07-12', 'published');

INSERT INTO room (id, name, location, capacity) VALUES
(1, 'Orchid Room', 'Floor 1', 120),
(2, 'Lotus Lab', 'Floor 2', 60),
(3, 'Main Auditorium', 'Ground Floor', 250),
(4, 'River View Hall', 'East Wing', 180),
(5, 'Innovation Studio', 'Floor 3', 90);

INSERT INTO speaker (id, full_name, expertise, biography, avatar_url) VALUES
(1, 'Dr. Linh Tran', 'Education Technology', 'Researcher in digital learning, blended classrooms, and AI-assisted teaching experiences for higher education institutions.', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80'),
(2, 'Nguyen Minh Khoa', 'Software Engineering', 'Engineering manager focused on backend architecture, clean code practices, mentoring, and building scalable student projects.', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80'),
(3, 'Sarah Do', 'Career Development', 'Speaker and coach helping students build strong portfolios, CVs, and communication skills for the technology job market.', 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=300&q=80'),
(4, 'Pham Gia Huy', 'Cloud Infrastructure', 'Cloud engineer with practical experience in deployment pipelines, infrastructure basics, and developer productivity.', 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=300&q=80'),
(5, 'Le Bao An', 'UI/UX and Product Design', 'Product designer who works with startup teams to improve interfaces, accessibility, and student-facing web experiences.', 'https://images.unsplash.com/photo-1488426862026-3ee34a7d66df?auto=format&fit=crop&w=300&q=80'),
(6, 'Jessica Nguyen', 'Data and AI', 'Data professional speaking on practical AI adoption, ethical experimentation, and analytics for campus innovation programs.', 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=300&q=80');

INSERT INTO users (id, email, roles, password, full_name) VALUES
(1, 'admin@conference.local', '["ROLE_ADMIN"]', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Admin'),
(2, 'student@conference.local', '["ROLE_USER"]', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Demo Student'),
(3, 'guest1@conference.local', '["ROLE_USER"]', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyen Thu Ha'),
(4, 'guest2@conference.local', '["ROLE_USER"]', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Tran Quoc Viet');

INSERT INTO session (id, conference_id, room_id, title, description, start_time, end_time, track, status) VALUES
(1, 1, 3, 'Opening Keynote: Future of Learning', 'A keynote exploring how universities are redesigning learning experiences in AI-enabled classrooms.', '2026-05-15 08:30:00', '2026-05-15 09:30:00', 'Keynote', 'scheduled'),
(2, 1, 1, 'Workshop: Build Engaging Course Content', 'A practical workshop for lecturers and teaching assistants who want to improve course materials and classroom interaction.', '2026-05-15 10:00:00', '2026-05-15 11:30:00', 'Workshop', 'scheduled'),
(3, 1, 5, 'Panel: Measuring Student Engagement', 'Panel discussion on classroom participation, digital analytics, and responsible interpretation of student data.', '2026-05-15 13:30:00', '2026-05-15 14:30:00', 'Panel', 'scheduled'),
(4, 2, 2, 'Portfolio Review Bootcamp', 'A hands-on session on structuring project portfolios for internship and fresher applications.', '2026-06-20 13:00:00', '2026-06-20 14:30:00', 'Career', 'scheduled'),
(5, 2, 4, 'From Student to Junior Developer', 'A career talk on interview readiness, teamwork expectations, and growth during the first year in industry.', '2026-06-20 15:00:00', '2026-06-20 16:00:00', 'Career', 'scheduled'),
(6, 3, 4, 'Shipping Reliable Symfony Apps', 'A backend engineering talk covering maintainable architecture, validation, forms, and clean deployment flow.', '2026-07-10 09:00:00', '2026-07-10 10:15:00', 'Backend', 'scheduled'),
(7, 3, 5, 'Cloud Basics for Student Teams', 'A beginner-friendly workshop on hosting, environment configuration, and release planning for coursework apps.', '2026-07-10 10:45:00', '2026-07-10 12:00:00', 'Cloud', 'scheduled'),
(8, 3, 3, 'Designing User-Friendly Conference Platforms', 'A session on practical UX improvements, admin workflows, and building interfaces that are easy to demo.', '2026-07-11 09:30:00', '2026-07-11 10:30:00', 'Design', 'scheduled'),
(9, 3, 1, 'AI Features Without the Hype', 'A data talk about where AI features add real value in student and event-management products.', '2026-07-11 11:00:00', '2026-07-11 12:00:00', 'AI', 'scheduled');

INSERT INTO session_speakers (session_id, speaker_id) VALUES
(1, 1),
(2, 1),
(3, 1),
(3, 6),
(4, 2),
(4, 3),
(5, 3),
(6, 2),
(7, 4),
(8, 5),
(9, 6);

INSERT INTO registrations (id, user_id, session_id, registered_at, status) VALUES
(1, 2, 1, '2026-04-28 08:00:00', 'confirmed'),
(2, 2, 4, '2026-04-28 08:05:00', 'confirmed'),
(3, 3, 2, '2026-04-28 08:15:00', 'confirmed'),
(4, 3, 6, '2026-04-28 08:20:00', 'confirmed'),
(5, 4, 5, '2026-04-28 08:25:00', 'confirmed'),
(6, 4, 8, '2026-04-28 08:30:00', 'confirmed');

COMMIT;
