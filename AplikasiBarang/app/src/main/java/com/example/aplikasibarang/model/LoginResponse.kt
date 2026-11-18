package com.example.aplikasibarang.model

data class LoginRequest(
    val username: String,
    val password: String
)

data class UserLogin(
    val id: Int,
    val nama: String,
    val username: String,
    val role: String,
    val token: String?
)

data class LoginResponse(
    val status: Boolean,
    val message: String,
    val data: UserLogin?
)
