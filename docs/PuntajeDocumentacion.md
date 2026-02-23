# Sistema de Puntos — Prode Mundial 2026

## Resumen rápido (para el que no quiere leer todo)

Los puntos son acumulativos. Cada partido tiene hasta 4 cosas que podés acertar: el gol del local, el gol del visitante, el resultado (quién ganó o empató), y en eliminatorias quién pasa. Cada una suma por separado, y los valores crecen a medida que avanza el torneo. El pronóstico de campeón es un bonus aparte que se cobra al final.

---

## Estructura de puntos por partido

### Fase de grupos (partidos 1–72)

| Acierto | Puntos |
|---|---|
| Score home exacto | +1 |
| Score away exacto | +1 |
| Resultado correcto (ganador o empate) | +3 |
| **Máximo posible** | **5** |

No hay bonus en esta fase. Acertar el score exacto de ambos equipos implica acertar el resultado, así que el máximo se da cuando acertás los tres componentes.

### Fase media (partidos 73–100: 16avos, octavos, cuartos)

| Acierto | Puntos |
|---|---|
| Score home exacto | +2 |
| Score away exacto | +2 |
| Resultado correcto | +4 |
| Bonus: acertaste quién pasa | +2 |
| **Máximo posible** | **10** |

El bonus de quién pasa aplica siempre. Si pronosticaste un resultado sin empate y acertaste el ganador, el bonus queda implícito. Si pronosticaste empate, necesitás haber especificado el `predicted_winner_team_id` para cobrarlo.

### Fase final (partidos 101–104: semis, tercer puesto, final)

| Acierto | Puntos |
|---|---|
| Score home exacto | +3 |
| Score away exacto | +3 |
| Resultado correcto | +5 |
| Bonus: acertaste quién pasa | +2 |
| **Máximo posible** | **13** |

---

## Lógica de cálculo

Los puntos no son excluyentes. La cuenta siempre es:

```
pts = 0

if (ph == sh) pts += scorePoints        // 1, 2 o 3 según stage
if (pa == sa) pts += scorePoints
if (resultado_acertado) pts += resultPoints   // 3, 4 o 5 según stage
if (fase_grupos && resultado_acertado) OR (eliminatorias && quien_pasa_acertado) pts += 2
```

Donde `resultado_acertado` es que la dirección del resultado coincide: ganó local, ganó visitante, o empate. En eliminatorias con empate, `quien_pasa_acertado` requiere que `predicted_winner_team_id` coincida con el ganador real (por penales incluido).

Los cortes de stage se determinan por ID de partido, no por nombre de stage, para evitar hardcodear strings y centralizar en `config/prode.php`:

```php
'match_kickoff' => 1,   // arranca el mundial
'match_16avos'  => 73,  // arranca fase media
'match_semis'   => 101, // arranca fase final
```

---

## Pronóstico de campeón

Cada usuario puede registrar un equipo campeón antes del inicio del torneo. El bonus se calcula según cuándo fue el último cambio:

| Momento del último cambio | Bonus si acierta |
|---|---|
| Antes del partido 1 | +50 |
| Entre partido 1 y 73 | +30 |
| Entre partido 73 y 101 | +10 |
| Desde partido 101 en adelante | bloqueado |

El timestamp se guarda en `champion_updated_at` en la tabla `users`. Si el usuario cambia su pronóstico durante la fase de grupos, pierde el derecho a los 50 puntos y pasa a jugar por 30. El sistema siempre toma el último cambio registrado.

---

## Análisis de distribución

### Máximo posible por fase

| Fase | Partidos | Max/partido | Total máximo | % del total |
|---|---|---|---|---|
| Grupos | 72 | 5 | 360 | 53% |
| Medio | 28 | 10 | 280 | 41% |
| Finales | 4 | 13 | 52 | 8% |
| **Total partidos** | **104** | | **692** | |
| Campeón | — | — | 50 | — |
| **Techo absoluto** | | | **742** | |

### Promedio esperado (acertás mitad de resultados, un tercio de scores, uno de cada diez exactos)

| Fase | Pts esperados | % del total |
|---|---|---|
| Grupos | ~155 | ~48% |
| Medio | ~121 | ~37% |
| Finales | ~22 | ~7% |
| Campeón (promedio) | ~25 | ~8% |
| **Total** | **~323** | |

El rango realista de un jugador random está entre 250 y 400 puntos. La diferencia entre alguien atento y alguien que pone cualquier cosa probablemente no supere los 150 puntos sobre el total del torneo, lo que mantiene el prode competitivo hasta el final.

---

## Por qué estas decisiones

**¿Por qué los puntos crecen con el stage?**
Para que el torneo no se decida en la fase de grupos. Con valores fijos, el 68% de los puntos máximos se jugaban en grupos y los últimos 4 partidos apenas movían la tabla. Con la escala actual, grupos representa el 53% del máximo y el medio casi iguala ese peso a pesar de tener menos de la mitad de los partidos.

**¿Por qué no hay bonus en grupos?**
Se analizó incluir el +2 por acertar resultado en grupos, lo que llevaba el máximo por partido a 7. El problema es que inflaba demasiado el peso de la fase de grupos (68% del total) y hacía que las fases finales fueran casi irrelevantes para remontar. Quitarlo bajó ese porcentaje a 53% y equilibró mejor la distribución.

**¿Por qué el bonus de quién pasa es fijo en +2?**
Porque es un evento binario (pasa o no pasa) y agregarle escala lo complicaba sin agregar demasiado valor. Los 2 puntos son suficientes para incentivar pensar el empate en eliminatorias sin distorsionar la tabla general.

**¿Por qué el campeón tiene tres niveles de bonus?**
Para mantener el pronóstico de campeón relevante durante todo el torneo, no solo al inicio. Si el único incentivo era registrarlo antes del día 1, mucha gente lo ignoraba o lo ponía sin pensar. Con la escala decreciente, hay una decisión real en cada corte: ¿cambio y pierdo puntos potenciales, o me quedo con mi pronóstico original?

---

## Posibles ajustes futuros

Estas son ideas conceptuales, no están implementadas ni probadas:

- **Subir más agresivamente el medio y final:** si la brecha entre líderes en grupos es muy grande, los partidos de eliminatorias pierden emoción. Doblar los multiplicadores del medio (4/8 en vez de 2/4) equilibraría aún más la distribución pero cambiaría bastante el rango de puntajes esperados.

- **Bonus por racha:** sumar puntos extra si acertás N partidos consecutivos. Agrega emoción pero complica el cálculo y puede frustrar si se corta la racha por un partido raro.

- **Penalización por no pronosticar:** actualmente un pronóstico vacío da 0 puntos, igual que uno errado. Podría penalizarse con -1 para incentivar la participación, aunque esto puede frustrar usuarios que se incorporan tarde.

- **Ajuste del campeón post-grupos:** si el bonus de 30 puntos entre partido 1 y 73 parece mucho o poco según cómo evolucione la competencia, es el valor más fácil de ajustar sin romper nada, ya que está en `config/prode.php`.