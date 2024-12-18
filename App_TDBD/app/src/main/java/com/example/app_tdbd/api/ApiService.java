package com.example.app_tdbd.api;

import com.google.gson.annotations.SerializedName;
import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.DELETE;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.PUT;
import retrofit2.http.Query;

import java.util.List;

public interface ApiService {

    // Método para iniciar sesión
    @POST("login_controller.php")
    Call<LoginResponse> login(@Body LoginRequest request);

    // Método para registrar un nuevo alumno
    @POST("alumnos_controller.php")
    Call<Void> registrarAlumno(@Body AlumnoRequest alumno);

    // Método para obtener todos los alumnos sin filtros
    @GET("consultas_controller.php")
    Call<List<AlumnoResponse>> obtenerAlumnos();

    // Método para filtrar alumnos con parámetros opcionales
    @GET("consultas_controller.php")
    Call<List<AlumnoResponse>> filtrarAlumnos(
            @Query("numero_control") String numeroControl,
            @Query("nombre") String nombre,
            @Query("carrera") String carrera,
            @Query("semestre") Integer semestre
    );

    // Método para eliminar un alumno por su ID
    @DELETE("bajas_controller.php")
    Call<Void> eliminarAlumno(@Query("id") String id);


    // Método para actualizar un alumno existente
    @PUT("alumnos_controller.php")
    Call<Void> actualizarAlumno(@Query("numero_control") String numeroControl, @Body AlumnoRequest alumno);

    // Clase para la solicitud de login
    class LoginRequest {
        String usuario; // "usuario" coincide con el servidor
        String password;

        public LoginRequest(String usuario, String password) {
            this.usuario = usuario;
            this.password = password;
        }
    }

    // Clase para la respuesta de login
    class LoginResponse {
        public boolean success;
        public String message;
        public String rol; // "rol" coincide con el servidor
    }

    // Clase para la solicitud de registro o actualización de alumno
    class AlumnoRequest {
        @SerializedName("numero_control")
        String numeroControl; // varchar(8)

        @SerializedName("nombre_completo")
        String nombreCompleto; // varchar(100)

        @SerializedName("carrera")
        String carrera; // enum("ISC", "IM", "LA", "IIA", "CP")

        @SerializedName("semestre")
        int semestre; // int(11)

        @SerializedName("fecha_nacimiento")
        String fechaNacimiento; // date

        public AlumnoRequest(String numeroControl, String nombreCompleto, String carrera, int semestre, String fechaNacimiento) {
            this.numeroControl = numeroControl;
            this.nombreCompleto = nombreCompleto;
            this.carrera = carrera;
            this.semestre = semestre;
            this.fechaNacimiento = fechaNacimiento;
        }
    }

    // Clase para la respuesta de alumnos
    class AlumnoResponse {
        @SerializedName("id_alumno")
        public int idAlumno; // Asegúrate de incluir el ID

        @SerializedName("numero_control")
        public String numeroControl;

        @SerializedName("nombre_completo")
        public String nombreCompleto;

        @SerializedName("carrera")
        public String carrera;

        @SerializedName("semestre")
        public int semestre;

        @SerializedName("fecha_nacimiento")
        public String fechaNacimiento;
    }
}
