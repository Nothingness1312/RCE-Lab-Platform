# 🚀 RCE Lab Platform

<div align="center">
  
  ![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
  ![Docker](https://img.shields.io/badge/docker-ready-2496ED.svg)
  ![PHP](https://img.shields.io/badge/PHP-8.0-777BB4.svg)
  ![SQLite](https://img.shields.io/badge/SQLite-3-003B57.svg)
  [![Discord](https://img.shields.io/badge/Discord-Join-5865F2.svg)](https://discord.gg/nH94vYstKA)
  [![GitHub](https://img.shields.io/badge/GitHub-Repo-181717.svg)](https://github.com/Nothingness1312/RCE-Lab-Platform)

  <h3>🔥 Platform Pembelajaran Remote Code Execution (RCE) 🔥</h3>
  <p>Belajar keamanan web dengan praktik langsung dalam lingkungan yang aman</p>
  
  <img src="https://raw.githubusercontent.com/Nothingness1312/RCE-Lab-Platform/main/docs/screenshot.png" alt="Screenshot" width="600">
  
</div>

---

## 📋 Daftar Isi
- [✨ Fitur](#-fitur)
- [⚡ Quick Start](#-quick-start)
- [📦 Instalasi Manual](#-instalasi-manual)
- [🔄 Reset Data](#-reset-data)
- [🎮 Cara Bermain](#-cara-bermain)
- [📁 Struktur Project](#-struktur-project)
- [🧠 Tujuan Pembelajaran](#-tujuan-pembelajaran)
- [⚠️ Catatan (Bisa Curang, Tapi…)](#-catatan-penting)
- [💬 Community](#-community)
- [🏁 Penutup](#-penutup)

---

## ✨ Fitur

| Fitur | Deskripsi |
|-------|-----------|
| 🏗️ **Docker Support** | Jalankan dengan satu perintah |
| 🎯 **Multiple Levels** | 2 level dengan tingkat kesulitan berbeda |
| 🔐 **Progres Tracking** | Progress tersimpan di database SQLite |
| 🎨 **Modern UI** | Tampilan responsif dengan tema gelap |
| 📱 **Mobile Friendly** | Bisa diakses dari berbagai device |
| 🔧 **Easy Setup** | Instalasi cepat tanpa konfigurasi rumit |

---

## ⚡ Quick Start

```bash
git clone https://github.com/Nothingness1312/RCE-Lab-Platform.git && cd RCE-Lab-Platform && docker compose up -d
```

Buka di browser:

```text
http://localhost:8080
```

---

## 📦 Instalasi Manual

```bash
git clone https://github.com/Nothingness1312/RCE-Lab-Platform.git
cd RCE-Lab-Platform
docker compose up -d
```

---

## 🔄 Reset Data

Ingin mengulang progress dari awal? Gunakan script berikut:

```bash
./reset.sh
```

### 🔍 Apa yang dilakukan script ini?

- Menghentikan container Docker
- Menghapus database (`db.sqlite`)
- Membersihkan semua file upload
- Mengembalikan kondisi lab seperti fresh install

---

### ⚠️ Catatan Penting

Jika menjalankan di Windows (PowerShell), gunakan:

```bash
bash reset.sh
```

Pastikan Docker sudah berjalan sebelum menjalankan ulang lab.

## 🎮 Cara Bermain

1. Daftar menggunakan username  
2. Masuk ke dashboard  
3. Mulai dari Level 1  
4. Analisa fitur yang tersedia  
5. Temukan celah keamanan  
6. Eksploitasi untuk mendapatkan flag  
7. Submit flag untuk membuka level berikutnya  

---

## 📁 Struktur Project

```
.
├── index.php
├── init.php
├── style.css
├── .env.example
├── docker-compose.yml
├── Dockerfile
├── reset.sh
│
├── levels/
│   ├── level1/
│   └── level2/
│
├── data/
│   └── db.sqlite
```

---

## 🧠 Tujuan Pembelajaran

- Memahami konsep dasar Remote Code Execution  
- Mengenali vulnerability upload & command execution  
- Melatih pola pikir attacker  
- Belajar eksploitasi aplikasi web  

---

---

## ⚠️ Catatan (Bisa Curang, Tapi…)

Platform ini berjalan **sepenuhnya secara lokal** di mesin kamu.

Artinya:
- Semua file bisa kamu akses langsung
- Termasuk konfigurasi internal dan file lainnya

> ⚠️ Kamu *bisa saja* membaca file seperti `.env` atau file lain secara langsung  
> tapi itu bukan tujuan dari challenge ini.

🎯 Fokus utamanya adalah:
- memahami vulnerability
- eksploitasi lewat aplikasi
- bukan mengambil jalan pintas

---

## 💬 Community

- Discord: https://discord.gg/nH94vYstKA
- GitHub: https://github.com/Nothingness1312/RCE-Lab-Platform  

---

## 🏁 Penutup

Selamat belajar dan eksplorasi 🔥  

Platform ini dibuat untuk membantu memahami keamanan aplikasi web secara praktik langsung.  
Gunakan dengan bijak dan tetap dalam batas etika.

> Learn → Exploit → Understand → Improve 🚀