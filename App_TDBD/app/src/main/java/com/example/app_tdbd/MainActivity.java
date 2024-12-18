package com.example.app_tdbd;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import com.example.app_tdbd.api.ApiService;
import com.example.app_tdbd.api.RetrofitClient;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MainActivity extends AppCompatActivity {

    EditText cajaUsuario, cajaContraseña;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // Inicializar las cajas de texto
        cajaUsuario = findViewById(R.id.caja_usuario);
        cajaContraseña = findViewById(R.id.caja_contraseña);
    }

    public void abrirMenu(View v) {
        // Obtener los datos ingresados por el usuario
        String usuarioIngresado = cajaUsuario.getText().toString().trim();
        String contraseñaIngresada = cajaContraseña.getText().toString().trim();

        // Validar los campos
        if (esUsuarioValido(usuarioIngresado) && esContraseñaValida(contraseñaIngresada)) {
            autenticarUsuarioEnServidor(usuarioIngresado, contraseñaIngresada);
        } else {
            Toast.makeText(getApplicationContext(), "Usuario o contraseña inválidos. Inténtalo de nuevo.", Toast.LENGTH_LONG).show();
        }
    }

    private void autenticarUsuarioEnServidor(String usuario, String contraseña) {
        Log.d("LOGIN", "Enviando: usuario=" + usuario + ", contraseña=" + contraseña);

        // Configurar Retrofit y el servicio de la API
        ApiService apiService = RetrofitClient.getClient().create(ApiService.class);

        // Llamada a la API
        Call<ApiService.LoginResponse> call = apiService.login(new ApiService.LoginRequest(usuario, contraseña));
        call.enqueue(new Callback<ApiService.LoginResponse>() {
            @Override
            public void onResponse(Call<ApiService.LoginResponse> call, Response<ApiService.LoginResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    ApiService.LoginResponse loginResponse = response.body();

                    if (loginResponse.success) {
                        String rol = loginResponse.rol; // Captura el campo "rol"
                        Log.d("LOGIN", "Usuario autenticado, rol: " + rol);

                        // Usuario autenticado correctamente
                        Toast.makeText(getApplicationContext(), "¡Bienvenido! Rol: " + rol, Toast.LENGTH_LONG).show();

                        // Abrir el menú principal
                        Intent intent = new Intent(getApplicationContext(), ActivityMenu.class);
                        intent.putExtra("ROL_USUARIO", rol); // Pasar el rol a la siguiente actividad
                        startActivity(intent);
                    } else {
                        Log.e("LOGIN", "Autenticación fallida: " + loginResponse.message);
                        Toast.makeText(getApplicationContext(), "Usuario o contraseña incorrectos.", Toast.LENGTH_LONG).show();
                    }
                } else {
                    // Error en la respuesta
                    Log.e("LOGIN", "Respuesta no exitosa. Código: " + response.code());
                    if (response.errorBody() != null) {
                        try {
                            Log.e("LOGIN", "Error body: " + response.errorBody().string());
                        } catch (Exception e) {
                            Log.e("LOGIN", "Error al leer el cuerpo de la respuesta", e);
                        }
                    }
                    Toast.makeText(getApplicationContext(), "Error al autenticar. Verifica los datos.", Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<ApiService.LoginResponse> call, Throwable t) {
                // Error de conexión
                Log.e("LOGIN", "Error de conexión: ", t);
                Toast.makeText(getApplicationContext(), "Error al conectar con el servidor. Verifica tu red.", Toast.LENGTH_LONG).show();
            }
        });
    }

    private boolean esUsuarioValido(String usuario) {
        return !usuario.isEmpty();
    }

    private boolean esContraseñaValida(String contraseña) {
        return !contraseña.isEmpty();
    }
}
