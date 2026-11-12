<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\User;

class ChatbotService
{
    /**
     * Teks Sambutan Awal (Greeting)
     */
    public static function getGreeting(): string
    {
        return "Halo! 👋 Saya adalah Asisten Virtual Q-Absen.\n\n" .
               "Sebelum saya teruskan ke Tim Admin, mungkin saya bisa membantu Anda memberikan solusi cepat. " .
               "Silakan ketik **angka** dari menu di bawah ini yang paling sesuai dengan kendala Anda:\n\n" .
               "1️⃣ *Kendala Face Recognition / Kamera Absen*\n" .
               "2️⃣ *Tanya soal Token & Saldo Poin*\n" .
               "3️⃣ *Pertanyaan seputar Sistem WFH/Izin*\n" .
               "4️⃣ *Ingin tanya langsung ke Tim Admin (Manusia)*\n\n" .
               "Ketik angkanya saja ya! (contoh: **1**)";
    }

    /**
     * Proses pesan dari siswa dan kembalikan response bot.
     * Mengembalikan null jika bot tidak merespon (misal sudah diteruskan ke admin).
     */
    public static function handleMessage(Ticket $ticket, string $message): ?string
    {
        $msg = trim($message);
        
        switch ($msg) {
            case '1':
            case '1️⃣':
                return "🤖 **Solusi Face Recognition:**\n\n" .
                       "1. Pastikan wajah Anda berada tepat di tengah area kamera.\n" .
                       "2. Pastikan pencahayaan cukup terang (jangan membelakangi cahaya/backlight).\n" .
                       "3. Lepaskan masker, kacamata hitam, atau topi saat absen.\n" .
                       "4. Jika kamera buram, coba bersihkan lensa HP Anda terlebih dahulu.\n\n" .
                       "*Apakah ini membantu? Jika masih bermasalah, ketik angka* **4** *untuk memanggil Admin.*";
                
            case '2':
            case '2️⃣':
                return "🤖 **Sistem Poin & Token Marketplace:**\n\n" .
                       "Kamu akan mendapat poin (+2) setiap hadir tepat waktu, dan poin (-1) jika Alpha.\n" .
                       "Poin ini bisa ditukarkan di menu **Dompet > Marketplace** untuk mendapatkan Token sakti (seperti WFH, Izin, atau Toleransi Telat).\n\n" .
                       "⚠️ *Catatan:* Token WFH/Izin yang diaktifkan akan berlaku **seharian penuh** setelah divalidasi oleh satu Guru saja!\n\n" .
                       "*Ketik* **4** *jika masih bingung dan ingin bertanya ke Admin.*";

            case '3':
            case '3️⃣':
                return "🤖 **Sistem WFH / Izin:**\n\n" .
                       "Untuk absensi jarak jauh, kamu wajib menukar Token WFH / Izin Mendadak di Marketplace terlebih dahulu.\n" .
                       "Setelah menukar, berikan **Kode Validasi** yang muncul kepada salah satu Guru pengajar hari itu.\n" .
                       "Begitu Guru tersebut memvalidasi, sistem akan otomatis mencatat kamu Hadir/Izin untuk *semua sesi pelajaran* di hari tersebut. Kamu tidak perlu absen wajah lagi hari ini!\n\n" .
                       "*Ketik* **4** *jika ingin bicara langsung dengan Admin.*";

            case '4':
            case '4️⃣':
            case 'admin':
            case 'Admin':
                // Eskalasi ke Admin!
                $ticket->update(['needs_admin' => true]);
                return "Baik, saya telah memanggil Tim Admin manusia untuk Anda. 🧑‍💻\n\n" .
                       "Mohon tunggu sebentar ya, notifikasi sudah dikirimkan dan Admin kami akan segera membaca serta membalas keluhan Anda di obrolan ini.";

            default:
                // Jika input tidak dikenali
                return "Maaf, saya robot jadi kurang mengerti maksud Anda. 😅\n\n" .
                       "Silakan ketik **angka** saja dari menu berikut:\n" .
                       "1️⃣ *Kendala Face Recognition*\n" .
                       "2️⃣ *Soal Token & Poin*\n" .
                       "3️⃣ *Sistem WFH/Izin*\n" .
                       "4️⃣ *Bicara dengan Admin Manusia*";
        }
    }

    /**
     * Dapatkan ID user untuk Bot (Ambil admin pertama sebagai perwakilan).
     */
    public static function getBotUserId(): ?int
    {
        $admin = User::whereHas('role', function($q) { 
            $q->where('nama_role', 'admin'); 
        })->first();
        
        return $admin ? $admin->id_user : null;
    }
}
