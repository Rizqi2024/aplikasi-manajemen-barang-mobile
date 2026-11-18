package com.example.aplikasibarang

import android.content.Context
import android.text.InputType
import android.widget.EditText
import android.widget.LinearLayout
import android.widget.TextView
import android.widget.Toast
import com.example.aplikasibarang.network.ApiClient
import com.google.android.material.dialog.MaterialAlertDialogBuilder

object ServerConfigDialog {

    fun show(context: Context, onUpdated: (String) -> Unit) {
        val density = context.resources.displayMetrics.density
        val padH = (22 * density).toInt()
        val padTop = (12 * density).toInt()

        val container = LinearLayout(context).apply {
            orientation = LinearLayout.VERTICAL
            setPadding(padH, padTop, padH, 0)
        }

        val helperText = TextView(context).apply {
            text = "• HP Fisik: http://<IP_LAPTOP>:8080/\n• Emulator: ${ApiClient.EMULATOR_BASE_URL}\nPastikan menjalankan: php spark serve --host 0.0.0.0 --port 8080"
            textSize = 12f
            setPadding(0, 0, 0, (12 * density).toInt())
        }

        val input = EditText(context).apply {
            inputType = InputType.TYPE_CLASS_TEXT or InputType.TYPE_TEXT_VARIATION_URI
            setText(ApiClient.getBaseUrl())
            setSelection(text.length)
            hint = "Contoh: http://192.168.1.221:8080/"
            isSingleLine = true
        }

        container.addView(helperText)
        container.addView(input)

        MaterialAlertDialogBuilder(context)
            .setTitle("Pengaturan Server API")
            .setView(container)
            .setPositiveButton("Simpan") { _, _ ->
                val result = ApiClient.setBaseUrl(context, input.text.toString())
                result.onSuccess { savedUrl ->
                    Toast.makeText(context, "Server diatur ke: $savedUrl", Toast.LENGTH_SHORT).show()
                    onUpdated(savedUrl)
                }.onFailure { err ->
                    Toast.makeText(
                        context,
                        "Format URL tidak valid: ${err.localizedMessage ?: "periksa kembali"}",
                        Toast.LENGTH_LONG
                    ).show()
                }
            }
            .setNeutralButton("Emulator (10.0.2.2)") { _, _ ->
                ApiClient.setBaseUrl(context, ApiClient.EMULATOR_BASE_URL).onSuccess { savedUrl ->
                    Toast.makeText(context, "Mode Emulator aktif: $savedUrl", Toast.LENGTH_SHORT).show()
                    onUpdated(savedUrl)
                }
            }
            .setNegativeButton("Reset Default") { _, _ ->
                val defaultUrl = ApiClient.resetBaseUrl(context)
                Toast.makeText(context, "Dikembalikan ke default: $defaultUrl", Toast.LENGTH_SHORT).show()
                onUpdated(defaultUrl)
            }
            .show()
    }
}
