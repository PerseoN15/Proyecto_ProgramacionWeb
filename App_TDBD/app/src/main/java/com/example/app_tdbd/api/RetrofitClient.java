package com.example.app_tdbd.api;

import okhttp3.OkHttpClient;
import okhttp3.logging.HttpLoggingInterceptor;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class RetrofitClient {

    // URL base del servidor XAMPP donde están las APIs
    private static final String BASE_URL = "http://192.168.2.7:8080/proyecto_pw/api/controllers/";

    private static Retrofit retrofit = null;

    public static Retrofit getClient() {
        if (retrofit == null) {
            // Crear e inicializar un interceptor de logging
            HttpLoggingInterceptor logging = new HttpLoggingInterceptor();
            logging.setLevel(HttpLoggingInterceptor.Level.BODY); // Log completo: encabezados y cuerpo

            // Crear cliente HTTP con el interceptor
            OkHttpClient client = new OkHttpClient.Builder()
                    .addInterceptor(logging) // Agregar interceptor
                    .build();

            // Crear instancia de Retrofit con el cliente configurado
            retrofit = new Retrofit.Builder()
                    .baseUrl(BASE_URL) // URL base del servidor
                    .addConverterFactory(GsonConverterFactory.create()) // Conversor para manejar JSON automáticamente
                    .client(client) // Cliente HTTP con logs habilitados
                    .build();
        }
        return retrofit;
    }
}
