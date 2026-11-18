package com.example.aplikasibarang

import android.content.Intent
import android.os.Bundle
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
import com.example.aplikasibarang.model.LoginRequest
import com.example.aplikasibarang.model.LoginResponse
import com.example.aplikasibarang.network.ApiClient
import org.json.JSONObject
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class LoginActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        ApiClient.init(this)

        val sessionManager = SessionManager(this)
        if (sessionManager.isLoggedIn()) {
            startActivity(Intent(this, DashboardActivity::class.java))
            finish()
            return
        }

        enableEdgeToEdge()
        setContentView(R.layout.activity_login)
        WindowCompat.getInsetsController(window, window.decorView)
            .isAppearanceLightStatusBars = false

        val rootView = findViewById<View>(R.id.main)
        val headerLogin = findViewById<View>(R.id.headerLogin)
        val baseHeaderTop = headerLogin.paddingTop
        val baseHeaderBottom = headerLogin.paddingBottom
        val baseHeaderStart = headerLogin.paddingStart
        val baseHeaderEnd = headerLogin.paddingEnd

        ViewCompat.setOnApplyWindowInsetsListener(rootView) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            val ime = insets.getInsets(WindowInsetsCompat.Type.ime())
            headerLogin.setPaddingRelative(
                baseHeaderStart,
                baseHeaderTop + systemBars.top,
                baseHeaderEnd,
                baseHeaderBottom
            )
            v.setPadding(
                systemBars.left,
                0,
                systemBars.right,
                maxOf(systemBars.bottom, ime.bottom)
            )
            insets
        }

        val edtUsername = findViewById<EditText>(R.id.edtUsername)
        val edtPassword = findViewById<EditText>(R.id.edtPassword)
        val btnLogin = findViewById<Button>(R.id.btnLogin)
        val txtPesan = findViewById<TextView>(R.id.txtPesan)
        val progressLogin = findViewById<ProgressBar>(R.id.progressLogin)
        val btnAturServer = findViewById<View>(R.id.btnAturServer)
        val txtServerAktif = findViewById<TextView>(R.id.txtServerAktif)

        txtServerAktif.text = ApiClient.getBaseUrl()

        btnAturServer.setOnClickListener {
            ServerConfigDialog.show(this) { updatedUrl ->
                txtServerAktif.text = updatedUrl
                txtPesan.visibility = View.GONE
            }
        }

        fun tampilkanError(pesan: String) {
            txtPesan.text = pesan
            txtPesan.visibility = View.VISIBLE
        }

        btnLogin.setOnClickListener {
            val username = edtUsername.text.toString().trim()
            val password = edtPassword.text.toString()

            if (username.isEmpty() || password.isEmpty()) {
                tampilkanError("Username dan password wajib diisi.")
                return@setOnClickListener
            }

            txtPesan.visibility = View.GONE
            progressLogin.visibility = View.VISIBLE
            btnLogin.isEnabled = false

            ApiClient.service.login(LoginRequest(username, password))
                .enqueue(object : Callback<LoginResponse> {
                    override fun onResponse(
                        call: Call<LoginResponse>,
                        response: Response<LoginResponse>
                    ) {
                        progressLogin.visibility = View.GONE
                        btnLogin.isEnabled = true

                        val result = response.body()
                        if (response.isSuccessful && result?.status == true) {
                            val token = result.data?.token.orEmpty()
                            if (token.isBlank()) {
                                tampilkanError("Respons login tidak menyertakan token sesi.")
                                return
                            }

                            sessionManager.saveSession(
                                token = token,
                                username = result.data?.username.orEmpty(),
                                nama = result.data?.nama.orEmpty(),
                                role = result.data?.role.orEmpty()
                            )
                            startActivity(Intent(this@LoginActivity, DashboardActivity::class.java))
                            finish()
                        } else if (!response.isSuccessful) {
                            val serverMessage = runCatching {
                                response.errorBody()?.string()?.let { body ->
                                    JSONObject(body).optString("message")
                                        .takeIf { it.isNotBlank() }
                                }
                            }.getOrNull()

                            val fallbackMsg = when (response.code()) {
                                400 -> "Data login tidak lengkap atau tidak valid."
                                401 -> "Username atau password salah."
                                else -> "Terjadi kendala pada server (HTTP ${response.code()})."
                            }
                            tampilkanError(serverMessage ?: fallbackMsg)
                        } else if (result == null) {
                            tampilkanError("Respons server kosong atau format tidak sesuai.")
                        } else {
                            tampilkanError(
                                result.message.ifBlank {
                                    "Login ditolak. Periksa kembali username dan password."
                                }
                            )
                        }
                    }

                    override fun onFailure(call: Call<LoginResponse>, t: Throwable) {
                        progressLogin.visibility = View.GONE
                        btnLogin.isEnabled = true
                        tampilkanError(
                            "Tidak dapat terhubung ke server (${ApiClient.getBaseUrl()}). " +
                                "Pastikan server aktif & berada di jaringan Wi-Fi yang sama."
                        )
                    }
                })
        }
    }
}
