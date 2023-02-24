<?php

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORM KARYAWAN | VSGA</title>
</head>

<body>
    <main>
        <h1>FORM KARYAWAN</h1>

        <form action="" method="get">
            <table>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>
                        <input type="text" name="nik" id="nik">
                    </td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>
                        <input type="text" name="nama" id="nama">
                    </td>
                </tr>

                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>
                        <select name="jenisKelamin" id="jenisKelamin">
                            <option value="1">Laki - Laki</option>
                            <option value="0">Perempuan</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Agama</td>
                    <td>:</td>
                    <td>
                        <select name="agama" id="agama">
                            <?php

                            foreach ($listAgama as $agama) {
                                echo "<option value='$agama'>$agama</option>";
                            }

                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Golongan</td>
                    <td>:</td>
                    <td>
                        <select name="golongan" id="golongan">
                            <?php
                            foreach ($listGolongan as $golongan) {
                                echo "<option value='$golongan'>$golongan</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Gaji Pokok</td>
                    <td>:</td>
                    <td>
                        <input type="number" name="gajiPokok" id="gajiPokok" value="0">
                    </td>
                </tr>
                <tr>
                    <td colspan="3" align="right">
                        <input style="cursor: pointer;" type="submit" value="Save" name="btnSave">
                    </td>
                </tr>
            </table>
        </form>

        <hr>

        <table border="1">
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
                    <th>TOTAL GAJI</th>
                </tr>
            </thead>
            <tbody>
                <?php

                // melakukan perulangan terhadap data karyawan
                foreach ($dataKaryawan as $karyawan) : ?>
                    <tr>
                        <td> <?= $karyawan['nik'] ?> </td>
                        <td> <?= $karyawan['nama'] ?> </td>
                        <td> <?= $karyawan['jenisKelamin'] ? "Laki-Laki" : "Perempuan" ?> </td>
                        <td> <?= $karyawan['agama'] ?> </td>
                        <td> <?= $karyawan['golongan'] ?> </td>
                        <td> <?= $karyawan['gajiPokok'] ?> </td>
                        <td>
                            <?php

                            if ($karyawan['golongan'] == "I") {
                                $tunjangan = 1000000;
                                echo $tunjangan;
                            } else if ($karyawan['golongan'] == "II") {
                                $tunjangan = 2000000;
                                echo $tunjangan;
                            } else if ($karyawan['golongan'] == "III") {
                                $tunjangan = 3000000;
                                echo $tunjangan;
                            } else {
                                echo "Tidak mendapat Tunjangan";
                            }

                            ?>
                        </td>
                        <td>
                            <?php

                            $pajak = ($karyawan['gajiPokok'] + $tunjangan) * 0.05;
                            echo $pajak;

                            ?>
                        </td>
                        <td>
                            <?php

                            $totalGaji = $karyawan['gajiPokok'] + $tunjangan - $pajak;
                            echo $totalGaji;

                            ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </main>

</body>

</html>