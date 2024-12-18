package com.example.app_tdbd;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;

import androidx.annotation.Nullable;

public class ActivityMenu extends Activity {

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        try {
            setContentView(R.layout.activity_menu);
        } catch (Exception e) {
            Log.e("ActivityMenu", "Error al cargar el diseño: ", e);
        }
    }

    public void abrirActivities(View v) {
        try {
            Intent i = new Intent(this, ActivityAltas.class);
            startActivity(i);
        } catch (Exception e) {
            Log.e("ActivityMenu", "Error al abrir ActivityAltas: ", e);
        }
    }

    public void abrirbajas(View v) {
        try {
            Intent i = new Intent(this, ActivityBajas.class);
            startActivity(i);
        } catch (Exception e) {
            Log.e("ActivityMenu", "Error al abrir ActivityBajas: ", e);
        }
    }

    public void abricambios(View v) {
        try {
            Intent i = new Intent(this, ActivityCambios.class);
            startActivity(i);
        } catch (Exception e) {
            Log.e("ActivityMenu", "Error al abrir ActivityCambios: ", e);
        }
    }

    public void abrirConsultas(View v) {
        try {
            Intent i = new Intent(this, ActivityConsultas.class);
            startActivity(i);
        } catch (Exception e) {
            Log.e("ActivityMenu", "Error al abrir ActivityConsultas: ", e);
        }
    }
}
