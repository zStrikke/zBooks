// Mi zona (#18): mapa de Leaflet con el punto y el radio de la zona habitual.
// El mapa vive fuera del estado de Alpine: sus objetos no deben ser reactivos.
// Leaflet se carga solo en la página que tiene el mapa (import dinámico).

// Mismo redondeo que App\Geo\ApproximateLocation: el mapa enseña el punto que se guardará
const METROS_REJILLA = 500;
const METROS_POR_GRADO = 111320;

export function redondear(lat, lon) {
    const pasoLat = METROS_REJILLA / METROS_POR_GRADO;
    const latR = Math.round(lat / pasoLat) * pasoLat;
    const pasoLon = pasoLat / Math.max(Math.cos((latR * Math.PI) / 180), 0.01);
    let lonR = Math.round(lon / pasoLon) * pasoLon;
    if (lonR > 180) lonR -= 360;

    return [Number(latR.toFixed(6)), Number(Math.max(lonR, -180).toFixed(6))];
}

const iconoHtml = '<svg width="28" height="38" viewBox="0 0 28 38" aria-hidden="true"><path d="M14 0C6.3 0 0 6.1 0 13.7 0 24 14 38 14 38s14-14 14-24.3C28 6.1 21.7 0 14 0z" fill="#4c3ba0"/><circle cx="14" cy="13.5" r="5.5" fill="#f5c842"/></svg>';

export function zonaMapa(config) {
    let L = null;
    let mapa = null;
    let marcador = null;
    let circulo = null;

    return {
        lat: config.lat,
        lon: config.lon,
        radio: config.radio,
        source: config.source,
        consulta: '',
        resultados: [],
        buscando: false,
        aviso: '',

        get tienePunto() {
            return this.lat !== null && this.lon !== null;
        },

        async init() {
            L = (await import('leaflet')).default;
            mapa = L.map(this.$refs.mapa, { scrollWheelZoom: false }).setView(config.centro, config.zoom);
            L.tileLayer(config.teselas, { maxZoom: 18, attribution: config.atribucion }).addTo(mapa);
            mapa.on('click', (e) => this.elegir(e.latlng.lat, e.latlng.lng, 'map'));

            if (this.tienePunto) {
                this.pintar();
            }
            this.$watch('radio', () => this.pintar());
        },

        elegir(lat, lon, origen) {
            [this.lat, this.lon] = redondear(lat, lon);
            this.source = origen;
            this.resultados = [];
            this.pintar();
        },

        pintar() {
            if (!this.tienePunto || !mapa) return;
            const punto = [this.lat, this.lon];
            const metros = Number(this.radio) * 1000;

            const icono = L.divIcon({ className: '', html: iconoHtml, iconSize: [28, 38], iconAnchor: [14, 38] });
            marcador ??= L.marker(punto, { icon: icono, keyboard: false }).addTo(mapa);
            circulo ??= L.circle(punto, { radius: metros, color: '#4c3ba0', weight: 2.5, fillOpacity: 0.12 }).addTo(mapa);
            marcador.setLatLng(punto);
            circulo.setLatLng(punto).setRadius(metros);
            mapa.fitBounds(circulo.getBounds(), { padding: [16, 16] });
        },

        miUbicacion() {
            if (!navigator.geolocation) {
                this.aviso = config.textos.sinGeolocalizacion;
                return;
            }
            this.aviso = '';
            navigator.geolocation.getCurrentPosition(
                (pos) => this.elegir(pos.coords.latitude, pos.coords.longitude, 'geolocation'),
                () => (this.aviso = config.textos.geolocalizacionDenegada),
                { enableHighAccuracy: false, timeout: 10000 },
            );
        },

        async buscar() {
            if (this.consulta.trim().length < 3) return;
            this.buscando = true;
            this.aviso = '';
            try {
                const respuesta = await fetch(`${config.urlBusqueda}?q=${encodeURIComponent(this.consulta)}`, {
                    headers: { Accept: 'application/json' },
                });
                const datos = await respuesta.json();
                this.resultados = datos.results ?? [];
                if (!respuesta.ok) this.aviso = datos.message ?? config.textos.errorBusqueda;
                else if (this.resultados.length === 0) this.aviso = config.textos.sinResultados;
            } catch {
                this.aviso = config.textos.errorBusqueda;
            } finally {
                this.buscando = false;
            }
        },
    };
}
