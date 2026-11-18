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
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowCompat
import androidx.core.view.WindowInsetsCompat
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.aplikasibarang.model.Supplier
import com.example.aplikasibarang.model.SupplierResponse
import com.example.aplikasibarang.network.ApiClient
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class SupplierActivity : AppCompatActivity() {

    private lateinit var layoutStatusSupplier: View
    private lateinit var progressSupplier: ProgressBar
    private lateinit var txtStatusSupplier: TextView
    private lateinit var btnRetrySupplier: Button
    private lateinit var txtTotalSupplier: TextView
    private lateinit var edtCariSupplier: EditText
    private lateinit var recyclerSupplier: RecyclerView
    private lateinit var supplierAdapter: SupplierAdapter
    private lateinit var sessionManager: SessionManager

    private var daftarSemuaSupplier: List<Supplier> = emptyList()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        ApiClient.init(this)

        sessionManager = SessionManager(this)
        if (!sessionManager.isLoggedIn()) {
            arahkanKeLogin()
            return
        }

        enableEdgeToEdge()
        setContentView(R.layout.activity_supplier)
        WindowCompat.getInsetsController(window, window.decorView)
            .isAppearanceLightStatusBars = false

        val rootSupplier = findViewById<View>(R.id.rootSupplier)
        val headerSupplier = findViewById<View>(R.id.headerSupplier)
        val recycler = findViewById<RecyclerView>(R.id.recyclerSupplier)
        val baseTop = headerSupplier.paddingTop
        val baseBottom = headerSupplier.paddingBottom
        val baseStart = headerSupplier.paddingStart
        val baseEnd = headerSupplier.paddingEnd
        val baseListBottom = recycler.paddingBottom

        ViewCompat.setOnApplyWindowInsetsListener(rootSupplier) { v, insets ->
            val bars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            headerSupplier.setPaddingRelative(baseStart, baseTop + bars.top, baseEnd, baseBottom)
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
        findViewById<View>(R.id.btnBackSupplier).setOnClickListener {
            onBackPressedDispatcher.onBackPressed()
        }
        findViewById<View>(R.id.btnRefreshSupplier).setOnClickListener {
            muatSupplier()
        }

        // Komponen UI
        layoutStatusSupplier = findViewById(R.id.layoutStatusSupplier)
        progressSupplier = findViewById(R.id.progressSupplier)
        txtStatusSupplier = findViewById(R.id.txtStatusSupplier)
        btnRetrySupplier = findViewById(R.id.btnRetrySupplier)
        txtTotalSupplier = findViewById(R.id.txtTotalSupplier)
        edtCariSupplier = findViewById(R.id.edtCariSupplier)
        recyclerSupplier = recycler

        btnRetrySupplier.setOnClickListener {
            muatSupplier()
        }

        recyclerSupplier.layoutManager = LinearLayoutManager(this)
        supplierAdapter = SupplierAdapter(emptyList())
        recyclerSupplier.adapter = supplierAdapter

        edtCariSupplier.addTextChangedListener(object : TextWatcher {
            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) = Unit
            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {
                terapkanFilter(s?.toString().orEmpty())
            }
            override fun afterTextChanged(s: Editable?) = Unit
        })

        muatSupplier()
    }

    private fun muatSupplier() {
        val token = sessionManager.getToken()
        layoutStatusSupplier.visibility = View.VISIBLE
        progressSupplier.visibility = View.VISIBLE
        btnRetrySupplier.visibility = View.GONE
        txtStatusSupplier.text = "Memuat data supplier dari server..."

        ApiClient.service.getSupplier("Bearer $token")
            .enqueue(object : Callback<SupplierResponse> {
                override fun onResponse(
                    call: Call<SupplierResponse>,
                    response: Response<SupplierResponse>
                ) {
                    progressSupplier.visibility = View.GONE

                    if (response.code() == 401) {
                        sessionManager.clearSession()
                        Toast.makeText(
                            this@SupplierActivity,
                            "Sesi tidak valid atau telah berakhir. Silakan login kembali.",
                            Toast.LENGTH_SHORT
                        ).show()
                        arahkanKeLogin()
                        return
                    }

                    if (response.isSuccessful) {
                        daftarSemuaSupplier = response.body()?.data.orEmpty()
                        txtTotalSupplier.text = "${daftarSemuaSupplier.size} Supplier Terdaftar"
                        terapkanFilter(edtCariSupplier.text?.toString().orEmpty())
                    } else {
                        layoutStatusSupplier.visibility = View.VISIBLE
                        btnRetrySupplier.visibility = View.VISIBLE
                        txtStatusSupplier.text =
                            "Gagal memuat daftar supplier (HTTP ${response.code()})."
                    }
                }

                override fun onFailure(call: Call<SupplierResponse>, t: Throwable) {
                    progressSupplier.visibility = View.GONE
                    layoutStatusSupplier.visibility = View.VISIBLE
                    btnRetrySupplier.visibility = View.VISIBLE
                    txtStatusSupplier.text =
                        "Tidak dapat terhubung ke server (${ApiClient.getBaseUrl()}).\nPastikan backend sedang berjalan."
                }
            })
    }

    private fun terapkanFilter(kataKunci: String) {
        val query = kataKunci.trim()
        val hasil = if (query.isEmpty()) {
            daftarSemuaSupplier
        } else {
            daftarSemuaSupplier.filter { item ->
                item.nama_supplier.contains(query, ignoreCase = true) ||
                    item.kode_supplier.contains(query, ignoreCase = true) ||
                    (item.alamat?.contains(query, ignoreCase = true) == true) ||
                    (item.no_telp?.contains(query, ignoreCase = true) == true) ||
                    (item.email?.contains(query, ignoreCase = true) == true)
            }
        }

        supplierAdapter.updateData(hasil)

        if (daftarSemuaSupplier.isEmpty()) {
            layoutStatusSupplier.visibility = View.VISIBLE
            btnRetrySupplier.visibility = View.GONE
            txtStatusSupplier.text = "Belum ada data supplier terdaftar di database."
        } else if (hasil.isEmpty()) {
            layoutStatusSupplier.visibility = View.VISIBLE
            btnRetrySupplier.visibility = View.GONE
            txtStatusSupplier.text = "Tidak ditemukan supplier dengan kata kunci \"$query\"."
        } else {
            layoutStatusSupplier.visibility = View.GONE
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
