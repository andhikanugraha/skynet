<?php

class ApplicantDetail extends HeliumRecord {

	public $id;
	public $applicant_id;

	// the form fields
	public $nama_lengkap = '';
	public $alamat_lengkap = array();
	public $ttl = array();
	public $jenis_kelamin = '';
	public $tinggi_badan = '';
	public $berat_badan = '';
	public $golongan_darah = '';
	public $kewarganegaraan = '';
	public $agama = '';
	public $program_afs = 'afs';
	public $program_yes = '';
	public $program_jenesys = '';
	public $nama_lengkap_ayah = '';
	public $pendidikan_terakhir_ayah = '';
	public $pekerjaan_ayah = '';
	public $pangkat_ayah = '';
	public $nama_kantor_ayah = '';
	public $alamat_kantor_ayah = array();
	public $nama_lengkap_ibu = '';
	public $pendidikan_terakhir_ibu = '';
	public $pekerjaan_ibu = '';
	public $pangkat_ibu = '';
	public $nama_kantor_ibu = '';
	public $alamat_kantor_ibu = array();
	public $nama_lengkap_wali = '';
	public $hubungan_dengan_wali = '';
	public $pendidikan_terakhir_wali = '';
	public $alamat_lengkap_wali = array();
	public $pekerjaan_wali = '';
	public $pangkat_wali = '';
	public $nama_kantor_wali = '';
	public $alamat_kantor_wali = array();
	public $jumlah_anak_dalam_keluarga = '';
	public $anak_nomor = '';
	public $saudara = array();
	public $pendidikan_sd_nama_sekolah = '';
	public $pendidikan_sd_tahun_ijazah = '';
	public $pendidikan_sd_prestasi = array();
	public $pendidikan_smp_nama_sekolah = '';
	public $pendidikan_smp_tahun_ijazah = '';
	public $pendidikan_smp_prestasi = array();
	public $pendidikan_sma_nama_sekolah = '';
	public $pesantren = '';
	public $pendidikan_sma_alamat_sekolah = array();
	public $pendidikan_sma_nama_kepala_sekolah = '';
	public $pendidikan_sma_tahun_masuk = '';
	public $akselerasi = '';
	public $pendidikan_sma_bulan_keluar = '';
	public $pendidikan_sma_tahun_keluar = '';
	public $pendidikan_sma_prestasi = array();
	public $pengetahuan_bahasa_inggris_berapa_lama = '';
	public $pengetahuan_bahasa_lain_apa = '';
	public $pengetahuan_bahasa_lain_berapa_lama = '';
	public $mata_pelajaran_favorit = '';
	public $organisasi = array();
	public $kesenian_sekedar_hobi = '';
	public $kesenian_ikut_perkumpulan = '';
	public $kesenian_prestasi = array();
	public $olahraga_sekedar_hobi = '';
	public $olahraga_ikut_perkumpulan = '';
	public $olahraga_prestasi = array();
	public $kegiatan_lain_lain = array();
	public $pengalaman_kerja = array();
	public $tujuan_pergi_jangka_pendek = '';
	public $kapan_pergi_jangka_pendek = '';
	public $rangka_pergi_jangka_pendek = '';
	public $tujuan_pergi_jangka_panjang = '';
	public $kapan_pergi_jangka_panjang = '';
	public $rangka_pergi_jangka_panjang = '';
	public $kegiatan_pergi_jangka_panjang = '';
	public $nama_relasi_pernah_ikut = '';
	public $hubungan_relasi_pernah_ikut = '';
	public $program_relasi_pernah_ikut = '';
	public $program_relasi_pernah_ikut_jenisnya = '';
	public $tujuan_relasi_pernah_ikut = '';
	public $alamat_relasi_pernah_ikut = '';
	public $nama_kegiatan_yba_pernah_diikuti = '';
	public $tahun_kegiatan_yba_pernah_diikuti = '';
	public $referral = '';
	public $motivasi = '';
	public $harapan_ikut_binabud = '';
	public $rekomendasi_lingkungan_sekolah_nama = '';
	public $rekomendasi_lingkungan_sekolah_alamat = '';
	public $rekomendasi_lingkungan_sekolah_pekerjaan = '';
	public $rekomendasi_lingkungan_sekolah_alamat_pekerjaan = '';
	public $rekomendasi_lingkungan_sekolah_hubungan = '';
	public $rekomendasi_lingkungan_luar_sekolah_nama = '';
	public $rekomendasi_lingkungan_luar_sekolah_alamat = '';
	public $rekomendasi_lingkungan_luar_sekolah_pekerjaan = '';
	public $rekomendasi_lingkungan_luar_sekolah_alamat_pekerjaan = '';
	public $rekomendasi_lingkungan_luar_sekolah_hubungan = '';
	public $rekomendasi_teman_dekat_nama = '';
	public $rekomendasi_teman_dekat_alamat = '';
	public $rekomendasi_teman_dekat_hubungan = '';
	public $kepribadian_sifat_dan_kepribadian = '';
	public $kepribadian_kelebihan_dan_kekurangan = '';
	public $kepribadian_kondisi_membuat_tertekan = '';
	public $kepribadian_masalah_terberat = '';
	public $kepribadian_rencana = '';
	public $telkomsel_menggunakan = '';
	public $telkomsel_school_community = '';
	public $cita_cita = '';

	public function init() {
		$this->belongs_to('applicant');

		// form fields that are arrays
		$this->auto_serialize('alamat_lengkap');
		$this->auto_serialize('ttl');
		$this->auto_serialize('alamat_kantor_ayah');
		$this->auto_serialize('alamat_kantor_ibu');
		$this->auto_serialize('alamat_lengkap_wali');
		$this->auto_serialize('alamat_kantor_wali');
		$this->auto_serialize('saudara');
		$this->auto_serialize('pendidikan_sd_prestasi');
		$this->auto_serialize('pendidikan_smp_prestasi');
		$this->auto_serialize('pendidikan_sma_alamat_sekolah');
		$this->auto_serialize('pendidikan_sma_prestasi');
		$this->auto_serialize('organisasi');
		$this->auto_serialize('kesenian_prestasi');
		$this->auto_serialize('olahraga_prestasi');
		$this->auto_serialize('kegiatan_lain_lain');
		$this->auto_serialize('pengalaman_kerja');
	}

	public function absorb($post = array()) {
		if (!$post)
			$post = $_POST;

		unset($post['id'], $post['applicant_id']);

		$columns = $this->_columns();
		foreach ($columns as $col) {
			if (!in_array($col, array('id', 'applicant_id')))
				$this->$col = $post[$col];
		}

		$this->save();
	}

	public function form_fields() {
		$vars = $this->_columns();
		$exclude = array('id', 'applicant_id');
		$return = array();
		foreach ($vars as $var) {
			if (!in_array($var, $exclude))
				$return[$var] = $this->$var;
		}

		return $return;
	}
	
	public static function sanitize_name($name) {
		$name = strtolower($name);
		$name = ucwords($name);

		foreach (array('-', ' \'', 'O\'') as $delimiter) {
	    	if (strpos($name, $delimiter)!==false) {
	    		$name = implode($delimiter, array_map('ucfirst', explode($delimiter, $name)));
	    	}
	    }

		return $name;
	}
	
	public function sanitized_name() {
		return self::sanitize_name($this->nama_lengkap);
	}
	
	public function sanitized_school() {
		return Gatotkaca::sanitize_school($this->pendidikan_sma_nama_sekolah);
	}
}