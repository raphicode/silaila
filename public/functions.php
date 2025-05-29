<?php
	//buat koneksi database
	$koneksi = mysqli_connect("localhost", "root", "", "db_silaila");
	
	function query($query) {
		global $koneksi;
		$result = mysqli_query($koneksi, $query);
		$tamus = [];
		while($tamu = mysqli_fetch_assoc($result) ) {
			$tamus[] = $tamu;
		}
		return $tamus;
	}

	function tambah($data) {
		global $koneksi;

		$nama = htmlspecialchars($data['nama']);
		$jenis_kelamin = htmlspecialchars($data['jenis_kelamin']);
		$instansi = htmlspecialchars($data['instansi']);
		$email = htmlspecialchars($data['email']);
		$no_hp = htmlspecialchars($data['no_hp']);
		$media_layanan = htmlspecialchars($data['media_layanan']);
		$keperluan = htmlspecialchars($data['keperluan']);
		$rincian_keperluan = htmlspecialchars($data['rincian_keperluan']);

		$tanggal_hari_ini = date('Y-m-d');

		$nomor_antrian = null;
		if ($media_layanan === "Kunjungan Langsung") {
			$result = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM pengunjung WHERE DATE(time) = '$tanggal_hari_ini' AND media_layanan = 'Kunjungan Langsung'");
			$row = mysqli_fetch_assoc($result);
			$nomor_antrian = ($row && isset($row['jumlah'])) ? $row['jumlah'] + 1 : 1;
		}

		$nip_petugas = !empty($data["nip_petugas"]) ? htmlspecialchars($data["nip_petugas"]) : null;
		$nama_petugas = null;


		$stmt = $koneksi->prepare("INSERT INTO pengunjung 
			(nama, jenis_kelamin, instansi, email, no_hp, media_layanan, keperluan, rincian_keperluan, nomor_antrian, nip_petugas, nama_petugas) 
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

		if (!$stmt) {
			echo "Prepare failed: " . $koneksi->error;
			return 0;
		}

		// Bind parameter dengan tipe yang sesuai
		$stmt->bind_param(
			"ssssssssiss", 
			$nama, 
			$jenis_kelamin, 
			$instansi, 
			$email, 
			$no_hp, 
			$media_layanan, 
			$keperluan, 
			$rincian_keperluan,
			$nomor_antrian, 
			$nip_petugas,
			$nama_petugas
		);

		if ($stmt->execute()) {
			return $stmt->affected_rows;
		} else {
			echo "Execute failed: " . $stmt->error;
			return 0;
		}
	}
	

	function penilaian($rate) {
		global $koneksi;
		$nama = htmlspecialchars($rate["nama"]);
		$kebutuhan = htmlspecialchars($rate["kebutuhan"]);
		$nilai = htmlspecialchars($rate["kepuasan"]);
		$pesan = htmlspecialchars($rate["pesan"]);
		$nip_petugas = htmlspecialchars($rate["nip_petugas"]);
		$nama_petugas = htmlspecialchars($rate["nama_petugas"]);

		$query = "INSERT INTO penilaian
					VALUES
				('', '$nama', '$kebutuhan', '$nilai', '$pesan', '$nip_petugas', '$nama_petugas', NULL)";
		mysqli_query($koneksi, $query);
		return mysqli_affected_rows($koneksi);
	}

	function edit($ubah) {
		global $koneksi;
		mysqli_query($koneksi, "SET SESSION innodb_lock_wait_timeout = 5");
	
		// Cek apakah semua key tersedia di $ubah
		$required_keys = ['id', 'nama', 'jenis_kelamin', 'instansi', 'email', 'no_hp', 'media_layanan', 'keperluan', 'rincian_keperluan'];
		foreach ($required_keys as $key) {
			if (!isset($ubah[$key])) {
				return 0; // atau bisa return false untuk menunjukkan data tidak lengkap
			}
		}
	
		// Aman: semua key ada
		$id = (int)$ubah['id'];
		$nama = htmlspecialchars($ubah['nama']);
		$jenis_kelamin = htmlspecialchars($ubah['jenis_kelamin']);
		$instansi = htmlspecialchars($ubah['instansi']);
		$email = htmlspecialchars($ubah['email']);
		$no_hp = htmlspecialchars($ubah['no_hp']);
		$media_layanan_baru = htmlspecialchars($ubah['media_layanan']);
		$keperluan = htmlspecialchars($ubah['keperluan']);
		$rincian_keperluan = htmlspecialchars($ubah['rincian_keperluan']);

		// Ambil media_layanan lama dari database
		$result = mysqli_query($koneksi, "SELECT media_layanan FROM pengunjung WHERE user_id = $id");
		if (!$result || mysqli_num_rows($result) === 0) {
			return 0; // data tidak ditemukan
		}
		$row = mysqli_fetch_assoc($result);
		$media_layanan_lama = $row['media_layanan'];
		// Tentukan nilai no_antrian
		if ($media_layanan_lama === 'Kunjungan Langsung' && $media_layanan_baru === 'Kunjungan Melalui Whatsapp') {
			$nomor_antrian = null;
		} elseif ($media_layanan_lama === 'Kunjungan Melalui Whatsapp' && $media_layanan_baru === 'Kunjungan Langsung') {
			// Ambil antrian terakhir
			$q = mysqli_query($koneksi, "SELECT MAX(nomor_antrian) as last FROM pengunjung WHERE media_layanan = 'Kunjungan Langsung'");
			$last = mysqli_fetch_assoc($q)['last'] ?? 0;
			$nomor_antrian = $last + 1;
		} else {
			// Tidak berubah atau tidak perlu ubah
			$q = mysqli_query($koneksi, "SELECT nomor_antrian FROM pengunjung WHERE user_id = $id");
			$nomor_antrian = mysqli_fetch_assoc($q)['nomor_antrian'];
		}
		
		if ($nomor_antrian === null) {
			$stmt = $koneksi->prepare("UPDATE pengunjung SET 
				nama = ?, 
				jenis_kelamin = ?, 
				instansi = ?, 
				email = ?, 
				no_hp = ?, 
				media_layanan = ?, 
				keperluan = ?, 
				rincian_keperluan = ?, 
				nomor_antrian = NULL 
				WHERE user_id = ?");

			$stmt->bind_param("ssssssssi", 
				$nama, $jenis_kelamin, $instansi, 
				$email, $no_hp, $media_layanan_baru, $keperluan, $rincian_keperluan, $id);

		} else {
			$stmt = $koneksi->prepare("UPDATE pengunjung SET 
				nama = ?, 
				jenis_kelamin = ?, 
				instansi = ?, 
				email = ?, 
				no_hp = ?, 
				media_layanan = ?, 
				keperluan = ?, 
				rincian_keperluan = ?, 
				nomor_antrian = ? 
				WHERE user_id = ?");

			$stmt->bind_param("ssssssssii", 
				$nama, $jenis_kelamin, $instansi, 
				$email, $no_hp, $media_layanan_baru, $keperluan, $rincian_keperluan, $nomor_antrian, $id);
		}
		return $stmt->execute() ? $stmt->affected_rows : 0;
	}
	
	

	function hapus($id) {
		global $koneksi; 
	
		$query = "DELETE FROM pengunjung WHERE user_id = $id"; 
		if (mysqli_query($koneksi, $query)) {
			return mysqli_affected_rows($koneksi);
		} else {
			return 0; 
		}
	}

	function cari($keyword) {
		global $koneksi;
		$keyword = mysqli_real_escape_string($koneksi, $keyword);
		$query = "SELECT * FROM pengunjung 
					WHERE 
				nama LIKE '%$keyword%' OR
				jenis_kelamin LIKE '%$keyword%' OR
				instansi LIKE '%$keyword%' OR
				email LIKE '%$keyword%' OR
				no_hp LIKE '%$keyword%' OR
				media_layanan LIKE '%$keyword%' OR
				keperluan LIKE '%$keyword%'
				";
		return query($query);
	}

	function registrasi($datapetugas) {
		global $koneksi;
		$nama_petugas = strtolower(stripslashes($datapetugas["nama_petugas"]));
		$nip = strtolower(stripslashes($datapetugas["nip"]));
		$username = strtolower(stripslashes($datapetugas["username"]));
		$pass_petugas = mysqli_real_escape_string($koneksi, $datapetugas["pass_petugas"]);
		$con_pass_petugas = mysqli_real_escape_string($koneksi, $datapetugas["con_pass_petugas"]);
		$rating_rata_rata = mysqli_real_escape_string($koneksi, $datapetugas["rating_rata_rata"]);

		$result = mysqli_query($koneksi, "SELECT * FROM login WHERE username = '$username'");

		// cek username
		if(mysqli_num_rows($result) === 1 ) {
			echo "<script>
				alert('Username sudah terdaftar!')
			</script>";
			return false; 
		} 

		if($pass_petugas !== $con_pass_petugas){
			echo "<script>
					alert('Password yang dimasukkan tidak sesuai')
				</script>";
			return false;
		}


		// Enkripsi Password
		// $pass_petugas = password_hash($pass_petugas, PASSWORD_DEFAULT);

		// Masukkan ke database
		mysqli_query($koneksi, "INSERT INTO login VALUES ('$nama_petugas', '$nip', '$username', '$pass_petugas', '')");

		return mysqli_affected_rows($koneksi);
	}

	function hitungDurasiPelayanan($nip) {
		// Ambil waktu pelayanan berdasarkan NIP petugas
		$waktu_kunjungan = query("SELECT time, waktu_selesai FROM pengunjung WHERE nip_petugas = '$nip'");
		$total_durasi = 0;
		$jumlah_kunjungan = 0;

		foreach ($waktu_kunjungan as $row) {
			if (!empty($row['time']) && !empty($row['waktu_selesai'])) {
				$start = strtotime($row['time']);
				$end = strtotime($row['waktu_selesai']);

				if ($end > $start) {
					$total_durasi += ($end - $start);
					$jumlah_kunjungan++;
				}
			}
		}

		// Hitung total jam, menit, detik
		$jam = floor($total_durasi / 3600);
		$sisa = $total_durasi % 3600;
		$menit = floor($sisa / 60);
		$detik = $sisa % 60;

		// Hitung rata-rata
		if ($jumlah_kunjungan > 0) {
			$rata_rata_durasi = $total_durasi / $jumlah_kunjungan;

			$jam_rata_rata = floor($rata_rata_durasi / 3600);
			$sisa = $rata_rata_durasi % 3600;
			$menit_rata_rata = floor($sisa / 60);
			$detik_rata_rata = $sisa % 60;
		} else {
			$jam_rata_rata = 0;
			$menit_rata_rata = 0;
			$detik_rata_rata = 0;
		}

		// Return hasil dalam array
		return [
			'total_jam' => $jam,
			'total_menit' => $menit,
			'total_detik' => $detik,
			'rata_jam' => $jam_rata_rata,
			'rata_menit' => $menit_rata_rata,
			'rata_detik' => $detik_rata_rata,
			'jumlah_kunjungan' => $jumlah_kunjungan
		];
	}

	function hitungKunjunganPetugas($nip) {
		global $koneksi;

		// Hari ini
		$layanan_harian = "SELECT COUNT(*) AS jumlah_pengunjung_harian
			FROM pengunjung 
			WHERE nip_petugas = '$nip'  
			AND DATE(waktu_selesai) = CURDATE()";
		$result = mysqli_query($koneksi, $layanan_harian);
		$kunjungan_hari_ini = mysqli_fetch_assoc($result);

		// Bulan ini
		$layanan_bulanan = "SELECT COUNT(*) AS jumlah_pengunjung_bulan 
			FROM pengunjung 
			WHERE nip_petugas = '$nip' 
			AND YEAR(waktu_selesai) = YEAR(CURDATE()) 
			AND MONTH(waktu_selesai) = MONTH(CURDATE())";
		$result = mysqli_query($koneksi, $layanan_bulanan);
		$kunjungan_bulan_ini = mysqli_fetch_assoc($result);

		// Total
		$layanan_total = "SELECT COUNT(*) AS jumlah_pengunjung_total
			FROM pengunjung 
			WHERE nip_petugas = '$nip'";
		$result = mysqli_query($koneksi, $layanan_total);
		$kunjungan_total = mysqli_fetch_assoc($result);

		return [
			'harian' => $kunjungan_hari_ini['jumlah_pengunjung_harian'],
			'bulanan' => $kunjungan_bulan_ini['jumlah_pengunjung_bulan'],
			'total' => $kunjungan_total['jumlah_pengunjung_total'],
		];
	}

	function getFeedbackPetugas($nip, $limit = 5) {
		global $koneksi;

		$query = "SELECT p.nama, p.kepuasan, p.pesan, p.nip_petugas
				FROM penilaian p
				WHERE nip_petugas = '$nip'
				ORDER BY time DESC
				LIMIT $limit";

		$result = mysqli_query($koneksi, $query);
		$feedback = [];

		while ($row = mysqli_fetch_assoc($result)) {
			$feedback[] = $row;
		}

		return $feedback;
	}
?>