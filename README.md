# General POS cleanup — no-backup version

**Warning:** Applying this script permanently deletes listed call-center files and overwrites the included POS starter files. It creates **no backup**. It does not change database tables or user/password records. Use only if you have your own independently verified backup.

Unzip this package directly into `C:\xampp\htdocs\generalpos-cleanup` (the `.ps1` file should be at that path, not one folder deeper).

**Preview:**
```cmd
powershell -ExecutionPolicy Bypass -File "C:\xampp\htdocs\generalpos-cleanup\cleanup-callcenter.ps1" -ProjectPath "C:\xampp\htdocs\generalpos"
```

**Apply (permanent deletion):**
```cmd
powershell -ExecutionPolicy Bypass -File "C:\xampp\htdocs\generalpos-cleanup\cleanup-callcenter.ps1" -ProjectPath "C:\xampp\htdocs\generalpos" -Apply
```

**Verify:**
```cmd
php artisan optimize:clear
php artisan route:list
npm run build
```

The script retains existing authentication code and database users, but replaces specific layout, routing and login-controller files to eliminate references to removed call-center functionality. Check `remove-paths.txt` and `replacements/` before applying. It will not clean unrelated files absent from its manifest.

