<?php
// Mulai session
session_start();

// include file db.php
include_once 'db.php';


// cek jika sudah login
if (isset($_SESSION['login'])) {

    // paksa pengguna ke halaman index.php
    header('Location: ../index.php');
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: white;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>

    <!-- Php script -->
    <?php

    // cek apakah tombol login telah diklik
    if (isset($_POST['login'])) {

        // mendapatkan nilai input dari form login
        $username = strip_tags(htmlentities($_POST['username']));
        $password = strip_tags(htmlentities($_POST['password']));
        $remember = isset($_POST['remember']) ? $_POST['remember'] : '';

        // membuat prepared statement untuk mengambil data pengguna dari tabel database
        $stmt = $conn->prepare("SELECT * FROM tbl_auth WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // memeriksa apakah username tersedia pada database
        if ($result->num_rows > 0) {

            // mengambil data pengguna dari tabel database
            $row = $result->fetch_assoc();

            // memeriksa apakah password yang dimasukkan sesuai dengan password di database
            if (password_verify($password, $row['password'])) {

                // menyimpan nilai pada session untuk mengidentifikasi pengguna yang telah login
                $_SESSION['username'] = $row['username'];
                $_SESSION['login'] = true;

                // mengatur cookie jika pengguna memilih opsi "remember me"
                if ($remember == 'on') {

                    // mengatur waktu kadaluarsa cookie 2 hari
                    $expire = time() + (2 * 24 * 60 * 60);
                    setcookie('app', hash('sha512', 'app_home'), $expire, '/');
                }

                // tutup koneksi database
                tutupKoneksi($stmt, $conn);

                // mengarahkan pengguna ke halaman index setelah login berhasil
                header("Location: ../index.php");
            } else {

                // tutup koneksi database
                tutupKoneksi($stmt, $conn);

                // menampilkan pesan error jika password yang dimasukkan salah
                echo "<script>
            Swal.fire(
                'GAGAL',
                'Password yang Anda masukkan salah.',
                'error'
            )
            </script>";
            }
        } else {

            // tutup koneksi database
            tutupKoneksi($stmt, $conn);

            // menampilkan pesan error jika username tidak ditemukan
            echo "<script>
            Swal.fire(
                'GAGAL',
                'Login gagal. Username tidak di temukan.',
                'error'
            )
            </script>";
        }
    }

    // cek jika registrasi berhasil
    if (isset($_SESSION['success'])) {

        // beri pesan ke pengguna
        echo "<script>
        Swal.fire(
            'BERHASIL!',
            'Proses registrasi berhasil. Silakan login terlebih dahulu.',
            'success'
        )
        </script>";

        // hapus session success registrasi
        unset($_SESSION['success']);

        // cek jika password berhasil di ubah
    } else if (isset($_SESSION['success_change'])) {

        // beri pesan ke pengguna
        echo "<script>
        Swal.fire(
            'BERHASIL!',
            'Berhasil ubah password. Silakan login kembali.',
            'success'
        )
        </script>";

        // hapus session success registrasi
        unset($_SESSION['success_change']);
    }
    ?>

    <!-- Section -->
    <section class="vh-100">

        <!-- Container -->
        <div class="container h-100">

            <!-- Row -->
            <div class="row d-flex justify-content-center align-items-center h-100">

                <!-- Col -->
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 mt-4">

                    <!-- Card -->
                    <div class="card shadow-lg p-3 mb-5 bg-body rounded" style="border: 2px solid white;">

                        <!-- Card body -->
                        <div class="card-body p-5">

                            <!-- Login -->
                            <h3 class="mb-5 text-center">Login</h3>
                            <!-- </Akhir login -->

                            <!-- Form -->
                            <form method="post" action="">

                                <!-- Username -->
                                <div class="mb-4">
                                    <label class="form-label" for="username">Username</label>
                                    <input type="text" id="username" class="form-control" name="username" autocomplete="off" required />
                                </div>
                                <!-- </Akhir username -->

                                <!-- Password -->
                                <div class="mb-4">
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" id="password" class="form-control" name="password" required />
                                </div>
                                <!-- </Akhir password -->

                                <!-- Checkbox -->
                                <div class="form-check d-flex justify-content-start mb-4">
                                    <input class="form-check-input" type="checkbox" name="remember" id="form1Example3" />
                                    <label class="form-check-label ms-2" for="form1Example3"> Remember password </label>
                                </div>
                                <!-- </Akhir checkbox -->

                                <!-- Lupa password -->
                                <div class="mb-3">
                                    <a href="lupa_password.php">Lupa Password?</a>
                                </div>
                                <!-- </Akhir lupa password -->

                                <!-- Tombol login -->
                                <div class="text-center">
                                    <button class="btn btn-primary w-75 text-center rounded-pill" type="submit" name="login">Login</button>
                                </div>
                                <!-- </Akhir tombol login -->

                            </form>
                            <!-- </Akhir form -->

                            <!-- Garis -->
                            <hr class="my-4">
                            <!-- </Akhir garis -->

                            <!-- Belum registrasi -->
                            <div class="text-center">
                                <a href="registrasi.php">Belum Registrasi?</a>
                            </div>
                            <!-- </Akhir belum registrasi -->

                        </div>
                        <!-- </Akhir card body -->

                    </div>
                    <!-- </Akhir card -->

                </div>
                <!-- </Akhir col -->

            </div>
            <!-- </Akhir row -->

        </div>
        <!-- </Akhir container -->

    </section>
    <!-- </Akhir section -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>

</html>