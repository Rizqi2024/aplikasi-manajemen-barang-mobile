package com.example.aplikasibarang.model

data class Barang(
    val id: Int,
    val kode_barang: String,
    val nama_barang: String,
    val kategori: String?,
    val stok: Int,
    val harga: Double
)
