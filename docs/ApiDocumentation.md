# Prode Mundial 2026 — Documentación de la API

## Índice

1. [Información general](#información-general)
2. [Autenticación](#autenticación)
3. [Estructura de respuestas](#estructura-de-respuestas)
4. [Endpoints](#endpoints)
   - [Auth](#auth)
   - [Equipos](#equipos)
   - [Partidos](#partidos)
   - [Predicciones](#predicciones)
   - [Grupos de amigos](#grupos-de-amigos)
   - [Usuarios](#usuarios)
5. [Modelos de datos](#modelos-de-datos)
6. [Códigos de error](#códigos-de-error)
7. [Notas de implementación](#notas-de-implementación)

---

## Información general

- **Base URL:** `http://[dominio]/api`
- **Formato:** Todas las requests y responses son en `application/json`
- **Framework backend:** Laravel con Sanctum para autenticación
- **CORS:** Configurado para aceptar cualquier origen en desarrollo (`*`)
- **Servidor:** Compartido con el frontend — sin restricciones de dominio en desarrollo

---

## Autenticación

La API usa **tokens Bearer** mediante Laravel Sanctum. No usa cookies ni sesiones.

### Flujo básico

1. El usuario se registra o loguea → la API devuelve un `token`
2. Ese token se guarda en el frontend (localStorage, estado global, etc.)
3. Cada request autenticada debe incluir el token en el header:

```
Authorization: Bearer {token}
```

### Ejemplo con fetch (JavaScript)

```javascript
// Login
const response = await fetch('http://[dominio]/api/login', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ email: 'user@example.com', password: 'password' })
});
const { token, user } = await response.json();

// Request autenticada
const partidos = await fetch('http://[dominio]/api/partidos', {
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
  }
});
```

### Ejemplo con axios (recomendado para React)

```javascript
import axios from 'axios';

// Configuración global (hacerlo una sola vez al arrancar la app)
axios.defaults.baseURL = 'http://[dominio]/api';
axios.defaults.headers.common['Content-Type'] = 'application/json';

// Después del login, setear el token globalmente
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

// A partir de ahí, todas las requests lo incluyen automáticamente
const { data } = await axios.get('/partidos');
```

---

## Estructura de respuestas

Las respuestas exitosas devuelven los datos directamente (sin wrapper), salvo en login/register que devuelven `token` y `user`.

Las respuestas de error siguen esta estructura:

```json
{
  "error": "Mensaje descriptivo del error"
}
```

Los errores de validación devuelven status `422` con esta estructura:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "campo": ["Mensaje de error del campo"]
  }
}
```

---

## Endpoints

---

### Auth

#### `POST /api/register`

Registra un nuevo usuario. Al registrarse, el sistema crea automáticamente todas las predicciones vacías del fixture para ese usuario.

**Body:**
```json
{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response `201`:**
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "role": "jugador",
    "total_points": 0,
    "champion_team_id": null
  }
}
```

---

#### `POST /api/login`

**Body:**
```json
{
  "email": "juan@example.com",
  "password": "password123"
}
```

**Response `200`:**
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "role": "jugador",
    "total_points": 42,
    "champion_team_id": 12
  }
}
```

**Error `401`:** Credenciales incorrectas.

---

#### `POST /api/logout` 🔒

Invalida todos los tokens del usuario. No requiere body.

**Response `200`:**
```json
{
  "message": "Sesión cerrada"
}
```

---

### Equipos

> Todos los endpoints de equipos requieren autenticación 🔒

#### `GET /api/equipos`

Devuelve todos los equipos del torneo ordenados por nombre.

**Response `200`:**
```json
[
  {
    "id": 1,
    "name": "Argentina",
    "fifa_code": "ARG",
    "flag_url": "ar"
  }
]
```

---

#### `GET /api/equipos/{id}`

Devuelve un equipo específico.

**Response `200`:**
```json
{
  "id": 1,
  "name": "Argentina",
  "fifa_code": "ARG",
  "flag_url": "ar"
}
```

**Error `404`:** Equipo no encontrado.

---

### Partidos

> Todos los endpoints de partidos requieren autenticación 🔒

#### `GET /api/partidos`

Devuelve todos los partidos del torneo (fase de grupos + eliminatorias) con sus equipos y grupo.

**Response `200`:** Array de objetos partido. Ver [modelo Partido](#partido).

---

#### `GET /api/partidos/{id}`

Devuelve un partido específico con sus equipos y grupo.

**Response `200`:** Objeto partido con relaciones. Ver [modelo Partido](#partido).

**Error `404`:** Partido no encontrado.

---

#### `GET /api/partidos/grupo/{grupo}`

Devuelve todos los partidos de un grupo específico de la fase de grupos.

**Parámetro:** `grupo` — letra del grupo (A, B, C, D, E, F, G, H, I, J, K, L)

**Ejemplo:** `GET /api/partidos/grupo/A`

**Response `200`:** Array de objetos partido del grupo solicitado.

---

#### `GET /api/partidos/stage/{stage}`

Devuelve todos los partidos de una fase del torneo.

**Parámetro:** `stage` — uno de los siguientes valores:

| Valor | Descripción |
|---|---|
| `fase_grupos` | Fase de grupos (72 partidos) |
| `dieciseisavos` | Dieciseisavos de final |
| `octavos` | Octavos de final |
| `cuartos` | Cuartos de final |
| `semis` | Semifinales |
| `tercero` | Tercer puesto |
| `final` | Final |

**Ejemplo:** `GET /api/partidos/stage/octavos`

**Response `200`:** Array de objetos partido de la fase solicitada.

---

### Predicciones

> Todos los endpoints de predicciones requieren autenticación 🔒

#### `GET /api/predicciones`

Devuelve todas las predicciones del usuario autenticado, incluyendo datos del partido y equipos.

**Response `200`:**
```json
[
  {
    "id": 1,
    "user_id": 1,
    "match_id": 1,
    "predicted_home_score": null,
    "predicted_away_score": null,
    "predicted_winner_team_id": null,
    "points": null,
    "match": {
      "id": 1,
      "stage": "fase_grupos",
      "match_date": "2026-06-11T16:00:00.000000Z",
      "status": "pendiente",
      "home_team": { "id": 1, "name": "México", "fifa_code": "MEX" },
      "away_team": { "id": 2, "name": "Sudáfrica", "fifa_code": "RSA" }
    }
  }
]
```

> **Nota:** Al registrarse, cada usuario tiene predicciones creadas para todos los partidos con scores en `null`. Este endpoint puede usarse para mostrar el fixture completo con el estado de cada predicción del usuario.

---

#### `GET /api/predicciones/{match_id}`

Devuelve la predicción del usuario autenticado para un partido específico.

**Parámetro:** `match_id` — ID del partido (no de la predicción).

**Response `200`:** Objeto predicción con datos del partido y equipos. Misma estructura que cada ítem de `GET /api/predicciones`.

---

#### `PUT /api/predicciones/{match_id}`

Carga o actualiza la predicción del usuario para un partido específico. Usa upsert: si ya existe la actualiza, si no la crea.

> **Restricción:** No se puede predecir un partido que ya comenzó (`match_date` <= hora actual). La API devuelve `403` en ese caso.

**Parámetro:** `match_id` — ID del partido (no de la predicción).

**Body:**
```json
{
  "predicted_home_score": 2,
  "predicted_away_score": 1,
  "predicted_winner_team_id": null
}
```

**Validación:**
- `predicted_home_score` y `predicted_away_score`: requeridos, enteros entre 0 y 20
- `predicted_winner_team_id`: opcional, ID válido de un equipo. En partidos de eliminatorias donde el usuario predice empate, se usa para determinar quién gana en penales

> **Nota para eliminatorias:** Si el usuario predice empate en cualquier fase que no sea `fase_grupos`, se recomienda enviar `predicted_winner_team_id`. Sin ese campo, un empate predicho con score exacto no suma los puntos del bonus de quién pasa.

**Response `200`:**
```json
{
  "id": 1,
  "user_id": 1,
  "match_id": 1,
  "predicted_home_score": 2,
  "predicted_away_score": 1,
  "predicted_winner_team_id": null,
  "points": null
}
```

**Error `403`:** El partido ya comenzó, no se puede modificar la predicción.

---

#### `PUT /api/usuario/campeon` 🔒

Registra o actualiza el pronóstico de campeón del usuario. Se puede modificar hasta el inicio de las semifinales. El bonus que recibe el usuario si acierta depende de cuándo fue el último cambio — ver [sistema de puntos](#sistema-de-puntos).

**Body:**
```json
{
  "champion_team_id": 37
}
```

**Response `200`:**
```json
{
  "message": "Pronóstico de campeón guardado"
}
```

**Error `403`:** Las semifinales ya comenzaron, no se puede modificar el pronóstico.

---

### Grupos de amigos

> Todos los endpoints de grupos requieren autenticación 🔒

Los grupos de amigos son torneos privados. Cada grupo tiene un `invite_code` único que se comparte para que otros usuarios puedan unirse. Todos los miembros del grupo predicen los mismos partidos del mundial pero tienen su propia tabla de posiciones interna.

#### `GET /api/grupos`

Devuelve los grupos a los que pertenece el usuario autenticado.

**Response `200`:** Array de grupos.

---

#### `POST /api/grupos`

Crea un nuevo grupo. El usuario que lo crea queda automáticamente como miembro y propietario.

**Body:**
```json
{
  "name": "Los Cracks del Trabajo"
}
```

**Response `201`:**
```json
{
  "id": 1,
  "name": "Los Cracks del Trabajo",
  "owner_id": 1,
  "invite_code": "aB3xKp7Q",
  "created_at": "2026-01-01T00:00:00.000000Z"
}
```

> El `invite_code` se genera automáticamente. Es el código que se comparte con otros usuarios para unirse al grupo.

---

#### `GET /api/grupos/{id}`

Devuelve un grupo con su lista de miembros.

**Response `200`:**
```json
{
  "id": 1,
  "name": "Los Cracks del Trabajo",
  "owner_id": 1,
  "invite_code": "aB3xKp7Q",
  "users": [
    { "id": 1, "name": "Juan" },
    { "id": 2, "name": "María" }
  ]
}
```

---

#### `POST /api/grupos/unirse`

Une al usuario autenticado a un grupo usando el código de invitación.

**Body:**
```json
{
  "invite_code": "aB3xKp7Q"
}
```

**Response `200`:** Objeto del grupo al que se unió.

**Error `404`:** Código de invitación inválido.

**Error `409`:** El usuario ya es miembro de ese grupo.

---

#### `GET /api/grupos/{id}/posiciones`

Devuelve la tabla de posiciones interna del grupo, ordenada de mayor a menor puntaje.

**Response `200`:**
```json
[
  { "name": "Juan",   "total_points": 42 },
  { "name": "María",  "total_points": 38 },
  { "name": "Carlos", "total_points": 21 }
]
```

---

#### `POST /api/grupos/{id}/agregar`

Permite al propietario del grupo agregar un usuario directamente usando su ID. El flujo recomendado es buscar al usuario primero con `POST /api/usuarios/buscar`, obtener su `id` del resultado, y pasarlo acá.

> **Restricción:** Solo el propietario del grupo puede usar este endpoint.

**Body:**
```json
{
  "user_id": 5
}
```

**Response `200`:**
```json
{
  "message": "Usuario agregado al grupo"
}
```

**Error `403`:** El usuario autenticado no es el propietario del grupo.

**Error `409`:** El usuario ya es miembro del grupo.

**Error `422`:** El `user_id` no corresponde a ningún usuario registrado.

---

#### `POST /api/grupos/{id}/agregar/mail`

Permite al propietario del grupo agregar un usuario directamente usando su dirección de mail registrada.

> **Restricción:** Solo el propietario del grupo puede usar este endpoint.

**Body:**
```json
{
  "email": "usuario@example.com"
}
```

**Response `200`:**
```json
{
  "message": "Usuario agregado al grupo",
  "user": {
    "id": 5,
    "name": "Juan Pérez"
  }
}
```

**Error `403`:** El usuario autenticado no es el propietario del grupo.

**Error `404`:** No existe ningún usuario registrado con ese mail.

**Error `409`:** El usuario ya es miembro del grupo.

---

#### `DELETE /api/grupos/{id}/quitar/{user_id}`

Permite al propietario del grupo eliminar a un miembro. No se puede usar para quitarse a uno mismo.

> **Restricción:** Solo el propietario del grupo puede usar este endpoint.

**Ejemplo:** `DELETE /api/grupos/1/quitar/5`

**Response `200`:**
```json
{
  "message": "Usuario eliminado del grupo"
}
```

**Error `403`:** El usuario autenticado no es el propietario del grupo.

**Error `422`:** El propietario intentó quitarse a sí mismo.

---

#### `DELETE /api/grupos/{id}/salir`

Permite a un miembro abandonar un grupo por voluntad propia. El propietario del grupo no puede usar este endpoint.

**Response `200`:**
```json
{
  "message": "Saliste del grupo"
}
```

**Error `422`:** El propietario intentó abandonar su propio grupo.

---

#### `DELETE /api/grupos/{id}`

Elimina el grupo y desvincula a todos los miembros. Solo puede usarlo el propietario.

**Ejemplo:** `DELETE /api/grupos/1`

**Response `200`:**
```json
{
  "message": "Grupo eliminado"
}
```

**Error `403`:** El usuario autenticado no es el propietario del grupo.

---

### Usuarios

> Todos los endpoints de usuarios requieren autenticación 🔒

#### `GET /api/usuarios/leaderboard`

Devuelve el ranking global de todos los usuarios, ordenado por puntaje de mayor a menor.

**Response `200`:**
```json
[
  { "name": "Juan",   "total_points": 42 },
  { "name": "María",  "total_points": 38 },
  { "name": "Carlos", "total_points": 21 }
]
```

---

#### `GET /api/usuarios/{id}`

Devuelve los datos de un usuario específico.

**Response `200`:** Objeto usuario completo.

**Error `404`:** Usuario no encontrado.

---

#### `POST /api/usuarios/buscar`

Busca usuarios por nombre. Útil para invitar amigos a un grupo. Devuelve hasta 10 resultados.

**Body:**
```json
{
  "query": "Juan"
}
```

**Validación:** `query` debe tener al menos 2 caracteres.

**Response `200`:**
```json
[
  { "id": 1, "name": "Juan Pérez",    "total_points": 42 },
  { "id": 5, "name": "Juanita López", "total_points": 15 }
]
```

---

#### `POST /api/usuarios/buscar/mail`

Busca usuarios por dirección de mail. Útil como alternativa a la búsqueda por nombre. Devuelve hasta 5 resultados.

**Body:**
```json
{
  "email": "juan"
}
```

**Validación:** `email` debe tener al menos 2 caracteres.

**Response `200`:**
```json
[
  { "id": 1, "name": "Juan Pérez", "email": "juan@example.com" }
]
```

---

## Modelos de datos

### Partido

```json
{
  "id": 1,
  "match_number": 1,
  "stage": "fase_grupos",
  "status": "pendiente",
  "match_date": "2026-06-11T16:00:00.000000Z",
  "home_score": null,
  "away_score": null,
  "home_extra_score": null,
  "away_extra_score": null,
  "penalty_winner_id": null,
  "tournament_group": {
    "id": 1,
    "name": "A"
  },
  "home_team": {
    "id": 1,
    "name": "México",
    "fifa_code": "MEX",
    "flag_url": "mx"
  },
  "away_team": {
    "id": 2,
    "name": "Sudáfrica",
    "fifa_code": "RSA",
    "flag_url": "za"
  }
}
```

**Valores posibles de `status`:**

| Valor | Descripción |
|---|---|
| `pendiente` | El partido no comenzó |
| `en_juego` | El partido está en curso |
| `finalizado` | El partido terminó |

**Valores posibles de `stage`:** `fase_grupos`, `dieciseisavos`, `octavos`, `cuartos`, `semis`, `tercero`, `final`

> En partidos de eliminatorias que aún no tienen equipos definidos, `home_team` y `away_team` pueden ser `null`.

---

## Sistema de puntos

Los puntos se calculan automáticamente cuando un partido es marcado como `finalizado`. La lógica es acumulativa: cada componente suma por separado.

### Fase de grupos (partidos 1–72)

| Acierto | Puntos |
|---|---|
| Score del local exacto | +1 |
| Score del visitante exacto | +1 |
| Resultado correcto (ganador o empate) | +3 |
| **Máximo posible** | **5** |

### Fase media (dieciseisavos, octavos, cuartos — partidos 73–100)

| Acierto | Puntos |
|---|---|
| Score del local exacto | +2 |
| Score del visitante exacto | +2 |
| Resultado correcto | +4 |
| Bonus: acertaste quién pasa | +2 |
| **Máximo posible** | **10** |

### Fase final (semis, tercer puesto, final — partidos 101–104)

| Acierto | Puntos |
|---|---|
| Score del local exacto | +3 |
| Score del visitante exacto | +3 |
| Resultado correcto | +5 |
| Bonus: acertaste quién pasa | +2 |
| **Máximo posible** | **13** |

### Pronóstico de campeón

El bonus se aplica al finalizar la final, según cuándo fue el último cambio al pronóstico:

| Momento del último cambio | Bonus si acierta |
|---|---|
| Antes del inicio del torneo (partido 1) | +50 |
| Durante la fase de grupos (partidos 1–72) | +30 |
| Durante la fase media (partidos 73–100) | +10 |
| Desde las semifinales en adelante | bloqueado |

---

## Códigos de error

| Código | Significado |
|---|---|
| `200` | OK |
| `201` | Creado exitosamente |
| `401` | No autenticado — falta o es inválido el token |
| `403` | Prohibido — acción no permitida (ej: predecir partido iniciado) |
| `404` | Recurso no encontrado |
| `409` | Conflicto — el recurso ya existe (ej: ya es miembro del grupo) |
| `422` | Error de validación — revisar el campo `errors` en la respuesta |
| `500` | Error interno del servidor |

---

## Notas de implementación

**Sistema de puntos:** Los puntos se calculan automáticamente cuando un partido es marcado como `finalizado`. El cálculo es acumulativo por componente (score local, score visitante, resultado, bonus quién pasa) con valores que escalan según la fase del torneo. Ver la sección [Sistema de puntos](#sistema-de-puntos) para el detalle completo.

**Predicciones vacías:** Al registrarse, cada usuario tiene predicciones creadas para los 104 partidos del torneo con scores en `null`. El frontend puede detectar si una predicción está cargada chequeando si `predicted_home_score` es distinto de `null`.

**Bloqueo de predicciones:** Una vez que `match_date` es menor o igual a la hora actual, la API rechaza modificaciones a esa predicción con un `403`. Se recomienda que el frontend deshabilite el input de predicción basándose en `match_date` para evitar el request innecesario.

**Partidos de eliminatorias:** Los partidos de dieciseisavos se cargan manualmente cuando termina la fase de grupos. Los de octavos en adelante se completan automáticamente cuando terminan los partidos anteriores.

**Fechas:** Todas las fechas se devuelven en formato ISO 8601 en UTC. Se recomienda convertirlas a la zona horaria local del usuario en el frontend.

**Predicción de ganador en eliminatorias:** En partidos de fase eliminatoria, si el usuario predice empate en el marcador, el sistema usa `predicted_winner_team_id` para determinar si acertó el ganador por penales y si corresponde el bonus de +2. Sin ese campo, un empate predicho con score exacto no cobra el bonus.

**Pronóstico de campeón:** Se puede registrar o modificar hasta el inicio de las semifinales. El timestamp del último cambio (`champion_updated_at`) determina el bonus que corresponde si el usuario acierta.