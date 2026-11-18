package com.example.aplikasibarang.model

data class Supplier(
    val id: Int,
    val kode_supplier: String,
    val nama_supplier: String,
    val alamat: String?,
    val no_telp: String?,
    val email: String?
)
