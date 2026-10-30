# 01 · Casos de uso

Mermaid no tiene diagrama de casos de uso nativo; se representa con un diagrama de flujo: actores a los lados, casos de uso (óvalos) agrupados por módulo dentro del sistema.

Los roles son acumulativos: un Moderador puede hacer todo lo del Miembro, y el Administrador todo lo del Moderador.

```mermaid
flowchart LR
    V(["👤 Visitante"])
    M(["👤 Miembro"])
    MO(["👤 Moderador"])
    AD(["👤 Administrador"])
    SY(["⚙️ Sistema"])

    subgraph zBooks
        direction TB
        subgraph Publico["Acceso público"]
            UC1([Ver libro, diario y recorrido])
            UC2([Capturar libro con BCID / QR])
            UC3([Buscar libros y libros cerca])
            UC4([Leer blogs y eventos públicos])
            UC5([Registrarse])
        end
        subgraph Libros["Libros"]
            UC6([Registrar libro por ISBN])
            UC7([Descargar etiqueta PDF])
            UC8([Liberar libro])
            UC9([Escribir entrada de diario])
            UC10([Escribir reseña])
            UC11([Importar desde Goodreads])
        end
        subgraph Comunidad["Comunidad"]
            UC12([Gestionar zona de referencia])
            UC13([Escribir en su blog])
            UC14([Crear canal privado / mensaje directo])
            UC15([Crear evento y gestionar asistentes])
            UC16([Proponer zona OBCZ])
            UC17([Denunciar contenido])
        end
        subgraph Gestion["Gestión"]
            UC18([Crear canal público])
            UC19([Moderar contenido y denuncias])
            UC20([Aprobar zonas OBCZ])
            UC21([Gestionar usuarios y roles])
            UC22([Configurar puntos e insignias])
        end
        subgraph Auto["Automático"]
            UC23([Otorgar puntos e insignias])
            UC24([Calcular rankings])
            UC25([Restaurar zona tras un viaje])
            UC26([Enviar notificaciones])
        end
    end

    V --- UC1 & UC2 & UC3 & UC4 & UC5
    M --- UC6 & UC7 & UC8 & UC9 & UC10 & UC11
    M --- UC12 & UC13 & UC14 & UC15 & UC16 & UC17
    MO --- UC18 & UC19 & UC20
    AD --- UC21 & UC22
    SY --- UC23 & UC24 & UC25 & UC26
```

## Notas

- **Capturar** está en el acceso público porque un visitante sin cuenta puede registrar una captura anónima (RF-13).
- **Mensaje directo** es un canal privado de dos personas (ADR-0005).
