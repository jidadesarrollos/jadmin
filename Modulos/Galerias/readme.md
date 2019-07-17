#Instalación Módulo Galerías

Para implementar el módulo galerías se deben seguir los siguientes pasos:

1. Verificar que en la base de datos exista la tabla `s_objetos_media` 
2. Agregar la opción en el menú del Jadmin (`Aplicacion/Jadmin/menu.json`) para ingresar al módulo Galerías.

Ejemplo:
```
"items": {
    "galerias": {
        "label": "<i class=\"nav-icon i-File-Text--Image\"></i><span class=\"nav-text\">Galerias</span>",
        "url": "/jadmin/galerias"
    }
}
```

Una vez ingrese al Jadmin ya estará disponible el módulo Galerías en el menú.