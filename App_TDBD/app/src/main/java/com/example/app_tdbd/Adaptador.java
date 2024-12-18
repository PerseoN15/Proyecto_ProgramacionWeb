package com.example.app_tdbd;

import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.app_tdbd.api.ApiService;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class Adaptador extends RecyclerView.Adapter<Adaptador.ViewHolder> {

    private List<ApiService.AlumnoResponse> alumnos = new ArrayList<>();
    private final Context context;
    private OnItemLongClickListener longClickListener;

    public Adaptador(Context context, List<ApiService.AlumnoResponse> alumnos) {
        this.context = context;
        if (alumnos != null) {
            this.alumnos = alumnos;
        }
    }

    // Interfaz para clics largos
    public interface OnItemLongClickListener {
        void onItemLongClick(ApiService.AlumnoResponse alumno);
    }

    public void setOnItemLongClickListener(OnItemLongClickListener listener) {
        this.longClickListener = listener;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.text_row_item, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        ApiService.AlumnoResponse alumno = alumnos.get(position);
        holder.tvNumeroControl.setText("Número de Control: " + alumno.numeroControl);
        holder.tvNombre.setText("Nombre: " + alumno.nombreCompleto);
        holder.tvCarrera.setText("Carrera: " + alumno.carrera);
        holder.tvSemestre.setText("Semestre: " + alumno.semestre);

        // Formatear la fecha de nacimiento
        String fechaFormateada = formatearFecha(alumno.fechaNacimiento);
        holder.tvFechaNacimiento.setText("Fecha de Nacimiento: " + fechaFormateada);

        // Log para depuración
        Log.d("Adaptador", "Fecha enviada a ActivityCambios: " + fechaFormateada);

        // Configurar clic simple
        holder.itemView.setOnClickListener(v -> {
            Intent intent = new Intent(context, ActivityCambios.class);
            intent.putExtra("numero_control", alumno.numeroControl);
            intent.putExtra("nombre_completo", alumno.nombreCompleto);
            intent.putExtra("carrera", alumno.carrera);
            intent.putExtra("semestre", alumno.semestre);
            intent.putExtra("fecha_nacimiento", fechaFormateada);
            context.startActivity(intent);
        });

        // Configurar clic largo
        holder.itemView.setOnLongClickListener(v -> {
            if (longClickListener != null) {
                longClickListener.onItemLongClick(alumno);
            }
            return true;
        });
    }

    @Override
    public int getItemCount() {
        return alumnos.size();
    }

    // Método para actualizar la lista
    public void actualizarLista(List<ApiService.AlumnoResponse> nuevaLista) {
        if (nuevaLista == null || nuevaLista.isEmpty()) {
            this.alumnos = new ArrayList<>();
            notifyDataSetChanged();
        } else {
            this.alumnos = nuevaLista;
            notifyDataSetChanged();
        }
    }

    // Método para formatear la fecha
    private String formatearFecha(String fecha) {
        try {
            SimpleDateFormat formatoOriginal = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());
            SimpleDateFormat formatoDeseado = new SimpleDateFormat("dd/MM/yyyy", Locale.getDefault());
            return formatoDeseado.format(formatoOriginal.parse(fecha));
        } catch (ParseException e) {
            e.printStackTrace();
            return fecha; // Devuelve la fecha original si ocurre un error
        }
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvNumeroControl, tvNombre, tvCarrera, tvSemestre, tvFechaNacimiento;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvNumeroControl = itemView.findViewById(R.id.tv_numeroControl);
            tvNombre = itemView.findViewById(R.id.tv_nombre);
            tvCarrera = itemView.findViewById(R.id.tv_carrera);
            tvSemestre = itemView.findViewById(R.id.tv_semestre);
            tvFechaNacimiento = itemView.findViewById(R.id.tv_edad);
        }
    }
}
