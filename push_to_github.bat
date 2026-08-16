@echo off
title Jezz Bakery - Push to GitHub

cd /d "c:\xampp\htdocs\jezz bakery managment system"

echo.
echo === Jezz Bakery - GitHub Push Script ===
echo.

:: Check if git is installed
git --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Git is not installed or not in PATH.
    echo Download from: https://git-scm.com/download/win
    pause & exit /b 1
)

:: Init repo if needed
if not exist ".git" (
    echo [1/6] Initializing git repository...
    git init
    git branch -M main
) else (
    echo [1/6] Git already initialized.
)

:: Set identity
echo [2/6] Setting git identity...
git config user.name "Bernard Mwangi"
git config user.email "ben-can-code@users.noreply.github.com"

:: Add remote
echo [3/6] Setting remote origin...
git remote remove origin 2>nul
git remote add origin https://github.com/ben-can-code/jezz-bakery.git

:: Stage files
echo [4/6] Staging all files...
git add .
git status --short

:: Commit
echo [5/6] Committing...
git commit -m "Initial release: Jezz Bakery Management System"

:: Push
echo [6/6] Pushing to GitHub...
echo.
echo NOTE: A browser window or terminal prompt may ask for your
echo       GitHub username and password / personal access token.
echo.
git push -u origin main

echo.
echo === DONE! ===
echo Visit: https://github.com/ben-can-code/jezz-bakery
echo.
pause
