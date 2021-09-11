@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../spiral/roadrunner-laravel/bin/rr-worker
php "%BIN_TARGET%" %*
