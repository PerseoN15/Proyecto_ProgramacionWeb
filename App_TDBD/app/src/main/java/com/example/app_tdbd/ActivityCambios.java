package com.example.app_tdbd;

import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import com.example.app_tdbd.api.ApiService;
import com.example.app_tdbd.api.RetrofitClient;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Locale;
import java.util.regex.Pattern;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ActivityCambios extends AppCompatActivity {

    private EditText txtNumeroControl, txtNombreCompleto, txtFechaNacimiento;
    private Spinner spinnerCarrera, spinnerSemestre;
    private String numeroControlOriginal;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cambios);

        // Inicializar vistas
        txtNumeroControl = findViewById(R.id.txt_numeroControl);
        txtNombreCompleto = findViewById(R.id.txt_nombre);
        txtFechaNacimiento = findViewById(R.id.txt_fecha_nacimiento); // Campo de fecha de nacimiento
        spinnerCarrera = findViewById(R.id.spinner_carrera);
        spinnerSemestre = findViewById(R.id.spinner_semestre);

        // Cargar datos desde el intent
        cargarDatosDesdeIntent();
    }

    private void cargarDatosDesdeIntent() {
        if (getIntent() != null) {
            numeroControlOriginal = getIntent().getStringExtra("numero_control");
            String nombreCompleto = getIntent().getStringExtra("nombre_completo");
            String carrera = getIntent().getStringExtra("carrera");
            int semestre = getIntent().getIntExtra("semestre", 1); // Valor por defecto
            String fechaNacimiento = getIntent().getStringExtra("fecha_nacimiento");

            // Convertir fecha de nacimiento a DD/MM/AAAA si viene en otro formato
            fechaNacimiento = convertirFechaADDMMAAAA(fechaNacimiento);

            // Llenar los campos
            txtNumeroControl.setText(numeroControlOriginal);
            txtNombreCompleto.setText(nombreCompleto);
            txtFechaNacimiento.setText(fechaNacimiento); // Llenar fecha de nacimiento

            // Configurar spinner de carrera
            String[] carreras = getResources().getStringArray(R.array.carreras);
            for (int i = 0; i < carreras.length; i++) {
                if (carreras[i].equals(carrera)) {
                    spinnerCarrera.setSelection(i);
                    break;
                }
            }

            // Configurar spinner de semestre
            spinnerSemestre.setSelection(semestre - 1); // Ajustar índice (semestres van del 1 al 12)
        }
    }

    private String convertirFechaADDMMAAAA(String fecha) {
        try {
            SimpleDateFormat formatoEntrada = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());
            SimpleDateFormat formatoSalida = new SimpleDateFormat("dd/MM/yyyy", Locale.getDefault());
            return formatoSalida.format(formatoEntrada.parse(fecha));
        } catch (ParseException e) {
            e.printStackTrace();
            Log.e("CAMBIOS", "Error al convertir fecha a DD/MM/AAAA: " + fecha);
            return fecha; // Devuelve la fecha original si hay error
        }
    }

    private String convertirFechaAFormatoSQL(String fecha) {
        try {
            SimpleDateFormat formatoEntrada = new SimpleDateFormat("dd/MM/yyyy", Locale.getDefault());
            SimpleDateFormat formatoSalida = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());
            return formatoSalida.format(formatoEntrada.parse(fecha));
        } catch (ParseException e) {
            e.printStackTrace();
            Log.e("CAMBIOS", "Error al convertir fecha a formato SQL: " + fecha);
            return fecha; // Devuelve la fecha original si hay un error
        }
    }
    public void guardarCambios(View v) {
        // Obtener datos de los campos
        String numeroControl = txtNumeroControl.getText().toString().trim();
        String nombreCompleto = txtNombreCompleto.getText().toString().trim();
        String carrera = spinnerCarrera.getSelectedItem().toString().trim();
        String semestreStr = spinnerSemestre.getSelectedItem().toString().trim();
        String fechaNacimiento = txtFechaNacimiento.getText().toString().trim();

        // Validar campos vacíos
        if (numeroControl.isEmpty() || nombreCompleto.isEmpty() || fechaNacimiento.isEmpty()) {
            Toast.makeText(getApplicationContext(), "Por favor, llena todos los campos.", Toast.LENGTH_LONG).show();
            return;
        }

        // Validar que los spinners no estén en la primera posición
        if (spinnerCarrera.getSelectedItemPosition() == 0) {
            Toast.makeText(getApplicationContext(), "Por favor, selecciona una carrera válida.", Toast.LENGTH_LONG).show();
            return;
        }
        if (spinnerSemestre.getSelectedItemPosition() == 0) {
            Toast.makeText(getApplicationContext(), "Por favor, selecciona un semestre válido.", Toast.LENGTH_LONG).show();
            return;
        }

        // Validar nombre completo (solo letras y espacios)
        if (!Pattern.matches("[a-zA-ZáéíóúÁÉÍÓÚñÑ\\s]+", nombreCompleto)) {
            Toast.makeText(getApplicationContext(), "El nombre solo puede contener letras y espacios.", Toast.LENGTH_LONG).show();
            return;
        }

        // Validar fecha de nacimiento (formato DD/MM/AAAA)
        if (!Pattern.matches("\\d{2}/\\d{2}/\\d{4}", fechaNacimiento)) {
            Toast.makeText(getApplicationContext(), "La fecha debe tener el formato DD/MM/AAAA.", Toast.LENGTH_LONG).show();
            return;
        }

        // Validar semestre
        int semestre;
        try {
            semestre = Integer.parseInt(semestreStr);
        } catch (NumberFormatException e) {
            Toast.makeText(getApplicationContext(), "El semestre debe ser un número válido.", Toast.LENGTH_LONG).show();
            return;
        }

        // Convertir la fecha al formato SQL
        String fechaSQL = convertirFechaAFormatoSQL(fechaNacimiento);

        // Crear objeto para la solicitud
        ApiService.AlumnoRequest alumno = new ApiService.AlumnoRequest(
                numeroControl,
                nombreCompleto,
                carrera,
                semestre,
                fechaSQL // Fecha convertida a formato SQL
        );

        // Registrar datos enviados a la API
        Log.d("CAMBIOS", "Datos enviados: " + alumno.toString());
        Log.d("CAMBIOS", "Fecha en formato SQL: " + fechaSQL);

        // Llamada a la API para actualizar los datos
        ApiService apiService = RetrofitClient.getClient().create(ApiService.class);
        Call<Void> call = apiService.actualizarAlumno(numeroControlOriginal, alumno);
        call.enqueue(new Callback<Void>() {
            @Override
            public void onResponse(Call<Void> call, Response<Void> response) {
                if (response.isSuccessful()) {
                    Toast.makeText(getApplicationContext(), "Alumno actualizado con éxito", Toast.LENGTH_LONG).show();
                    Log.d("CAMBIOS", "Alumno actualizado con éxito");
                    finish(); // Cerrar la actividad
                } else {
                    Toast.makeText(getApplicationContext(), "Error al actualizar el alumno.", Toast.LENGTH_LONG).show();
                    Log.e("CAMBIOS", "Error en la respuesta: " + response.errorBody());
                }
            }

            @Override
            public void onFailure(Call<Void> call, Throwable t) {
                Toast.makeText(getApplicationContext(), "Error de conexión con el servidor.", Toast.LENGTH_LONG).show();
                Log.e("CAMBIOS", "Error: ", t);
            }
        });
    }


}
