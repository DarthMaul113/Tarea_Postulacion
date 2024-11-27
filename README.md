# **Configuración de la Base de Datos**

Este proyecto incluye un archivo SQL como plantilla para configurar la base de datos necesaria. Asegúrate de importar este archivo antes de ejecutar el proyecto.

---

## **Pasos para importar la base de datos en phpMyAdmin**

1. Accede a **phpMyAdmin** desde tu servidor local (por ejemplo, `http://localhost/phpmyadmin`).
2. Crea una nueva base de datos con el nombre deseado (recomendado: `indicadores`).
3. Selecciona la base de datos creada en el panel izquierdo.
4. Haz clic en la pestaña **Importar**.
5. Haz clic en **Elegir archivo** y selecciona el archivo `historial_uf.sql` incluido en este proyecto.
6. Presiona el botón **Continuar** para ejecutar la importación.

---

## **Detalles del archivo SQL**

El archivo `historial_uf.sql` incluye:
- La **creación de la tabla** `historial_uf`.
- Los campos necesarios:
  - `id`: Identificador único (clave primaria).
  - `fecha`: Fecha del valor (formato `YYYY-MM-DD`).
  - `valor`: Valor asociado a la fecha.
- **Datos iniciales** como ejemplo para pruebas.
