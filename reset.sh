#!/bin/bash

echo "[+] Stopping containers & removing volumes..."
docker compose down -v

echo "[+] Removing dangling volumes (optional cleanup)..."
docker volume prune -f

echo "[+] Rebuilding & starting fresh containers..."
docker compose up -d --build

echo "[✓] Lab fully reset (database & volume cleared)"