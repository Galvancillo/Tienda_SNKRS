# Arquitectura del Proyecto

Este proyecto utiliza una arquitectura basada en el patrón **MVC (Modelo-Vista-Controlador)** junto con la aplicación de los principios **SOLID** para el diseño de software.

## Descripción de la Arquitectura

### MVC (Modelo-Vista-Controlador)
- **Modelo:** Encapsula la lógica de negocio y el acceso a datos. Ejemplo: `app/Models/Producto.php`.
- **Vista:** Contiene los archivos de presentación que se muestran al usuario. Ejemplo: `app/Views/`.
- **Controlador:** Gestiona la lógica de la aplicación, recibe las peticiones del usuario, procesa los datos a través del modelo y selecciona la vista adecuada. Ejemplo: `app/Controllers/`.

### Principios SOLID
- **S**: Responsabilidad Única (Single Responsibility Principle)
- **O**: Abierto/Cerrado (Open/Closed Principle)
- **L**: Sustitución de Liskov (Liskov Substitution Principle)
- **I**: Segregación de Interfaces (Interface Segregation Principle)
- **D**: Inversión de Dependencias (Dependency Inversion Principle)

Estos principios se aplican en la estructura del código, especialmente en el uso de interfaces (`app/Interfaces/`), servicios (`app/Services/`), y la separación clara de responsabilidades entre controladores, modelos y vistas.

## Estructura de Carpetas

- `public/` — Archivos públicos y punto de entrada (`index.php`)
- `app/Controllers/` — Controladores
- `app/Models/` — Modelos
- `app/Views/` — Vistas
- `app/Services/` — Lógica de negocio y servicios
- `app/Interfaces/` — Definición de interfaces
- `app/Core/` — Componentes centrales como el router y la base de datos
- `config/` — Configuración del proyecto 