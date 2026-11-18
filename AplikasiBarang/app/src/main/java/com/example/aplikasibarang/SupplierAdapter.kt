package com.example.aplikasibarang

import android.content.Intent
import android.net.Uri
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.core.content.ContextCompat
import androidx.recyclerview.widget.RecyclerView
import com.example.aplikasibarang.model.Supplier

class SupplierAdapter(private var items: List<Supplier>) :
    RecyclerView.Adapter<SupplierAdapter.ViewHolder>() {

    class ViewHolder(view: View) : RecyclerView.ViewHolder(view) {
        val txtNamaSupplier: TextView = view.findViewById(R.id.txtNamaSupplier)
        val txtKodeSupplier: TextView = view.findViewById(R.id.txtKodeSupplier)
        val txtAlamat: TextView = view.findViewById(R.id.txtAlamat)
        val txtNoTelp: TextView = view.findViewById(R.id.txtNoTelp)
        val txtEmail: TextView = view.findViewById(R.id.txtEmail)
        val rowTelepon: View = view.findViewById(R.id.rowTelepon)
        val rowEmail: View = view.findViewById(R.id.rowEmail)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_supplier, parent, false)
        return ViewHolder(view)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        val supplier = items[position]
        val context = holder.itemView.context

        holder.txtNamaSupplier.text = supplier.nama_supplier
        holder.txtKodeSupplier.text = supplier.kode_supplier

        val alamat = supplier.alamat?.trim()?.takeIf { it.isNotEmpty() }
        val noTelp = supplier.no_telp?.trim()?.takeIf { it.isNotEmpty() }
        val email = supplier.email?.trim()?.takeIf { it.isNotEmpty() }

        holder.txtAlamat.text = alamat ?: "-"
        holder.txtNoTelp.text = noTelp ?: "-"
        holder.txtEmail.text = email ?: "-"

        if (noTelp != null) {
            holder.txtNoTelp.setTextColor(ContextCompat.getColor(context, R.color.teal_dark))
            holder.rowTelepon.setOnClickListener {
                runCatching {
                    context.startActivity(Intent(Intent.ACTION_DIAL, Uri.parse("tel:$noTelp")))
                }
            }
        } else {
            holder.txtNoTelp.setTextColor(ContextCompat.getColor(context, R.color.text_secondary))
            holder.rowTelepon.setOnClickListener(null)
        }

        if (email != null) {
            holder.txtEmail.setTextColor(ContextCompat.getColor(context, R.color.primary))
            holder.rowEmail.setOnClickListener {
                runCatching {
                    context.startActivity(Intent(Intent.ACTION_SENDTO, Uri.parse("mailto:$email")))
                }
            }
        } else {
            holder.txtEmail.setTextColor(ContextCompat.getColor(context, R.color.text_secondary))
            holder.rowEmail.setOnClickListener(null)
        }
    }

    override fun getItemCount(): Int = items.size

    fun updateData(newItems: List<Supplier>) {
        items = newItems
        notifyDataSetChanged()
    }
}
