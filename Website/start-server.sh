#!/bin/bash

echo "========================================"
echo "  KHOI DONG WEBSITE CODEIGNITER"
echo "========================================"
echo ""

# Kiem tra PHP
if ! command -v php &> /dev/null; then
    echo "[LOI] PHP chua duoc cai dat hoac chua co trong PATH"
    echo "Vui long cai dat PHP hoac them PHP vao PATH"
    exit 1
fi

echo "[INFO] Dang khoi dong server..."
echo "[INFO] Server se chay tai: http://localhost:8000"
echo "[INFO] Nhan Ctrl+C de dung server"
echo ""

# Chuyen den thu muc Website
cd "$(dirname "$0")"

# Khoi dong PHP built-in server voi router
php -S localhost:8000 router.php

