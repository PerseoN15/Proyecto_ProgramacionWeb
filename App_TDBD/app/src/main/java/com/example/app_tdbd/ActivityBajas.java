package com.example.app_tdbd;

import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

import com.example.app_tdbd.api.ApiService;
import com.example.app_tdbd.api.RetrofitClient;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ActivityBajas extends AppCompatActivity {

    private RecyclerView recyclerView;
    private Adaptador adaptador;
    private List<ApiService.AlumnoResponse> listaAlumnos = new ArrayList<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_bajas);

        recyclerView = findViewById(R.id.recyclerView_bajas); // Corregir el ID
        recyclerView.setLayoutManager(new LinearLayoutManager(this));

        adaptador = new Adaptador(this, listaAlumnos);
        recyclerView.setAdapter(adaptador);

        cargarAlumnos();
    }

    private void cargarAlumnos() {
        ApiService apiService = RetrofitClient.getClient().create(ApiService.class);
        Call<List<ApiService.AlumnoResponse>> call = apiService.obtenerAlumnos();

        call.enqueue(new Callback<List<ApiService.AlumnoResponse>>() {
            @Override
            public void onResponse(Call<List<ApiService.AlumnoResponse>> call, Response<List<ApiService.AlumnoResponse>> response) {
                if (response.isSuccessful() && response.body() != null) {
                    listaAlumnos.clear();
                    listaAlumnos.addAll(response.body());
                    adaptador.notifyDataSetChanged();
                } else {
                    Toast.makeText(getApplicationContext(), "Error al cargar los alumnos.", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<List<ApiService.AlumnoResponse>> call, Throwable t) {
                Toast.makeText(getApplicationContext(), "Error al conectar con el servidor.", Toast.LENGTH_SHORT).show();
                Log.e("BAJAS", "Error: ", t);
            }
        });
    }
}
