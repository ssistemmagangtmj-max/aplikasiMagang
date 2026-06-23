<?php
$zipFile = 'upload_magang.zip'; // Nama file zip yang Anda upload

if (!file_exists($zipFile)) {
    exit("File $zipFile tidak ditemukan. Pastikan Anda sudah menguploadnya di folder yang sama dengan file unzip.php ini.");
}

$zip = new ZipArchive;
$res = $zip->open($zipFile);
if ($res === TRUE) {
    $zip->extractTo('./'); // Ekstrak ke folder saat ini (htdocs)
    $zip->close();
    echo "Horee! File berhasil diekstrak dengan cepat. Anda sekarang bisa menghapus file unzip.php dan upload_magang.zip ini.";
} else {
    echo "Gagal mengekstrak file zip.";
}
?>
