# Open Mind - Mental Wellness Website

## Overview
**Open Mind** is a web-based mental wellness platform designed to help users improve their mental health through guided meditation, therapy sessions, mindfulness videos, podcasts, yoga, and digital detox activities. The platform aims to provide accessible mental health resources and interactive tools for self-care.

---

## Objectives
- Provide easy access to mental wellness resources.
- Allow users to track their mood and progress over time.
- Enable users to book therapy sessions with professional therapists.
- Offer mindfulness videos, podcasts, and audio/video therapy for relaxation.
- Maintain a secure and interactive platform for users.

---

## Features
- **Home Page**: Overview, latest posts, hero section, featured sections.
- **Mood Tracker**: Track daily moods and view analysis in charts.
- **Talk With Roob (Chatbot)**: Personalized mental wellness guidance.
- **Blogs & Stories**: Submit, edit, delete, and search user stories.
- **Audio/Video Therapy**: Watch videos, listen to audio therapy sessions.
- **Articles**: Read mental wellness-related articles.
- **Digital Detox**: Tips and interactive guides for digital well-being.
- **Podcast**: Watch and listen to podcasts.
- **Yoga**: Guided yoga sessions.
- **Dream Analyzer**: Log and analyze dreams.
- **Find Support**: Book therapy sessions and generate booking receipts (PDF).
- **Admin Dashboard**: Manage users, bookings, stories, articles, yoga, podcasts, audio/video therapy, and digital detox content.
- **Therapist Dashboard**: View bookings.
- **All Pages**: Dark mode feature.

---

## Tools and Technologies
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend**: PHP 8
- **Database**: MongoDB (storing users, bookings, stories, media content)
- **Libraries & Plugins**:
  - AOS.js (animations)
  - Font Awesome (icons)
  - Bootstrap (responsive design)

---

## System Design

### Architecture
- **Client Side**: Responsive interface for desktop.
- **Server Side**: PHP handles requests, forms, and MongoDB interactions.
- **Database**: MongoDB stores users, bookings, therapists, stories, articles, podcasts.

### Database Design

| Collection       | Fields |
|-----------------|--------|
| Users           | Id, fullname, email, password |
| Bookings        | Id, name, address, email, phone, date, time, therapist_name, therapist_specialty, patient_number, session_price, created_at |
| Stories         | Id, name, title, story, created_at |
| Therapists      | Id, index, name, email, password, specialty, created_at |
| Articles        | Id, title, author, content, reading_time, created_at |
| Podcasts        | Id, title, url, description, thumbnail, created_at |
| Admins          | Id, email, password, created_at |
| Detox Challenges| Id, title, description, url, created_at |
| Dream Analyzer  | Id, title, description, analysis, created_at |
| Moods           | Id, user, mood, note, date, created_at |
| Therapy         | Id, user, thought, created_at |
| Yoga            | Id, title, video_url, description, benefits, created_at |

---

## Challenges Faced
- Integrating MongoDB with PHP efficiently.
- Handling dynamic user-generated content.
- Ensuring responsive design across devices.
- Implementing a fully functional admin dashboard with CRUD operations.

---

## Future Enhancements
- Implement AI-based chat support for mental wellness guidance.
- Add user analytics dashboard to track mood trends.
- Mobile app version for iOS and Android.
- Integrate video conferencing for live therapy sessions.
- Implement push notifications for reminders and tips.
- Add multi-language support for global users.
- Include gamification features like badges, streaks, and achievements to increase engagement.

---

## Conclusion
The Open Mind website provides a comprehensive platform for mental wellness, combining educational resources, therapy booking, mindfulness exercises, and interactive content. With future enhancements, it can further support mental health globally, offering a complete solution for users seeking mental wellness assistance.

---

## Contact
**Developer**: H.S.A. Liyanage  
**Email**: hashiliyanage231@gmail.com  
**GitHub**: [https://github.com/hasanthikaliyanage](https://github.com/hasanthikaliyanage)
