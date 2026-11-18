package com.example.aplikasibarang.network
import com.example.aplikasibarang.model.BarangResponse
import retrofit2.Call
import retrofit2.http.GET
import retrofit2.http.Body
import retrofit2.http.POST
import retrofit2.http.Header
import com.example.aplikasibarang.model.LoginResponse
import com.example.aplikasibarang.model.LoginRequest
import com.example.aplikasibarang.model.SupplierResponse

interface ApiService {

    @GET("api/barang")
    fun getBarang(): Call<BarangResponse>

    @POST("api/login")
    fun login(
        @Body request: LoginRequest
    ): Call<LoginResponse>

    @GET("api/supplier")
    fun getSupplier(
        @Header("Authorization") token: String
    ): Call<SupplierResponse>

}
