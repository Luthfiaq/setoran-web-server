@echo off
REM Backup database db_workshop (Laragon, Windows). Hasil: database\backup\db_workshop_TANGGAL_JAM.sql
REM Cara jadwal otomatis: lihat docs\BACKUP_RESTORE.md (Task Scheduler)

setlocal
set "MYSQL_BIN="
for /d %%D in (C:\laragon\bin\mysql\mysql-*) do set "MYSQL_BIN=%%D\bin"
if "%MYSQL_BIN%"=="" (
    echo MySQL Laragon tidak ditemukan di C:\laragon\bin\mysql
    exit /b 1
)

for /f %%i in ('powershell -NoProfile -Command "Get-Date -Format yyyy-MM-dd_HHmm"') do set "TS=%%i"

set "FOLDER=%~dp0..\database\backup"
if not exist "%FOLDER%" mkdir "%FOLDER%"

"%MYSQL_BIN%\mysqldump.exe" -u root --routines db_workshop > "%FOLDER%\db_workshop_%TS%.sql"

if %ERRORLEVEL% neq 0 (
    echo Backup GAGAL
    exit /b 1
)
echo Backup berhasil: %FOLDER%\db_workshop_%TS%.sql
endlocal
