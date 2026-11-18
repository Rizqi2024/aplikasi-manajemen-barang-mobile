package com.example.aplikasibarang

import android.content.Intent
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.view.View
import android.widget.Button
import android.widget.EditText
import android.widget.ProgressBar
import android.widget.TextView
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowCompat
import androidx.core.view.WindowInsetsCompat
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.aplikasibarang.model.Barang
import com.example.aplikasibarang.model.BarangResponse
import com.example.aplikasibarang.network.ApiClient
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class MainActivity : AppCompatActivity() {

    private lateinit var layoutStatusBarang: View
    private lateinit var txtStatus: TextView
    private lateinit var btnRetryBarang: Button
    private lateinit var txtTotalBarang: TextView
    private lateinit var txtTotalStok: TextView
    private lateinit var edtCariBarang: EditText
    private lateinit var progressBar: ProgressBar
    private lateinit var recyclerBarang: RecyclerView
    private lateinit var barangAdapter: BarangAdapter
    private lateinit var sessionManager: SessionManager

    private var daftarSemuaBarang: List<Barang> = emptyList()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        ApiClient.init(this)

        sessionManager = SessionManager(this)
        if (!sessionManager.isLoggedIn()) {
            startActivity(Intent(this, LoginActivity::class.java))
            finish()
            return
        }

        enableEdgeToEdge()
        setContentView(R.layout.activity_main)
        WindowCompat.getInsetsController(window, window.decorView)
            .isAppearanceLightStatusBars = false

        val rootBarang = findViewById<View>(R.id.rootBarang)
        val headerBarang = findViewById<View>(R.id.headerBarang)
        val recycler = findViewById<RecyclerView>(R.id.recyclerBarang)
        val baseTop = headerBarang.paddingTop
        val baseBottom = headerBarang.paddingBottom
        val baseStart = headerBarang.paddingStart
        val baseEnd = headerBarang.paddingEnd
        val baseListBottom = recycler.paddingBottom

        ViewCompat.setOnApplyWindowInsetsListener(rootBarang) { v, insets ->
            val bars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            headerBarang.setPaddingRelative(baseStart, baseTop + bars.top, baseEnd, baseBottom)
            v.setPadding(bars.left, 0, bars.right, 0)
            recycler.setPadding(
                recycler.paddingLeft,
                recycler.paddingTop,
                recycler.paddingRight,
                baseListBottom + bars.bottom
            )
            insets
        }

        // Navigasi Header
        findViewById<View>(R.id.btnBackBarang).setOnClickListener {
            onBackPressedDispatcher.onBackPressed()
        }
        findViewById<View>(R.id.btnRefreshBarang).setOnClickListener {
            muatBarang()
        }

        // Komponen UI
        layoutStatusBarang = findViewById(R.id.layoutStatusBarang)
        txtStatus = findViewById(R.id.txtStatus)
        btnRetryBarang = findViewById(R.id.btnRetryBarang)
        txtTotalBarang = findViewById(R.id.txtTotalBarang)
        txtTotalStok = findViewById(R.id.txtTotalStok)
        edtCariBarang = findViewById(R.id.edtCariBarang)
        progressBar = findViewById(R.id.progressBar)
        recyclerBarang = recycler

        btnRetryBarang.setOnClickListener {
            muatBarang()
        }

        recyclerBarang.layoutManager = LinearLayoutManager(this)
        barangAdapter = BarangAdapter(emptyList())
        recyclerBarang.adapter = barangAdapter

        edtCariBarang.addTextChangedListener(object : TextWatcher {
            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) = Unit
            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {
                terapkanFilter(s?.toString().orEmpty())
            }
            override fun afterTextChanged(s: Editable?) = Unit
        })

        muatBarang()
    }

    private fun muatBarang() {
        layoutStatusBarang.visibility = View.VISIBLE
        progressBar.visibility = View.VISIBLE
        btnRetryBarang.visibility = View.GONE
        txtStatus.text = "Memuat data barang dari server..."

        ApiClient.service.getBarang()
            .enqueue(object : Callback<BarangResponse> {
                override fun onResponse(
                    call: Call<BarangResponse>,
                    response: Response<BarangResponse>
                ) {
                    progressBar.visibility = View.GONE
                    if (response.isSuccessful) {
                        daftarSemuaBarang = response.body()?.data.orEmpty()
                        val totalStok = daftarSemuaBarang.sumOf { it.stok }

                        txtTotalBarang.text = "${daftarSemuaBarang.size} Barang"
                        txtTotalStok.text = "$totalStok Unit"

                        terapkanFilter(edtCariBarang.text?.toString().orEmpty())
                    } else {
                        layoutStatusBarang.visibility = View.VISIBLE
                        btnRetryBarang.visibility = View.VISIBLE
                        txtStatus.text = "Gagal memuat barang (HTTP ${response.code()})."
                    }
                }

                override fun onFailure(call: Call<BarangResponse>, t: Throwable) {
                    progressBar.visibility = View.GONE
                    layoutStatusBarang.visibility = View.VISIBLE
                    btnRetryBarang.visibility = View.VISIBLE
                    txtStatus.text =
                        "Tidak dapat terhubung ke server (${ApiClient.getBaseUrl()}).\nPastikan backend sedang berjalan."
                }
            })
    }

    private fun terapkanFilter(kataKunci: String) {
        val query = kataKunci.trim()
        val hasil = if (query.isEmpty()) {
            daftarSemuaBarang
        } else {
            daftarSemuaBarang.filter { item ->
                item.nama_barang.contains(query, ignoreCase = true) ||
                    item.kode_barang.contains(query, ignoreCase = true) ||
                    (item.kategori?.contains(query, ignoreCase = true) == true)
            }
        }

        barangAdapter.updateData(hasil)

        if (daftarSemuaBarang.isEmpty()) {
            layoutStatusBarang.visibility = View.VISIBLE
            btnRetryBarang.visibility = View.GONE
            txtStatus.text = "Belum ada data barang di dalam database."
        } else if (hasil.isEmpty()) {
            layoutStatusBarang.visibility = View.VISIBLE
            btnRetryBarang.visibility = View.GONE
            txtStatus.text = "Tidak ditemukan barang dengan kata kunci \"$query\"."
        } else {
            layoutStatusBarang.visibility = View.GONE
        }
    }
}
