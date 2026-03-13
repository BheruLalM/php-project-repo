
## Project Details
- Project Name: Crime Record Management System (CRMS)
- GitHub Repo: https://github.com/BheruLalM/php-project-repo
- Stack: Laravel 12, PHP 8.2, MySQL


## Test Credentials
| Role    | Email            | Password    |
|---------|------------------|-------------|
| Admin   | admin@crms.gov   | Admin@123   |
| Officer | rajan@crms.gov   | Officer@123 |
| Officer | meera@crms.gov   | Officer@123 |

## Features
- Multi-role auth (Admin / Officer)
- Criminal profile management with mugshot upload
- Crime record tracking with visual status timeline
- Encrypted complainant contact data
- Evidence file upload (images + PDFs)
- Audit logging (who viewed/edited what)
- Monthly case reports with archive function
- Search by name, alias, crime type
- Laravel Policies for role-based access (only Admin can delete)

## Local Setup Steps
1. git clone https://github.com/BheruLalM/php-project-repo.git
2. cd crms
3. composer install
4. cp .env.example .env
5. php artisan key:generate
6. Set DB credentials in .env
7. php artisan migrate:fresh --seed
8. php artisan storage:link
9. php artisan serve

## Git Info
- Dev branch: v1-development
- Production: master
- PR: v1-development → master
