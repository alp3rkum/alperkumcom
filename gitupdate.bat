@echo off
set /p commit_msg="Commit message: "
set /p branch="Branch: "
git add .
git commit -m "%commit_msg%"
git push origin %branch%
echo.

pause