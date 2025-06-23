<?php
/**
 *  AJAX Endpoint: Ambil saldo tabungan siswa
 *  Path   : etabs/plugins/proses-ajax.php
 *  Method : POST
 *  Params : nis_siswa  (integer)  — NIS siswa
 *           action     (string)   — 'hapus_saldo_siswa' | 'get_saldo_siswa'
 *  Return : JSON
 */

header('Content-Type: application/json');

// hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);           // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Hanya menerima POST']);
    exit;
}

// validasi parameter
$action = $_POST['action'] ?? '';
$nisStr = $_POST['nis_siswa'] ?? '';

if ($action !== 'get_saldo_siswa') {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Aksi tidak valid']);
    exit;
}

if (!ctype_digit($nisStr)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'NIS harus numerik']);
    exit;
}

$nis = (int) $nisStr;

require_once __DIR__ . '/../inc/koneksi.php';   // koneksi DB

// --- query dengan prepared statement ---
$stmt = $koneksi->prepare("SELECT saldo FROM tb_siswa WHERE nis = ? LIMIT 1");
$stmt->bind_param('i', $nis);
$stmt->execute();
$stmt->bind_result($saldo);

if ($stmt->fetch()) {
    echo json_encode(['status' => 'success', 'saldo' => (int) $saldo]);
} else {
    http_response_code(404);                     // Not Found
    echo json_encode(['status' => 'error', 'message' => 'Siswa tidak ditemukan', 'saldo' => 0]);
}

$stmt->close();
$koneksi->close();
