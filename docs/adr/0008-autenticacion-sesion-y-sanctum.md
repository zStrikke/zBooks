# ADR-0008: Autenticación con sesión en la web y tokens Sanctum en la API

- **Estado:** Aceptado
- **Fecha:** 2026-10-30
- **Decisores:** PO, Claude

## Contexto

zBooks tendrá dos tipos de cliente: la web (Livewire, mismo dominio) y la API (`/api/v1`) para una futura app móvil y herramientas como Postman. El PO pidió verificación por email en el registro y se planteó usar Sanctum.

## Opciones consideradas

1. **Solo sesión:** no sirve para clientes móviles.
2. **Solo Sanctum** (también para la web): añade complejidad sin beneficio en una web del mismo dominio.
3. **Sesión en la web + tokens Sanctum en la API.**
4. **Laravel Passport (OAuth2):** excesivo; no hay terceros que necesiten delegar acceso.

## Decisión

Elegimos la **opción 3**: una misma cuenta con dos formas de identificarse.

- **Web:** sesión estándar de Laravel con **Breeze** (variante Livewire).
- **API:** **tokens de Sanctum**, uno por dispositivo (`device_name`), con *abilities*, caducidad y revocables desde el perfil.
- **Verificación de email obligatoria** en ambos canales. Sin verificar se puede leer, pero las acciones que escriben devuelven `403` (middleware `verified`).
- Rate limiting estricto en login y registro.

## Consecuencias

**Positivas**
- Cada cliente usa el mecanismo más adecuado.
- El usuario ve y revoca sus dispositivos conectados.
- Menos spam y cuentas falsas gracias a la verificación.

**Negativas / riesgos**
- Dos flujos de autenticación que probar.
- Los tokens deben guardarse de forma segura en el cliente móvil.

**Seguimiento**
- Caducidad de tokens sin uso (propuesta: 90 días).
- Tests de contrato de los endpoints de autenticación en la iteración 1.

## Referencias

- RF-01, RF-48, RF-49, RNF-03.
- [Diagrama 09 · Secuencia de autenticación](../diagramas/09-secuencia-autenticacion.md)
