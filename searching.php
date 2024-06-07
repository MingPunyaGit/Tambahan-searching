<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Jadwal Konser</title>
    <link rel="stylesheet" href="searching.css">
</head>
<body>
    </div>
    <header>
      <div class="logo1">
        <a href="#">
          <img src="img/logo.png" alt="logo" />
        </a>
      </div>
      <div class="website">
        <a href="#">
          <h1>OJINK</h1>
        </a>
      </div>
      <div class="login">
        <a href="#">LOGIN</a>
      </div>
    </header>
    <div class="banner">
      <h1 class="welcome">SELAMAT DATANG!</h1>
      <h1>PASUKAN OJINK</h1>
      <p>
        Pasukan Ojink adalah sebuah komunitas atau kelompok informal yang
        terdiri dari orang-orang yang gemar berjoget dan bernyanyi bersama
        dengan penuh semangat, terutama mengikuti iringan musik dangdut koplo
        atau campursari.
      </p>
    </div>
    <div class="cari">
        <form method="GET" action="searching.php">
            <label for="cari">Pencarian: </label>
            <input type="text" id="cari" name="cari" value="<?php if (isset($_GET['cari'])) { echo htmlspecialchars($_GET['cari']); } ?>">
            <button type="submit">Cari</button>
        </form>
    </div>
    <div>
    <?php
// Include file koneksi
include "koneksi.php";

// Ambil input pencarian
$pencarian = isset($_GET['cari']) ? $_GET['cari'] : '';

// Query pencarian
$querycari = $con->prepare("SELECT * FROM jadwal_konser WHERE title LIKE ?");
$searchTerm = "%{$pencarian}%";
$querycari->bind_param("s", $searchTerm);
$querycari->execute();

$result = $querycari->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<img src='img/" . htmlspecialchars($row['img']) . "' alt='Image' width='250'><br>";
        echo "<strong>Judul:</strong> " . htmlspecialchars($row['title']) . "<br>";
        echo "<strong>Tanggal:</strong> " . htmlspecialchars($row['tanggal']) . "<br>";
        echo "<strong>Lokasi:</strong> " . htmlspecialchars($row['lokasi']) . "<br>";
        echo "<strong>Waktu:</strong> " . htmlspecialchars($row['waktu']) . "<br>";
        echo "<strong>Harga:</strong> " . htmlspecialchars($row['harga']) . "<br>";
        echo "<strong>Nama Orkes:</strong> " . htmlspecialchars($row['nama_orkes']) . "<br>";
        echo "<strong>Sosial Media:</strong> <a href='" . htmlspecialchars($row['sosial_media_link']) . "'>" . htmlspecialchars($row['sosial_media_link']) . "</a><br>";
        echo "</div><hr>";
    }
} else {
    echo "Tidak ada hasil yang ditemukan untuk pencarian Anda.";
}

$artis = "Dinda Teratu";

// Query untuk mengecek keberadaan artis
$query_check = mysqli_query($con, "SELECT COUNT(*) as count FROM jadwal_konser WHERE artis LIKE '%$artis%'");
if (!$query_check) {
    die("Query error: " . mysqli_error($con));
}

$row_check = mysqli_fetch_assoc($query_check);
$artis_ada = $row_check['count'] > 0;

// Jika artis ditemukan, ambil data konser
$konser_data = [];
if ($artis_ada) {
    $query_konser = mysqli_query($con, "SELECT id, img, title, tanggal, lokasi FROM jadwal_konser WHERE artis LIKE '%$artis%' AND tanggal >= CURDATE() ORDER BY tanggal LIMIT 3");
    if (!$query_konser) {
        die("Query error: " . mysqli_error($con));
    }
    $konser_data = mysqli_fetch_all($query_konser, MYSQLI_ASSOC);
}

// Menampilkan data konser jika artis ditemukan
if ($artis_ada && !empty($konser_data)) {
    echo '<section class="rekomendasi">
        <h1>REKOMENDASI ORKES</h1>
        <div class="side">
            <h2>ORKES POPULER!</h2>
            <p>Kumpulan Orkes Populer yang menampilkan artis-artis terkenal</p>
        </div>
        <div class="halrek">';

    foreach ($konser_data as $konser) {
        echo '<div class="kotak">
            <div class="poster">
                <img src="img/' . $konser['img'] . '" alt="' . $konser['title'] . '" />
            </div>
            <div class="isi">
                <a href="detail.php?nama=' . $konser['title'] . '">
                    <h2>' . $konser['title'] . '</h2>
                </a>
                <table>
                    <tr>
                        <td>Tanggal</td>
                        <td>: ' . date('d F Y', strtotime($konser['tanggal'])) . '</td>
                    </tr>
                    <tr>
                        <td>Lokasi</td>
                        <td>: ' . $konser['lokasi'] . '</td>
                    </tr>
                </table>
            </div>
        </div>';
    }

    echo '</div>
    </section>';
} else {
    echo "Data konser tidak ditemukan untuk artis '$artis'.";
}


// Tutup koneksi
$querycari->close();
$con->close();
?>

    <footer>
      <div class="footer1">
        <div class="footerkiri">
          <img class="logo" src="img/logo.png" alt=" logo" />
          <p>
            <b>Ojink</b> adalah platform online yang menyediakan layanan
            pembelian tiket untuk berbagai acara, seperti konser musik,
            festival, hajatan, dan happy party. Website ini didirikan pada tahun
            2024 oleh sekelompok pemuda yang ingin memudahkan masyarakat dalam
            mendapatkan tiket orkes favorit mereka.
          </p>
          <p>Sosial Media:</p>
          <div class="sosmed">
            <a href="https://www.tiktok.com/@info_orkes_pati"
              ><img src="img/tiktok.png" alt="tiktok"
            /></a>
            <a href="https://www.facebook.com/infoorkespati"
              ><img src="img/fb.png" alt="fb"
            /></a>
            <a href="https://www.instagram.com/infoorkespati/"
              ><img src="img/ig.png" alt="ig"
            /></a>
          </div>
        </div>
        <div class="footerkiri">
          <p><b>INFORMASI</b></p>
          <a href="#">
            <p>Syarat dan Ketentuan</p>
          </a>
          <a href="#">
            <p>Privasi</p>
          </a>
        </div>
      </div>
      <div class="copyright">
        <p>PT. Pasukan Ojink Indonesia (Ojink)</p>
        <p>&copy; 2024 Ojink. All Rights Reserved</p>
      </div>
    </footer>
</body>
</html>
