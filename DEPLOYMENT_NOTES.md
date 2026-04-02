# Deployment Notes

This archive appears to be a partial Laravel application snapshot rather than a full framework root.

## Before deployment
1. Place these folders into the correct Laravel root if needed.
2. Run migrations.
3. Clear caches.
4. Rebuild assets with Vite.
5. Run the reminder command on a scheduler.

## Expected commands
- `php artisan migrate`
- `php artisan optimize:clear`
- `npm install && npm run build`
- schedule the reminder command used by the project

## Manual checks recommended
- Login/register
- Task create/edit/complete/archive
- Notification feed and test notification
- Admin dashboard access by role
- Role create/edit/delete for custom roles
- Reminder preferences
- Calendar rendering
