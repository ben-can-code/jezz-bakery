# ================================================================
# Jezz Bakery — Push to GitHub
# Double-click this file or run it in PowerShell to push.
# ================================================================

$repo   = "c:\xampp\htdocs\jezz bakery managment system"
$remote = "https://github.com/ben-can-code/jezz-bakery.git"

Set-Location $repo

Write-Host "`n=== Jezz Bakery — GitHub Push Script ===" -ForegroundColor Cyan

# 1. Init git if not already
if (!(Test-Path ".git")) {
    Write-Host "`n[1/6] Initializing git repository..." -ForegroundColor Yellow
    git init
    git branch -M main
} else {
    Write-Host "`n[1/6] Git already initialized." -ForegroundColor Green
}

# 2. Configure identity (update if needed)
Write-Host "`n[2/6] Setting git identity..." -ForegroundColor Yellow
git config user.name  "Bernard Mwangi"
git config user.email "ben-can-code@users.noreply.github.com"

# 3. Add remote if not set
$existingRemote = git remote get-url origin 2>$null
if ($existingRemote) {
    Write-Host "`n[3/6] Remote already set: $existingRemote" -ForegroundColor Green
} else {
    Write-Host "`n[3/6] Adding remote origin..." -ForegroundColor Yellow
    git remote add origin $remote
}

# 4. Stage all files
Write-Host "`n[4/6] Staging all files..." -ForegroundColor Yellow
git add .
git status --short

# 5. Commit
Write-Host "`n[5/6] Creating commit..." -ForegroundColor Yellow
$date = Get-Date -Format "yyyy-MM-dd HH:mm"
git commit -m "Initial release: Jezz Bakery Management System ($date)"

# 6. Push
Write-Host "`n[6/6] Pushing to GitHub..." -ForegroundColor Yellow
Write-Host "      Remote: $remote" -ForegroundColor Gray
git push -u origin main

Write-Host "`n=== DONE! Visit: https://github.com/ben-can-code/jezz-bakery ===" -ForegroundColor Green
Write-Host "Press any key to close..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
