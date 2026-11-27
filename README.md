# VerboVancouverWeb

Sitio nuevo de la Iglesia Cristiana Verbo Vancouver. Concepto visual: **"Luz que habita"**.

## Estructura

```
theme/verbo-luz/   Tema WordPress a medida (PHP + Vite + GSAP + Lenis)
  inc/             setup, carga de assets (manifest de Vite) y tipos de contenido
  src/             CSS y JS fuente (se compilan a dist/)
```

Tipos de contenido incluidos: Prédicas (con taxonomía Series), Eventos, Ministerios, IGHO, Testimonios y Líderes.

## Trabajar en el tema

Requisitos: Node 20+ y PHP 8.1+.

```bash
cd theme/verbo-luz
npm install
npm run build      # genera dist/ para el servidor
npm run dev        # servidor de Vite con recarga en vivo
```

Para usar `npm run dev` con WordPress, añade en `wp-config.php` del entorno de desarrollo:

```php
define( 'VERBO_LUZ_VITE_DEV', true );
```

## Instalar en el servidor dev

En el servidor (Virtualmin), dentro de `wp-content/themes/` del sitio dev:

```bash
git clone https://github.com/DiegoLangara/VerboVancouverWeb.git verbovancouverweb
ln -s verbovancouverweb/theme/verbo-luz verbo-luz
cd verbo-luz && npm ci && npm run build
```

Luego activa "Verbo Luz" en Apariencia → Temas. Para actualizar: `git pull && npm run build`.

`dist/` no se versiona; se compila en el servidor.
