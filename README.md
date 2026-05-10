# Conference Scheduler

Conference Scheduler is a coursework web application built with PHP and Symfony conventions. It supports conference publishing, session scheduling, speaker management, attendee registration, and an admin dashboard.

## Main Features

- Public home page with highlighted conferences and sessions
- Conference listing, conference detail, and schedule pages
- Session listing and session detail pages
- Speaker listing and speaker profile pages
- Attendee registration, login, and personal schedule
- Admin dashboard
- Admin CRUD for conferences, sessions, speakers, and rooms
- Session conflict validation for room and speaker collisions

## Entity Models

- `User`
- `Conference`
- `Room`
- `Speaker`
- `Session`
- `Registration`

## Controllers

- `HomeController`
- `SecurityController`
- `ConferenceController`
- `SessionController`
- `SpeakerController`
- `RegistrationController`
- `AdminController`

## Included Views

This project includes more than 10 Twig views, including:

- Home
- Login
- Register
- Conference list
- Conference detail
- Conference schedule
- Session list
- Session detail
- Speaker list
- Speaker detail
- My schedule
- Admin dashboard
- Admin conference list/form
- Admin session list/form
- Admin speaker list/form
- Admin room list/form

## Suggested Local Setup

1. Install PHP 7.4+, Composer, and SQLite or MySQL.
2. Run `composer install`.
3. Configure `.env` if you want to switch database engine.
4. Run `php bin/console doctrine:migrations:migrate`.
5. Import sample data from `data/seed.sql`.
6. Start the app with `symfony server:start` or your local web server.

For SQLite, a simple import example is:

```bash
sqlite3 var/data.db < data/seed.sql
```

## Demo Accounts

- Admin: `admin@conference.local`
- User: `student@conference.local`
- Password for seeded accounts: `password`

## Git Init And Repository Setup

To initialize and publish the repository:

```bash
git init
git add .
git commit -m "Initial Conference Scheduler setup"
git branch -M main
git remote add origin <your-public-repository-url>
git push -u origin main
```

The repository also includes:

- `.editorconfig` for consistent formatting
- `.gitattributes` for line ending normalization
- `.github/workflows/ci.yml` for basic CI validation
- `docs/demo-checklist.md` for presentation flow

## Notes

- The codebase is structured around Symfony 5.4 conventions compatible with PHP 7.4.
- The environment in this workspace did not allow dependency installation, so vendor packages were not installed here.
