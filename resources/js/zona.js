// Mi zona (#18): mapa de Leaflet con el punto y el radio de la zona habitual.
// El mapa vive fuera del estado de Alpine: sus objetos no deben ser reactivos.
// Leaflet se carga solo en la página que tiene el mapa (import dinámico).

// Mismo redondeo que App\Geo\ApproximateLocation: el mapa enseña el punto que se guardará
const METROS_REJILLA = 250;
const METROS_POR_GRADO = 111320;

// El buscador espera a que se deje de escribir: Nominatim admite una petición por segundo
const ESPERA_BUSQUEDA_MS = 500;
const MINIMO_LETRAS = 3;

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
    let temporizador = null;
    let peticion = null;

    return {
        lat: config.lat,
        lon: config.lon,
        // En kilómetros en la pantalla; el formulario envía metros (radius_m)
        radio: config.radio,
        source: config.source,
        consulta: '',
        resultados: [],
        buscando: false,
        localizando: false,
        aviso: '',

        get tienePunto() {
            return this.lat !== null && this.lon !== null;
        },

        get radioMetros() {
            return Math.round(Number(this.radio) * 1000);
        },

        get radioTexto() {
            return Number(this.radio).toLocaleString(config.idioma, { maximumFractionDigits: 1 });
        },

        async init() {
            this.$watch('radio', () => this.pintar());
            this.$watch('consulta', () => this.programarBusqueda());

            L = (await import('leaflet')).default;
            mapa = L.map(this.$refs.mapa, { scrollWheelZoom: false }).setView(config.centro, config.zoom);
            L.tileLayer(config.teselas, { maxZoom: 18, attribution: config.atribucion }).addTo(mapa);
            mapa.on('click', (e) => this.elegir(e.latlng.lat, e.latlng.lng, 'map'));

            this.pintar();
        },

        elegir(lat, lon, origen) {
            [this.lat, this.lon] = redondear(lat, lon);
            this.source = origen;
            this.resultados = [];
            this.aviso = '';
            this.pintar();
        },

        pintar() {
            if (!this.tienePunto || !mapa) return;
            const punto = [this.lat, this.lon];

            const icono = L.divIcon({ className: '', html: iconoHtml, iconSize: [28, 38], iconAnchor: [14, 38] });
            marcador ??= L.marker(punto, { icon: icono, keyboard: false }).addTo(mapa);
            circulo ??= L.circle(punto, { color: '#4c3ba0', weight: 2.5, fillOpacity: 0.12 }).addTo(mapa);
            marcador.setLatLng(punto);
            circulo.setLatLng(punto).setRadius(this.radioMetros);
            mapa.fitBounds(circulo.getBounds(), { padding: [16, 16] });
        },

        miUbicacion() {
            if (!navigator.geolocation) {
                this.aviso = config.textos.sinGeolocalizacion;
                return;
            }
            this.aviso = '';
            this.localizando = true;
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.localizando = false;
                    this.elegir(pos.coords.latitude, pos.coords.longitude, 'geolocation');
                },
                () => {
                    this.localizando = false;
                    this.aviso = config.textos.geolocalizacionDenegada;
                },
                { enableHighAccuracy: false, timeout: 15000, maximumAge: 60000 },
            );
        },

        programarBusqueda() {
            clearTimeout(temporizador);
            if (this.consulta.trim().length < MINIMO_LETRAS) {
                peticion?.abort();
                this.resultados = [];
                this.buscando = false;
                return;
            }
            this.buscando = true;
            temporizador = setTimeout(() => this.buscar(), ESPERA_BUSQUEDA_MS);
        },

        async buscar() {
            peticion?.abort();
            peticion = new AbortController();
            this.aviso = '';
            try {
                const respuesta = await fetch(`${config.urlBusqueda}?q=${encodeURIComponent(this.consulta)}`, {
                    headers: { Accept: 'application/json' },
                    signal: peticion.signal,
                });
                const datos = await respuesta.json();
                this.resultados = datos.results ?? [];
                if (!respuesta.ok) this.aviso = datos.message ?? config.textos.errorBusqueda;
                else if (this.resultados.length === 0) this.aviso = config.textos.sinResultados;
                this.buscando = false;
            } catch (error) {
                // Una búsqueda cancelada porque se siguió escribiendo no es un error
                if (error.name === 'AbortError') return;
                this.aviso = config.textos.errorBusqueda;
                this.buscando = false;
            }
        },
    };
}
