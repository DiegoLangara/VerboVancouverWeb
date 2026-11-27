# VerboVancouverWeb

Sitio nuevo de la Iglesia Cristiana Verbo Vancouver. Concepto visual: **"Luz que habita"**.

Tema de bloques `verbovancouver` con bloques ACF (requiere ACF Pro) y gulp, que compila `src/` hacia `public_html/wp-content/`.

## Estructura

```
src/assets/   SCSS, JS e imágenes globales  → themes/verbovancouver/assets
src/theme/    PHP, theme.json, plantillas y bloques ACF → themes/verbovancouver
src/plugins/  → wp-content/plugins
src/mu/       → wp-content/mu-plugins
```

## Bloques del homepage

| Bloque | Qué hace |
| --- | --- |
| Hero Luz que habita | Barras de luz y cruz que se encienden y se abren con el scroll |
| Pilares | Adoración, Comunidad y Crecimiento en paneles con scroll horizontal |
| Planea tu visita | Horario, dirección, cómo llegar y cuenta regresiva al próximo servicio |
| Última prédica | Video de YouTube con carga diferida |
| Comunidad | Ministerios en lista que se ilumina al pasar |
| Siguiente paso | Visitar, Conectar y Dar |

Todos los bloques tienen textos por defecto, así que la plantilla `front-page.html` se ve completa recién instalada. Se editan desde el editor de bloques.

## Compilar en el servidor dev

Desde la carpeta del sitio (donde está `public_html`):

```bash
git pull
npm install
./node_modules/.bin/gulp default      # o: gulp watch mientras se edita
```

Las animaciones usan GSAP + ScrollTrigger (incluidos en `verbovancouver-libs.js`) y respetan `prefers-reduced-motion`.
