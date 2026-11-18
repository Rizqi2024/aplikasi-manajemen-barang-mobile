package com.example.aplikasibarang

import android.content.Context
import android.content.SharedPreferences

class SessionManager(context: Context) {
    private val prefs: SharedPreferences =
        context.getSharedPreferences("app_session", Context.MODE_PRIVATE)

    companion object {
        const val KEY_TOKEN = "token"
        const val KEY_USERNAME = "username"
        const val KEY_NAMA = "nama"
        const val KEY_ROLE = "role"
        const val KEY_IS_LOGGED_IN = "is_logged_in"
    }

    fun saveSession(token: String, username: String, nama: String, role: String) {
        prefs.edit().apply {
            putString(KEY_TOKEN, token)
            putString(KEY_USERNAME, username)
            putString(KEY_NAMA, nama)
            putString(KEY_ROLE, role)
            putBoolean(KEY_IS_LOGGED_IN, token.isNotBlank())
            apply()
        }
    }

    fun getToken(): String = prefs.getString(KEY_TOKEN, "") ?: ""
    fun getNama(): String = prefs.getString(KEY_NAMA, "") ?: ""
    fun getUsername(): String = prefs.getString(KEY_USERNAME, "") ?: ""
    fun getRole(): String = prefs.getString(KEY_ROLE, "user") ?: "user"

    fun isLoggedIn(): Boolean =
        prefs.getBoolean(KEY_IS_LOGGED_IN, false) && getToken().isNotBlank()

    fun clearSession() {
        prefs.edit().clear().apply()
    }
}
