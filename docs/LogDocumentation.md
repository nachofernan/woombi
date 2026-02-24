# Log de desarrollo — Prode Mundial 2026

## Limpieza del sistema de puntos en grupos (`group_user`)

Se eliminó el campo `total_points` de la tabla pivot `group_user`. El valor era siempre idéntico al de `users.total_points`, por lo que su existencia solo agregaba redundancia y riesgo de desincronización. La tabla de posiciones de cada grupo ahora lee directamente desde `users`.

---

## Refactor del `MatcheObserver` — `updatePoints` y `calculateChampion`

Se simplificaron ambos métodos eliminando el loop sobre grupos que actualizaba el pivot. `updatePoints` ahora solo actualiza `users.total_points`. `calculateChampion` idem.

---

## Sistema de bonus por pronóstico de campeón escalonado

Se reemplazó el bloqueo fijo al 11 de junio por un sistema de tres niveles basado en el avance del torneo. El bonus baja según cuándo fue el último cambio del pronóstico: 50 puntos antes del partido 1, 30 entre el partido 1 y el 73, y 10 entre el 73 y el 101. Desde el partido 101 (semis) queda bloqueado. Se agregó el campo `champion_updated_at` en `users` para registrar el timestamp del último cambio.

---

## Nuevo sistema de puntos por partido

Se rediseñó completamente la lógica de puntuación. Los puntos son acumulativos: se suman por score home acertado, score away acertado, resultado correcto, y bonus por quién pasa. Los valores crecen según el stage: grupos (1/3/—), medio 73-100 (2/4/+2), finales 101-104 (3/5/+2). En grupos no hay bonus de quién pasa. El máximo posible es 5 en grupos, 10 en el medio y 13 en finales.

---

## Centralización de constantes en `config/prode.php`

Se creó el archivo de configuración `config/prode.php` para centralizar los IDs de partidos clave (kickoff, 16avos, semis) y todos los valores de puntos. Evita números mágicos dispersos en el código.

---

## Fix en `resolveNextSlot` — `first()` por `get()`

El observer usaba `->first()` para encontrar el partido siguiente en la llave, lo que dejaba sin equipo al segundo partido que apuntaba a la misma fuente (caso concreto: final y tercer puesto apuntan ambos a las semis). Se cambió a `->get()` con loop.

---

## Comando `prode:simular`

Comando Artisan interactivo para simular resultados random en partidos pendientes. Detecta el próximo partido sin finalizar, pregunta cuántos simular, asigna scores entre 0-4, y genera `penalty_winner_id` random en eliminatorias con empate. Para 16avos asigna equipos random a los slots vacíos ya que ese stage no tiene fuente automática. Incluye `--reset` para volver todos los partidos al estado inicial sin truncar tablas.

---

## Tests de `MatcheObserverTest`

Se agregaron tests de integración que cubren los casos principales del observer: score exacto, solo resultado, un score parcial, pronóstico errado, empate con penalty winner correcto e incorrecto, y bonus de campeón. Cada test verifica el `total_points` del usuario después de que el observer procesa el partido finalizado.