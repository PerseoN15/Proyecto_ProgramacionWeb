package com.example.app_tdbd;

import androidx.appcompat.app.AppCompatActivity;

import android.app.DatePickerDialog;
import android.os.Bundle;
import android.text.Editable;
import android.text.InputFilter;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import com.example.app_tdbd.api.ApiService;
import com.example.app_tdbd.api.RetrofitClient;
import com.google.gson.Gson;

import java.io.IOException;
import java.util.Calendar;
import java.util.regex.Pattern;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ActivityAltas extends AppCompatActivity {

    EditText txtNumeroControl, txtNombreCompleto, txtFechaNacimiento;
    Spinner txtCarrera, txtSemestre;
    private Toast toast;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_altas);

        // Inicializar campos
        txtNumeroControl = findViewById(R.id.txt_numeroControl);
        txtNombreCompleto = findViewById(R.id.txt_nombre);
        txtCarrera = findViewById(R.id.spinner_carrera); // Spinner
        txtSemestre = findViewById(R.id.spinner_semestre); // Spinner
        txtFechaNacimiento = findViewById(R.id.txt_edad);

        // Configurar validaciones
        configurarValidaciones();

        // Configurar selector de fecha
        txtFechaNacimiento.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mostrarDatePicker();
            }
        });
    }

    private void configurarValidaciones() {
        // Validación: Número de Control (solo números, exactamente 8 dígitos)
        txtNumeroControl.setFilters(new InputFilter[]{new InputFilter.LengthFilter(8)}); // Máximo 8 caracteres
        txtNumeroControl.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {}

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                if (!s.toString().matches("\\d*")) { // Permitir solo números
                    txtNumeroControl.setError("Solo se permiten números");
                }
            }

            @Override
            public void afterTextChanged(Editable s) {
                if (s.length() != 8) {
                    txtNumeroControl.setError("El número de control debe tener exactamente 8 dígitos");
                }
            }
        });

        // Validación: Nombre Completo (solo letras y espacios)
        txtNombreCompleto.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {}

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                if (!s.toString().matches("[a-zA-ZáéíóúÁÉÍÓÚñÑ\\s]*")) {
                    txtNombreCompleto.setError("Solo se permiten letras y espacios");
                }
            }

            @Override
            public void afterTextChanged(Editable s) {}
        });
    }

    private void mostrarDatePicker() {
        // Obtener la fecha actual
        final Calendar calendar = Calendar.getInstance();
        int anio = calendar.get(Calendar.YEAR);
        int mes = calendar.get(Calendar.MONTH);
        int dia = calendar.get(Calendar.DAY_OF_MONTH);

        // Crear y mostrar el DatePickerDialog
        DatePickerDialog datePickerDialog = new DatePickerDialog(this, new DatePickerDialog.OnDateSetListener() {
            @Override
            public void onDateSet(DatePicker view, int year, int month, int dayOfMonth) {
                // Ajustar formato de la fecha seleccionada
                String fechaSeleccionada = String.format("%02d/%02d/%04d", dayOfMonth, month + 1, year);
                txtFechaNacimiento.setText(fechaSeleccionada);
            }
        }, anio, mes, dia);

        datePickerDialog.show();
    }

    public void registrarAlumno(View v) {
        // Obtener datos de los campos
        String numeroControl = txtNumeroControl.getText().toString().trim();
        String nombreCompleto = txtNombreCompleto.getText().toString().trim();
        String carrera = txtCarrera.getSelectedItem().toString().trim();
        String semestreStr = txtSemestre.getSelectedItem().toString().trim();
        String fechaNacimiento = txtFechaNacimiento.getText().toString().trim();

        // Validar campos
        if (numeroControl.isEmpty()) {
            mostrarToast("Por favor, ingrese el número de control.");
            return;
        }

        if (nombreCompleto.isEmpty()) {
            mostrarToast("Por favor, ingrese el nombre completo.");
            return;
        }

        if (carrera.equals("--SELECCIONAR CARRERA--")) {
            mostrarToast("Por favor, seleccione una carrera válida.");
            return;
        }

        if (semestreStr.equals("--SELECCIONAR SEMESTRE--")) {
            mostrarToast("Por favor, seleccione un semestre válido.");
            return;
        }

        if (fechaNacimiento.isEmpty()) {
            mostrarToast("Por favor, ingrese la fecha de nacimiento.");
            return;
        }

        // Validar y convertir fecha al formato esperado por la API (YYYY-MM-DD)
        String fechaSQL = convertirFechaAFormatoSQL(fechaNacimiento);
        if (fechaSQL == null) {
            mostrarToast("Formato de fecha inválido. Use DD/MM/AAAA.");
            return;
        }

        int semestre;
        try {
            semestre = Integer.parseInt(semestreStr);
        } catch (NumberFormatException e) {
            mostrarToast("El semestre debe ser un número válido.");
            return;
        }

        // Crear objeto para la solicitud
        ApiService.AlumnoRequest alumno = new ApiService.AlumnoRequest(
                numeroControl,
                nombreCompleto,
                carrera,
                semestre,
                fechaSQL // Usar la fecha en formato SQL
        );

        // Log del JSON enviado
        Log.d("ALTAS", "JSON enviado: " + new Gson().toJson(alumno));

        // Llamada a la API
        ApiService apiService = RetrofitClient.getClient().create(ApiService.class);
        Call<Void> call = apiService.registrarAlumno(alumno);
        call.enqueue(new Callback<Void>() {
            @Override
            public void onResponse(Call<Void> call, Response<Void> response) {
                if (response.isSuccessful()) {
                    mostrarToast("Alumno registrado con éxito.");
                    limpiarCampos();
                } else {
                    Log.e("ALTAS", "Código de respuesta: " + response.code());
                    try {
                        if (response.errorBody() != null) {
                            Log.e("ALTAS", "Cuerpo del error: " + response.errorBody().string());
                        }
                    } catch (IOException e) {
                        Log.e("ALTAS", "Error al leer el cuerpo del error", e);
                    }
                    mostrarToast("Error al registrar el alumno. Código: " + response.code());
                }
            }

            @Override
            public void onFailure(Call<Void> call, Throwable t) {
                Log.e("ALTAS", "Error de conexión: ", t);
                mostrarToast("Error de conexión con el servidor.");
            }
        });
    }

    private void limpiarCampos() {
        txtNumeroControl.setText("");
        txtNombreCompleto.setText("");
        txtFechaNacimiento.setText("");
        txtCarrera.setSelection(0); // Reiniciar Spinner
        txtSemestre.setSelection(0); // Reiniciar Spinner
    }

    private void mostrarToast(String mensaje) {
        if (toast != null) {
            toast.cancel();
        }
        toast = Toast.makeText(getApplicationContext(), mensaje, Toast.LENGTH_LONG);
        toast.show();
    }

    private String convertirFechaAFormatoSQL(String fecha) {
        if (!fecha.matches("\\d{2}/\\d{2}/\\d{4}")) return null;

        String[] partes = fecha.split("/");
        String dia = partes[0];
        String mes = partes[1];
        String anio = partes[2];

        return anio + "-" + mes + "-" + dia;
    }
}
