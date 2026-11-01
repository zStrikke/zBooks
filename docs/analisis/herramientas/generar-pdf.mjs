// Genera el PDF del análisis funcional a partir de analisis-funcional.md.
//
// Requisitos: pandoc y Node con Playwright (`npm i -g playwright && npx playwright install chromium`).
// Uso: make analisis (o NODE_PATH=$(npm root -g) node docs/analisis/herramientas/generar-pdf.mjs)
import { execFileSync } from 'node:child_process';
import { readFileSync, rmSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { tmpdir } from 'node:os';
import { createRequire } from 'node:module';

// require() acepta NODE_PATH, así sirve un Playwright instalado de forma global
const { chromium } = createRequire(import.meta.url)('playwright');

const aqui = dirname(fileURLToPath(import.meta.url));
const raiz = join(aqui, '..');
const fuente = join(raiz, 'analisis-funcional.md');

// La versión sale de la cabecera YAML del propio documento
const version = readFileSync(fuente, 'utf8').match(/^version:\s*"([^"]+)"/m)?.[1];
if (!version) throw new Error('Falta «version» en la cabecera de analisis-funcional.md');

const html = join(tmpdir(), `zbooks-analisis-${version}.html`);
const pdf = join(raiz, `zBooks_Analisis_Funcional_v${version}.pdf`);

execFileSync('pandoc', [
    fuente, '--standalone', '--embed-resources', '--section-divs', '--number-sections',
    '--reference-location=section', '--css', join(aqui, 'estilo.css'),
    '--metadata', `pagetitle=zBooks – Análisis funcional v${version}`, '-o', html,
], { stdio: 'inherit' });

// Guion que no se parte en los identificadores (RF-01, RNF-04…), para que no se corten en la tabla
writeFileSync(html, readFileSync(html, 'utf8').replace(/>(RF|RNF|CU)-/g, '>$1\u2011'));

const navegador = await chromium.launch();
const pagina = await navegador.newPage();
await pagina.goto(`file://${html}`);
await pagina.pdf({
    path: pdf,
    format: 'A4',
    printBackground: true,
    preferCSSPageSize: true,
    displayHeaderFooter: true,
    headerTemplate: '<span></span>',
    footerTemplate: `<div style="width:100%;font-size:7pt;color:#5b6770;padding:0 16mm;display:flex;justify-content:space-between">
        <span>zBooks – Análisis funcional y planificación v${version}</span>
        <span>Página <span class="pageNumber"></span> de <span class="totalPages"></span></span></div>`,
});
await navegador.close();
rmSync(html);

console.log(`PDF generado: ${pdf}`);
