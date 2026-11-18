package com.example.aplikasibarang

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.ProgressBar
import android.widget.TextView
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowCompat
import androidx.core.view.WindowInsetsCompat
import com.example.aplikasibarang.model.BarangResponse
import com.example.aplikasibarang.model.SupplierResponse
import com.example.aplikasibarang.network.ApiClient
import com.google.android.material.dialog.MaterialAlertDialogBuilder
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class DashboardActivity : AppCompatActivity() {

    private lateinit var sessionManager: SessionManager
    private lateinit var txtStatTotalBarang: TextView
    private lateinit var txtStatSubBarang: TextView
    private lateinit var txtStatTotalSupplier: TextView
    private lateinit var txtStatSubSupplier: TextView
    private lateinit var txtSyncStatus: TextView
    private lateinit var txtDashboardServer: TextView
    private lateinit var progressDashboard: ProgressBar

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        ApiClient.init(this)

        sessionManager = SessionManager(this)
        if (!sessionManager.isLoggedIn()) {
            arahkanKeLogin()
            return
        }

        enableEdgeToEdge()
        setContentView(R.layout.activity_dashboard)
        WindowCompat.getInsetsController(window, window.decorView)
            .isAppearanceLightStatusBars = false

        val rootDashboard = findViewById<View>(R.id.rootDashboard)
        val headerDashboard = findViewById<View>(R.id.headerDashboard)
        val baseTop = headerDashboard.paddingTop
        val baseBottom = headerDashboard.paddingBottom
        val baseStart = headerDashboard.paddingStart
        val baseEnd = headerDashboard.paddingEnd

        ViewCompat.setOnApplyWindowInsetsListener(rootDashboard) { v, insets ->
            val bars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            headerDashboard.setPaddingRelative(baseStart, baseTop + bars.top, baseEnd, baseBottom)
            v.setPadding(bars.left, 0, bars.right, bars.bottom)
            insets
        }

        // Inisialisasi profil pengguna
        val nama = sessionManager.getNama().ifBlank { "Pengguna" }
        val username = sessionManager.getUsername().ifBlank { "user" }
        val role = sessionManager.getRole().ifBlank { "user" }.uppercase()

        findViewById<TextView>(R.id.txtNamaUserDashboard).text = nama
        findViewById<TextView>(R.id.txtRoleBadge).text = "$role • @$username"
        findViewById<TextView>(R.id.txtAvatarInitial).text = buatInisial(nama)

        // Inisialisasi komponen statistik
        txtStatTotalBarang = findViewById(R.id.txtStatTotalBarang)
        txtStatSubBarang = findViewById(R.id.txtStatSubBarang)
        txtStatTotalSupplier = findViewById(R.id.txtStatTotalSupplier)
        txtStatSubSupplier = findViewById(R.id.txtStatSubSupplier)
        txtSyncStatus = findViewById(R.id.txtSyncStatus)
        txtDashboardServer = findViewById(R.id.txtDashboardServer)
        progressDashboard = findViewById(R.id.progressDashboard)

        txtDashboardServer.text = ApiClient.getBaseUrl()

        // Navigasi ke Data Barang
        val bukaBarang = View.OnClickListener {
            startActivity(Intent(this, MainActivity::class.java))
        }
        findViewById<View>(R.id.btnMenuBarang).setOnClickListener(bukaBarang)
        findViewById<View>(R.id.cardStatBarang).setOnClickListener(bukaBarang)

        // Navigasi ke Daftar Supplier
        val bukaSupplier = View.OnClickListener {
            startActivity(Intent(this, SupplierActivity::class.java))
        }
        findViewById<View>(R.id.btnMenuSupplier).setOnClickListener(bukaSupplier)
        findViewById<View>(R.id.cardStatSupplier).setOnClickListener(bukaSupplier)

        // Tombol Segarkan / Sinkronkan
        val aksiRefresh = View.OnClickListener {
            muatRingkasanDashboard(tampilkanNotif = true)
        }
        findViewById<View>(R.id.btnRefreshDashboard).setOnClickListener(aksiRefresh)
        findViewById<View>(R.id.btnMuatUlang).setOnClickListener(aksiRefresh)

        // Pengaturan Server
        findViewById<View>(R.id.btnPengaturanServer).setOnClickListener {
            ServerConfigDialog.show(this) { updatedUrl ->
                txtDashboardServer.text = updatedUrl
                muatRingkasanDashboard(tampilkanNotif = false)
            }
        }

        // Konfirmasi Logout
        findViewById<View>(R.id.btnLogoutDashboard).setOnClickListener {
            MaterialAlertDialogBuilder(this)
                .setTitle("Konfirmasi Keluar")
                .setMessage("Apakah Anda yakin ingin keluar dari akun @$username?")
                .setNegativeButton("Batal", null)
                .setPositiveButton("Ya, Keluar") { _, _ ->
                    sessionManager.clearSession()
                    arahkanKeLogin()
                }
                .show()
        }
    }

    override fun onResume() {
        super.onResume()
        if (!sessionManager.isLoggedIn()) {
            arahkanKeLogin()
            return
        }
        txtDashboardServer.text = ApiClient.getBaseUrl()
        muatRingkasanDashboard(tampilkanNotif = false)
    }

    private fun muatRingkasanDashboard(tampilkanNotif: Boolean) {
        val token = sessionManager.getToken()
        progressDashboard.visibility = View.VISIBLE
        txtSyncStatus.text = "Menyinkronkan data dari server..."

        var requestSelesai = 0
        var berhasilSemua = true

        fun tandaiSelesai() {
            requestSelesai++
            if (requestSelesai >= 2) {
                progressDashboard.visibility = View.GONE
                txtSyncStatus.text = if (berhasilSemua) {
                    "Data tersinkronisasi • Ketuk kartu untuk melihat detail"
                } else {
                    "Sebagian data gagal dimuat • Periksa koneksi server"
                }
                if (tampilkanNotif) {
                    val msg = if (berhasilSemua) "Ringkasan berhasil diperbarui" else "Gagal menjangkau server"
                    Toast.makeText(this, msg, Toast.LENGTH_SHORT).show()
                }
            }
        }

        ApiClient.service.getBarang().enqueue(object : Callback<BarangResponse> {
            override fun onResponse(
                call: Call<BarangResponse>,
                response: Response<BarangResponse>
            ) {
                if (response.isSuccessful) {
                    val list = response.body()?.data.orEmpty()
                    val totalStok = list.sumOf { it.stok }
                    txtStatTotalBarang.text = list.size.toString()
                    txtStatSubBarang.text = "Total stok: $totalStok unit"
                } else {
                    berhasilSemua = false
                    txtStatSubBarang.text = "HTTP ${response.code()}"
                }
                tandaiSelesai()
            }

            override fun onFailure(call: Call<BarangResponse>, t: Throwable) {
                berhasilSemua = false
                txtStatTotalBarang.text = "-"
                txtStatSubBarang.text = "Server offline"
                tandaiSelesai()
            }
        })

        ApiClient.service.getSupplier("Bearer $token").enqueue(object : Callback<SupplierResponse> {
            override fun onResponse(
                call: Call<SupplierResponse>,
                response: Response<SupplierResponse>
            ) {
                if (response.code() == 401) {
                    sessionManager.clearSession()
                    Toast.makeText(
                        this@DashboardActivity,
                        "Sesi berakhir, silakan login kembali.",
                        Toast.LENGTH_SHORT
                    ).show()
                    arahkanKeLogin()
                    return
                }

                if (response.isSuccessful) {
                    val list = response.body()?.data.orEmpty()
                    txtStatTotalSupplier.text = list.size.toString()
                    txtStatSubSupplier.text = if (list.isEmpty()) "Belum ada mitra" else "Mitra pemasok aktif"
                } else {
                    berhasilSemua = false
                    txtStatSubSupplier.text = "HTTP ${response.code()}"
                }
                tandaiSelesai()
            }

            override fun onFailure(call: Call<SupplierResponse>, t: Throwable) {
                berhasilSemua = false
                txtStatTotalSupplier.text = "-"
                txtStatSubSupplier.text = "Server offline"
                tandaiSelesai()
            }
        })
    }

    private fun buatInisial(nama: String): String {
        val parts = nama.trim().split(Regex("\\s+")).filter { it.isNotEmpty() }
        return when {
            parts.size >= 2 -> "${parts[0].first()}${parts[1].first()}".uppercase()
            parts.size == 1 -> parts[0].take(2).uppercase()
            else -> "US"
        }
    }

    private fun arahkanKeLogin() {
        val intent = Intent(this, LoginActivity::class.java).apply {
            flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
        }
        startActivity(intent)
        finish()
    }
}
