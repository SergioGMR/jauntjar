<div id="{{ $mapId }}" class="h-full w-full"></div>

<!-- prettier-ignore -->
<script>
    (g => {
        let h, a, k, p = "The Google Maps JavaScript API", c = "google", l = "importLibrary", q = "__ib__",
            m = document, b = window;
        b = b[c] || (b[c] = {});
        let d = b.maps || (b.maps = {}), r = new Set, e = new URLSearchParams,
            u = () => h || (h = new Promise(async (f, n) => {
                await (a = m.createElement("script"));
                e.set("libraries", "marker");
                for (k in g) e.set(k.replace(/[A-Z]/g, t => "_" + t[0].toLowerCase()), g[k]);
                e.set("callback", c + ".maps." + q);
                a.src = `https://maps.googleapis.com/maps/api/js?` + e;
                d[q] = f;
                a.onerror = () => h = n(Error(p + " could not load."));
                a.nonce = m.querySelector("script[nonce]")?.nonce || "";
                m.head.append(a)
            }));
        d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n))
    })
    ({key: "{{ config('maps.google_maps.access_token') }}", v: "beta"});
</script>

<script>
    async function initMap{{$mapId}}() {
        if (typeof google === "undefined" || typeof google.maps === "undefined") {
            console.error("Google Maps API no está cargada correctamente");
            return;
        }

        if (!document.getElementById("{{$mapId}}")) {
            console.error("El contenedor del mapa no existe: {{$mapId}}");
            return;
        }

        const {Map, InfoWindow} = await google.maps.importLibrary("maps");
        const {AdvancedMarkerElement, PinElement} = await google.maps.importLibrary("marker");

        const map = new Map(document.getElementById("{{$mapId}}"), {
            center: {lat: {{$centerPoint['lat'] ?? $centerPoint[0]}}, lng: {{$centerPoint['long'] ?? $centerPoint[1]}}},
            zoom: {{$zoomLevel}},
            mapTypeId: "{{$mapType}}",
            mapId: "{{$mapId}}"
        });

        @if($fitToBounds || $centerToBoundsCenter)
        let bounds = new google.maps.LatLngBounds();
        @endif

        @foreach($markers as $index => $marker)
        const markerContent{{$index}} = buildContent({
            title: "{{ $marker['title'] ?? 'Marcador' }}",
            info: @json($marker['info'] ?? ''),
        });

        const pinEmoji{{$index}} = new PinElement({
            glyph: "👍🏻",
            scale: 1.2,
            background: "green",
            borderColor: "black",
            glyphColor: "white",
        })

        const marker{{$index}} = new AdvancedMarkerElement({
            position: {lat: {{$marker['lat'] ?? $marker[0]}}, lng: {{$marker['long'] ?? $marker[1]}}},
            map: map,
            gmpClickable: true,
            content: pinEmoji{{$index}}.element
        });

        @if(isset($marker['info']))
        const infoWindow{{$index}} = new InfoWindow({
            content: markerContent{{$index}}
        });
        marker{{$index}}.addListener("gmp-click", function () {
            infoWindow{{$index}}.open(map, marker{{$index}});
        });
        @endif

        @if($fitToBounds || $centerToBoundsCenter)
        bounds.extend(marker{{$index}}.position);
        @endif
        @endforeach

        @if($fitToBounds)
        map.fitBounds(bounds);
        @endif

        @if($centerToBoundsCenter)
        map.setCenter(bounds.getCenter());
        @endif
    }

    function buildContent(place) {
        const content = document.createElement("div");
        content.className = "flex flex-col items-center bg-emerald-400 p-3 rounded-lg shadow-lg text-sm font-bold cursor-pointer";
        content.innerHTML = `
            <div class="text-lg">${place.title}</div>
            <div class="text-xs text-gray-700">${place.info}</div>
        `;
        return content;
    }

    if (typeof google !== "undefined" && typeof google.maps !== "undefined") {
        initMap{{$mapId}}();
    } else {
        console.error("Google Maps API no cargó correctamente");
    }
</script>
