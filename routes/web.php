<?php

use App\Models\Pendaftaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('user/register');
});

Route::get('/print/surat-balasan/{pendaftaran}', function (Pendaftaran $pendaftaran) {
    if ($pendaftaran->status_pendaftaran !== 'diterima') {
        abort(403, 'Surat balasan tidak dapat dicetak untuk status ini.');
    }

    // Calculate duration for the letter
    $startDate = Carbon::parse($pendaftaran->tgl_mulai);
    $endDate = Carbon::parse($pendaftaran->tgl_selesai);
    $duration = $startDate->diffInDays($endDate) + 1;
    $durasi_magang = '';
    if ($duration < 30) {
        $durasi_magang = $duration . ' hari';
    } elseif ($duration >= 30 && $duration < 365) {
        $durasi_magang = round($duration / 30) . ' bulan';
    } else {
        $durasi_magang = round($duration / 365) . ' tahun';
    }


    $htmlContent = view('filament.pages.pendaftaran.surat-balasan', [
        'record' => $pendaftaran,
        'user' => $pendaftaran->user,
        'nomor_surat' => "000/{$pendaftaran->id}/KOMINFO/2025",
        'tanggal_surat_formatted' => Carbon::now()->translatedFormat('d F Y'),
        'tanggal_surat_asal' => $pendaftaran->created_at,
        'durasi_magang' => $durasi_magang,
        'tgl_mulai' => Carbon::parse($pendaftaran->tgl_mulai)->translatedFormat('d F Y'),
        'tgl_selesai' => Carbon::parse($pendaftaran->tgl_selesai)->translatedFormat('d F Y'),
        'kadin_nama' => 'Adi Zahri, S.I.Kom',
        'kadin_pangkat' => 'Pembina/IV.a',
        'kadin_nip' => '197207171993031003',
    ])->render();

    // Return a response with the HTML content and appropriate headers.
    // The 'inline' disposition helps browsers display it directly rather than download.
    return response($htmlContent)
        ->header('Content-Type', 'text/html')
        ->header('Content-Disposition', 'inline; filename="Surat_Balasan_Kerja_Praktek' . $pendaftaran->id . '.html"');
})->name('print.surat.balasan');

Route::middleware(['throttle:5,1'])->group(function () {});
