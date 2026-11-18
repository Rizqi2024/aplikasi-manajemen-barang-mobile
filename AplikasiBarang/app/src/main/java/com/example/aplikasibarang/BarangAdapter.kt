package com.example.aplikasibarang

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.RecyclerView
import com.example.aplikasibarang.model.Barang
import java.text.NumberFormat
import java.util.Locale

class BarangAdapter(
    private var daftarBarang: List<Barang>
) : RecyclerView.Adapter<BarangAdapter.BarangViewHolder>() {

    class BarangViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val txtNama: TextView = itemView.findViewById(R.id.txtNamaBarang)
        val txtKode: TextView = itemView.findViewById(R.id.txtKodeBarang)
        val txtKategori: TextView = itemView.findViewById(R.id.txtKategoriBarang)
        val txtHarga: TextView = itemView.findViewById(R.id.txtHargaBarang)
        val txtStok: TextView = itemView.findViewById(R.id.txtStokBarang)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): BarangViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_barang, parent, false)
        return BarangViewHolder(view)
    }

    override fun onBindViewHolder(holder: BarangViewHolder, position: Int) {
        val barang = daftarBarang[position]
        val context = holder.itemView.context

        holder.txtNama.text = barang.nama_barang
        holder.txtKode.text = barang.kode_barang
        holder.txtKategori.text = barang.kategori?.takeIf { it.isNotBlank() } ?: "Umum"

        val formatter = NumberFormat.getCurrencyInstance(Locale.forLanguageTag("id-ID")).apply {
            maximumFractionDigits = 0
        }
        holder.txtHarga.text = formatter.format(barang.harga)

        when {
            barang.stok <= 0 -> {
                holder.txtStok.text = "Stok Habis (0)"
                holder.txtStok.setBackgroundResource(R.drawable.bg_stock_empty)
                holder.txtStok.setTextColor(ContextCompat.getColor(context, R.color.danger_text))
            }
            barang.stok <= 5 -> {
                holder.txtStok.text = "Stok Tipis: ${barang.stok}"
                holder.txtStok.setBackgroundResource(R.drawable.bg_stock_low)
                holder.txtStok.setTextColor(ContextCompat.getColor(context, R.color.warning_text))
            }
            else -> {
                holder.txtStok.text = "Stok: ${barang.stok}"
                holder.txtStok.setBackgroundResource(R.drawable.bg_stock_ok)
                holder.txtStok.setTextColor(ContextCompat.getColor(context, R.color.success_text))
            }
        }
    }

    override fun getItemCount(): Int = daftarBarang.size

    fun updateData(dataBaru: List<Barang>) {
        daftarBarang = dataBaru
        notifyDataSetChanged()
    }
}
