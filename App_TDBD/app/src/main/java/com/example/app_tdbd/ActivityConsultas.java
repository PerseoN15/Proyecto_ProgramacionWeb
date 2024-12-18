package com.example.app_tdbd;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import com.example.app_tdbd.api.ApiService;
import com.example.app_tdbd.api.RetrofitClient;

import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ActivityConsultas extends AppCompatActivity {

    private RecyclerView recyclerView;
    private Adaptador adaptador;
    private EditText txtNumeroControl, txtNombre;
    private Spinner spinnerCarrera, spinnerSemestre;

    // Variables para guardar los filtros actuales y evitar consultas innecesarias
    private String currentNumeroControl = "";
    private String currentNombre = "";
    private String currentCarrera = "";
    private Integer currentSemestre = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_consultas);

        // Inicializar vistas
        recyclerView = findViewById(R.id.recyclerview);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
        txtNumeroControl = findViewById(R.id.txt_numeroControl_cons);
        txtNombre = findViewById(R.id.txt_nombre_cons);
        spinnerCarrera = findViewById(R.id.spinner_carrera_cons);
        spinnerSemestre = findViewById(R.id.spinner_semestre_cons);

        // Inicializar adaptador vacío
        adaptador = new Adaptador(this, null);
        recyclerView.setAdapter(adaptador);

        // Configurar eventos
        configurarEventos();

        // Configurar clic largo en los elementos
        configurarClicLargo();

        // Cargar la lista inicial de alumnos sin filtros
        cargarAlumnosFiltrados();
    }

    private void configurarEventos() {
        // Listener para cambios en los campos de texto
        txtNumeroControl.addTextChangedListener(new FiltroTextWatcher());
        txtNombre.addTextChangedListener(new FiltroTextWatcher());

        // Listener para cambios en los Spinners
        spinnerCarrera.setOnItemSelectedListener(new FiltroSpinnerListener());
        spinnerSemestre.setOnItemSelectedListener(new FiltroSpinnerListener());
    }

    private void configurarClicLargo() {
        adaptador.setOnItemLongClickListener(alumno -> {
            new AlertDialog.Builder(this)
                    .setTitle("Eliminar Alumno")
                    .setMessage("¿Estás seguro de que deseas eliminar a " + alumno.nombreCompleto + "?")
                    .setPositiveButton("Aceptar", (dialog, which) -> eliminarAlumno(alumno.idAlumno))
                    .setNegativeButton("Cancelar", null)
                    .show();
        });
    }

    private void eliminarAlumno(int idAlumno) {
        ApiService apiService = RetrofitClient.getClient().create(ApiService.class);
        Call<Void> call = apiService.eliminarAlumno(String.valueOf(idAlumno));

        call.enqueue(new Callback<Void>() {
            @Override
            public void onResponse(Call<Void> call, Response<Void> response) {
                if (response.isSuccessful()) {
                    Toast.makeText(ActivityConsultas.this, "Alumno eliminado correctamente.", Toast.LENGTH_SHORT).show();
                    cargarAlumnosFiltrados(); // Recargar la lista después de eliminar
                } else {
                    Toast.makeText(ActivityConsultas.this, "Error al eliminar el alumno.", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<Void> call, Throwable t) {
                Toast.makeText(ActivityConsultas.this, "Error de conexión al eliminar el alumno.", Toast.LENGTH_SHORT).show();
                Log.e("CONSULTAS", "Error al conectar con la API para eliminar: ", t);
            }
        });
    }

    private void cargarAlumnosFiltrados() {
        String numeroControl = txtNumeroControl.getText().toString().trim();
        String nombre = txtNombre.getText().toString().trim();

        String carrera = spinnerCarrera.getSelectedItem() != null &&
                !spinnerCarrera.getSelectedItem().toString().equals("--SELECCIONAR CARRERA--")
                ? spinnerCarrera.getSelectedItem().toString().trim()
                : null;

        Integer semestre = null;
        if (spinnerSemestre.getSelectedItem() != null &&
                !spinnerSemestre.getSelectedItem().toString().equals("--SELECCIONAR SEMESTRE--")) {
            try {
                semestre = Integer.parseInt(spinnerSemestre.getSelectedItem().toString().trim());
            } catch (NumberFormatException e) {
                Log.e("CONSULTAS", "Error al convertir semestre a entero", e);
            }
        }

        Log.d("CONSULTAS", "Filtros: numeroControl=" + numeroControl + ", nombre=" + nombre +
                ", carrera=" + carrera + ", semestre=" + semestre);

        ApiService apiService = RetrofitClient.getClient().create(ApiService.class);

        Call<List<ApiService.AlumnoResponse>> call = apiService.filtrarAlumnos(
                numeroControl.isEmpty() ? null : numeroControl,
                nombre.isEmpty() ? null : nombre,
                carrera,
                semestre
        );

        call.enqueue(new Callback<List<ApiService.AlumnoResponse>>() {
            @Override
            public void onResponse(Call<List<ApiService.AlumnoResponse>> call, Response<List<ApiService.AlumnoResponse>> response) {
                if (response.isSuccessful()) {
                    List<ApiService.AlumnoResponse> alumnos = response.body();
                    if (alumnos != null && !alumnos.isEmpty()) {
                        adaptador.actualizarLista(alumnos);
                    } else {
                        adaptador.actualizarLista(null);
                        Toast.makeText(getApplicationContext(), "No se encontraron alumnos.", Toast.LENGTH_SHORT).show();
                    }
                } else {
                    adaptador.actualizarLista(null);
                    Toast.makeText(getApplicationContext(), "Error al cargar datos.", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<List<ApiService.AlumnoResponse>> call, Throwable t) {
                Toast.makeText(getApplicationContext(), "Error al conectar con el servidor.", Toast.LENGTH_SHORT).show();
            }
        });
    }


    private class FiltroTextWatcher implements TextWatcher {
        @Override
        public void beforeTextChanged(CharSequence s, int start, int count, int after) {}

        @Override
        public void onTextChanged(CharSequence s, int start, int before, int count) {
            cargarAlumnosFiltrados();
        }

        @Override
        public void afterTextChanged(Editable s) {}
    }

    private class FiltroSpinnerListener implements AdapterView.OnItemSelectedListener {
        @Override
        public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
            cargarAlumnosFiltrados();
        }

        @Override
        public void onNothingSelected(AdapterView<?> parent) {}
    }
}
