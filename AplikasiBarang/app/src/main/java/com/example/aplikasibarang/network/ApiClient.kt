package com.example.aplikasibarang.network

import android.content.Context
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

object ApiClient {

    // ─────────────────────────────────────────────────────────────
    // KONFIGURASI ALAMAT SERVER DEFAULT
    // • Perangkat Fisik (Wi-Fi sama) : gunakan IPv4 Laptop (cek via `ipconfig`)
    // • Emulator Android Studio      : gunakan "http://10.0.2.2:8080/"
    // Anda juga dapat mengubah IP langsung dari menu "Pengaturan Server" di aplikasi!
    // ─────────────────────────────────────────────────────────────
    const val DEFAULT_BASE_URL = "http://192.168.1.221:8080/"
    const val EMULATOR_BASE_URL = "http://10.0.2.2:8080/"

    private const val PREFS_NAME = "server_config"
    private const val KEY_CUSTOM_URL = "custom_base_url"
    private const val KEY_LAST_DEFAULT = "last_code_default_url"

    @Volatile
    private var currentBaseUrl: String = DEFAULT_BASE_URL

    @Volatile
    private var apiServiceInstance: ApiService? = null

    val service: ApiService
        get() = apiServiceInstance ?: synchronized(this) {
            apiServiceInstance ?: buildService(currentBaseUrl).also {
                apiServiceInstance = it
            }
        }

    fun init(context: Context) {
        val prefs = context.applicationContext.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
        val lastCodeDefault = prefs.getString(KEY_LAST_DEFAULT, null)
        val savedCustom = prefs.getString(KEY_CUSTOM_URL, null)

        val resolvedUrl = if (lastCodeDefault != DEFAULT_BASE_URL) {
            // Jika konstanta DEFAULT_BASE_URL di kode baru saja diubah developer, gunakan nilai terbaru
            prefs.edit()
                .putString(KEY_LAST_DEFAULT, DEFAULT_BASE_URL)
                .remove(KEY_CUSTOM_URL)
                .apply()
            DEFAULT_BASE_URL
        } else {
            savedCustom?.takeIf { it.isNotBlank() } ?: DEFAULT_BASE_URL
        }

        if (resolvedUrl != currentBaseUrl || apiServiceInstance == null) {
            runCatching {
                val built = buildService(resolvedUrl)
                currentBaseUrl = resolvedUrl
                apiServiceInstance = built
            }.onFailure {
                currentBaseUrl = DEFAULT_BASE_URL
                apiServiceInstance = buildService(DEFAULT_BASE_URL)
            }
        }
    }

    fun getBaseUrl(): String = currentBaseUrl

    fun setBaseUrl(context: Context, rawInput: String): Result<String> {
        val trimmed = rawInput.trim()
        if (trimmed.isEmpty()) {
            return Result.failure(IllegalArgumentException("Alamat server tidak boleh kosong."))
        }

        val withScheme = if (trimmed.startsWith("http://", ignoreCase = true) ||
            trimmed.startsWith("https://", ignoreCase = true)
        ) {
            trimmed
        } else {
            "http://$trimmed"
        }

        val normalized = if (withScheme.endsWith("/")) withScheme else "$withScheme/"

        return runCatching {
            val newService = buildService(normalized)
            val prefs = context.applicationContext.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
            prefs.edit()
                .putString(KEY_CUSTOM_URL, normalized)
                .putString(KEY_LAST_DEFAULT, DEFAULT_BASE_URL)
                .apply()
            currentBaseUrl = normalized
            apiServiceInstance = newService
            normalized
        }
    }

    fun resetBaseUrl(context: Context): String {
        val prefs = context.applicationContext.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
        prefs.edit()
            .remove(KEY_CUSTOM_URL)
            .putString(KEY_LAST_DEFAULT, DEFAULT_BASE_URL)
            .apply()
        currentBaseUrl = DEFAULT_BASE_URL
        apiServiceInstance = buildService(DEFAULT_BASE_URL)
        return DEFAULT_BASE_URL
    }

    private fun buildService(baseUrl: String): ApiService {
        return Retrofit.Builder()
            .baseUrl(baseUrl)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
            .create(ApiService::class.java)
    }
}
