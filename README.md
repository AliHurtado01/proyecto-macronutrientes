\# 🥗 NutriTrack \- Gestión Nutricional Inteligente

\!\[Laravel\](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge\&logo=laravel)  
\!\[PHP\](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge\&logo=php)  
\!\[TailwindCSS\](https://img.shields.io/badge/Tailwind\_CSS-38B2AC?style=for-the-badge\&logo=tailwind-css)  
\!\[Alpine.js\](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge\&logo=alpinedotjs)  
\!\[MySQL\](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge\&logo=mysql)

\*\*NutriTrack\*\* es una aplicación web integral desarrollada en \*\*Laravel 11\*\* para el control dietético, planificación de menús y seguimiento de objetivos nutricionales. Se diferencia por su integración directa con la \*\*API BEDCA\*\* (Base de Datos Española de Composición de Alimentos) y su arquitectura robusta para el cálculo de macronutrientes en tiempo real.

\---

\#\# 🚀 Características Principales

\#\#\# 🍎 Gestión Avanzada de Ingredientes  
\* \*\*Base de Datos Híbrida:\*\* Combina miles de productos oficiales importados de BEDCA con ingredientes personalizados creados por el usuario.  
\* \*\*Normalización de Datos:\*\* Algoritmo propio (\`NutrientExtractor\`) para estandarizar los datos complejos de la API externa.  
\* \*\*Sistema de Favoritos:\*\* Acceso rápido a los ingredientes más utilizados.

\#\#\# 🍳 Creación de Recetas (Platos)  
\* \*\*Calculadora Nutricional Automática:\*\* Al crear un plato, el sistema calcula automáticamente las calorías, proteínas, grasas y carbohidratos totales basándose en los gramos de cada ingrediente.  
\* \*\*Formularios Dinámicos:\*\* Interfaz reactiva construida con \*\*Alpine.js\*\* que permite añadir o quitar ingredientes de la receta de forma fluida sin recargar la página.

\#\#\# 📅 Planificación y Calendario  
\* \*\*Agenda Semanal:\*\* Planificador visual interactivo para organizar desayunos, comidas, meriendas y cenas.  
\* \*\*Control de Raciones:\*\* Ajuste preciso de la ingesta (ej: "1.5 raciones" de Lentejas).  
\* \*\*Exportación PDF:\*\* Generación de informes semanales listos para imprimir (\`dompdf\`), ideales para la lista de la compra o colgar en la nevera.

\#\#\# 📊 Dashboard de Seguimiento  
\* \*\*Progreso en Tiempo Real:\*\* Gráficas visuales que comparan la ingesta diaria con los objetivos marcados por el usuario.  
\* \*\*Alertas Inteligentes:\*\* Indicadores de color (Semáforo) que avisan de déficits o excesos nutricionales.

\---

\#\# 🛠️ Stack Tecnológico y Arquitectura

El proyecto sigue una arquitectura \*\*MVC\*\* estricta con patrones de diseño avanzados para garantizar la escalabilidad.

\* \*\*Backend:\*\* Laravel 11 (PHP 8.2+).  
\* \*\*Frontend:\*\* Blade Templates \+ Tailwind CSS \+ Alpine.js.  
\* \*\*Base de Datos:\*\* MySQL / MariaDB.  
\* \*\*Autenticación:\*\* Laravel Breeze (Seguridad robusta).

\#\#\# Decisiones de Arquitectura Destacadas

1\.  \*\*Patrón Helper/Service:\*\*  
    \* \`app/Helpers/NutrientExtractor.php\`: Desacopla la lógica de limpieza de datos de la API de los controladores.  
    \* \`app/Services/NutrientCalculatorService.php\`: Centraliza la lógica matemática de los cálculos nutricionales.

2\.  \*\*Optimización de Rendimiento:\*\*  
    \* Uso de columnas de "caché" en la tabla \`dishes\` (\`total\_calories\`, etc.) que se actualizan solo al editar la receta, evitando lecturas masivas en tiempo de ejecución.  
    \* Uso de \`Eager Loading\` (\`with('dishes.products')\`) para solucionar problemas de N+1 queries.

3\.  \*\*Seguridad y Scopes:\*\*  
    \* Implementación de \*\*Eloquent Local Scopes\*\* (\`scopeAccessibleBy\`) para garantizar que los usuarios solo accedan a sus datos privados o a los datos públicos globales, manteniendo la privacidad estricta.

\---

\#\# ⚙️ Instalación y Despliegue Local

Sigue estos pasos para levantar el proyecto en tu entorno de desarrollo:

\#\#\# 1\. Prerrequisitos  
\* PHP \>= 8.2  
\* Composer  
\* Node.js & NPM  
\* Servidor MySQL

\#\#\# 2\. Clonar el Repositorio

git clone \[[https://github.com/TU\_USUARIO/nutritrack.git\](https://github.com/TU\_USUARIO/nutritrack.git)](https://github.com/TU_USUARIO/nutritrack.git]\(https://github.com/TU_USUARIO/nutritrack.git\))

cd nutritrack

3\. Instalar Dependencias

\# Backend (Laravel)  
composer install

\# Frontend (Tailwind/Alpine)  
npm install

Configuración de Entorno

cp .env.example .env

php artisan key:generate

Configura tu base de datos en el archivo .env:

DB\_CONNECTION=mysql  
DB\_HOST=127.0.0.1  
DB\_PORT=3306  
DB\_DATABASE=nutritrack\_db  
DB\_USERNAME=root  
DB\_PASSWORD=

5\. Migraciones y Seeders (Datos de Prueba)

Este comando es crucial. Crea la estructura de la base de datos, conecta con la API de BEDCA para importar categorías y productos base, y genera recetas de ejemplo.

php artisan migrate:fresh \--seed

6\. Ejecutar Aplicación

Necesitarás dos terminales:

Terminal 1 (Compilación de assets):

npm run dev

Terminal 2 (Servidor Web):

php artisan serve

**Accede a: [http://127.0.0.1:8000](http://127.0.0.1:8000)**

**🧪 Usuario de Prueba**  
**El sistema genera automáticamente un usuario para pruebas rápidas:**

**Email: test@example.com**

**Contraseña: password**

**📄 Licencia**

**Este proyecto es de código abierto y está disponible bajo la licencia MIT.**