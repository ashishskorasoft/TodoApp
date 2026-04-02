TodoFlow upgraded source pack

Key fixes included in this final pack:
- Database-driven roles and permissions instead of hardcoded role strings
- Separate admin and user access model preserved
- First registered user becomes Super Admin through role assignment
- Seeded roles, permissions, and owner account
- Login hero converted into useful product value content
- Existing Phase 1 / Phase 2 design language preserved

Important setup notes:
1. Replace your project with these files.
2. Run: php artisan migrate:fresh --seed
3. Run: php artisan optimize:clear
4. Run: npm install && npm run build
5. Run: php artisan serve

Demo owner account after seed:
owner@example.com
password123
