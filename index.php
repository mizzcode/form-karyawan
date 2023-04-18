<?php
session_start();

if (!isset($_SESSION['login']) && !isset($_SESSION['username'])) {
    header('Location: auth/login.php');
    exit();
}
require_once __DIR__ . "/vendor/autoload.php";

use RandomLib\Factory;
use SecurityLib\Strength;

$listAgama = ['Islam', 'Kristen', 'Hindu', 'Buddha', 'Katolik', 'Kong Hu Chu'];
sort($listAgama);

$listGolongan = ['I', 'II', 'III'];

// membaca file json
$dataJson = file_get_contents("data/data_karyawan.json");
// ubah json ke array
$dataKaryawan = json_decode($dataJson, true);

if (isset($_GET['btnSave'])) {
    // ambil data setiap input user
    $nik = $_GET['nik'];
    $nama = $_GET['nama'];
    $jenisKelamin = $_GET['jenisKelamin'];
    $agama = $_GET['agama'];
    $golonan = $_GET['golongan'];
    $gajiPokok = $_GET['gajiPokok'];

    // buat array assosiatif baru dan value nya kita ambil dari input user menggunakan method get
    $dataBaru = [
        "nik" => $nik,
        "nama" => $nama,
        "jenisKelamin" => $jenisKelamin,
        "agama" => $agama,
        "golongan" => $golonan,
        "gajiPokok" => $gajiPokok,
    ];

    // memasukkan array data baru ke array data karyawan
    array_push($dataKaryawan, $dataBaru);

    // ubah array ke json
    $dataToJson = json_encode($dataKaryawan, JSON_PRETTY_PRINT);

    // menulis ke file json
    file_put_contents("data/data_karyawan.json", $dataToJson);
}

function hitungTunjangan($golongan)
{
    if ($golongan == "I") {
        return 1000000;
    } else if ($golongan == "II") {
        return 2000000;
    } else if ($golongan == "III") {
        return 3000000;
    } else {
        return 0;
    }
}
function hitungPajak($gajiPokok, $tunjangan)
{
    $pajak = ($gajiPokok + $tunjangan) * 0.05;
    return $pajak;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- BOOTSTRAP V5.3 -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <title>FORM KARYAWAN | VSGA</title>
</head>

<body>

    <heading>
        <div class="nav justify-content-center mt-2">
            <img src="gambar/icons-hero.png" alt="bootstrap" width="100">
        </div>
        <div class="nav justify-content-center mt-2">
            <div class="nav-item">
                <h2>
                    FORM KARYAWAN
                </h2>
            </div>
        </div>
    </heading>

    <!-- Modal Tambah Data Karyawan -->
    <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Data Karyawan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="" method="get" class="needs-validation" novalidate>
                        <table>
                            <tr>
                                <td>
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" name="nik" placeholder="NIK" required>
                                        <div class="valid-feedback">
                                            Looks Good!
                                        </div>
                                        <label for="floatingInput">NIK</label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="nama" placeholder="NAMA">
                                        <div class="valid-feedback">
                                            Looks Good!
                                        </div>
                                        <label for="floatingInput">NAMA</label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="jenisKelamin" aria-label="Floating label select example">
                                            <option selected disabled>Pilih</option>
                                            <option value="1">Laki-Laki</option>
                                            <option value="0">Perempuan</option>
                                        </select>
                                        <div class="valid-feedback">
                                            Looks Good!
                                        </div>
                                        <label for="floatingSelect">Jenis Kelamin</label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="agama" aria-label="Floating label select example">
                                            <?php

                                            echo "<option selected disabled>Pilih</option>";

                                            foreach ($listAgama as $agama) {
                                                echo "<option value='$agama'>$agama</option>";
                                            }

                                            ?>
                                        </select>
                                        <div class="valid-feedback">
                                            Looks Good!
                                        </div>
                                        <label for="floatingSelect">Agama</label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="golongan" aria-label="Floating label select example">
                                            <?php

                                            echo "<option selected disabled>Pilih</option>";

                                            foreach ($listGolongan as $golongan) {
                                                echo "<option value='$golongan'>$golongan</option>";
                                            }

                                            ?>
                                        </select>
                                        <div class="valid-feedback">
                                            Looks Good!
                                        </div>
                                        <label for="floatingSelect">Golongan</label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="input-group input-group-default mb-3">
                                        <span class="input-group-text" id="inputGroup-sizing-default">Gaji
                                            Pokok</span>
                                        <input type="number" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="gajiPokok" required>
                                        <div class="invalid-feedback">
                                            You must fill gaji pokok.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="modal-footer mb-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="btnSave">Save changes</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <main>

        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-md" id="add">
                    <a class="btn btn-danger float-right mb-5" href="auth/logout.php">Log out</a>
                    <button class="btn btn-success float-end me-9 mb-5" data-bs-toggle="modal" data-bs-target="#add" name="btnSave">Tambah Data Karyawan</button>
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>NAMA</th>
                        <th>JENIS KELAMIN</th>
                        <th>AGAMA</th>
                        <th>GOLONGAN</th>
                        <th>GAJI POKOK</th>
                        <th>TUNJANGAN</th>
                        <th>PAJAK</th>
                        <th>GAJI BERSIH</th>
                        <th>TOKEN</th>
                    </tr>
                </thead>

                <tbody>
                    <?php

                    // melakukan perulangan terhadap data karyawan
                    foreach ($dataKaryawan as $karyawan) :

                        $gajiPokok = $karyawan['gajiPokok'];
                        $tunjangan = hitungTunjangan($karyawan['golongan']);
                        $pajak = hitungPajak($gajiPokok, $tunjangan);
                        $gajiBersih = ($gajiPokok + $tunjangan) - $pajak;

                        $factory = new Factory;
                        $generator = $factory->getGenerator(new Strength(Strength::MEDIUM));
                        $generator = $factory->getMediumStrengthGenerator();
                        $token = $generator->generateString(10);
                    ?>
                        <tr>
                            <td> <?= $karyawan['nik'] ?> </td>
                            <td> <?= $karyawan['nama'] ?> </td>
                            <td> <?= $karyawan['jenisKelamin'] ? "Laki-Laki" : "Perempuan" ?> </td>
                            <td> <?= $karyawan['agama'] ?> </td>
                            <td> <?= $karyawan['golongan'] ?> </td>
                            <td> <?= $karyawan['gajiPokok'] ?> </td>
                            <td> <?= $tunjangan ?> </td>
                            <td> <?= $pajak ?> </td>
                            <td> <?= $gajiBersih ?> </td>
                            <td> <?= $token ?> </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

    </main>

</body>

</html>