@php
    $markerPayload = collect($markers ?? [])->map(function ($marker) {
        $lat = $marker['lat'] ?? $marker[0] ?? null;
        $lng = $marker['long'] ?? $marker[1] ?? null;

        if ($lat === null || $lng === null) {
            return null;
        }

        return [
            'position' => [
                'lat' => $lat,
                'lng' => $lng,
            ],
            'title' => $marker['title'] ?? 'Marcador',
            'info' => $marker['info'] ?? null,
        ];
    })->filter()->values();

    $mapCenter = [
        'lat' => $centerPoint['lat'] ?? $centerPoint[0] ?? 0,
        'lng' => $centerPoint['long'] ?? $centerPoint[1] ?? 0,
    ];
@endphp

<div class="h-full w-full" id="{{ $mapId }}"></div>

<!-- prettier-ignore -->
<script>
(g => {
let h, a, k, p = "The Google Maps JavaScript API", c = "google", l =
"importLibrary", q = "__ib__",
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
d[l] ? console.warn(p + " only loads once. Ignoring:", g) : d[l] = (f, ...n) =>
r.add(f) && u().then(() => d[l](f, ...n))
})
({key: "{{ config("maps.google_maps.access_token") }}", v: "beta"});
</script>

<script>
    async function initMap{{ $mapId }}() {
        if (typeof google === "undefined" || typeof google.maps ===
            "undefined" || typeof google.maps.importLibrary !== "function") {
            requestAnimationFrame(initMap{{ $mapId }});
            return;
        }

        if (!document.getElementById("{{ $mapId }}")) {
            console.error(
                "El contenedor del mapa no existe: {{ $mapId }}");
            return;
        }

        const {
            Map,
            InfoWindow
        } = await google.maps.importLibrary("maps");
        const markerLibrary = await google.maps.importLibrary("marker").catch(() => ({}));
        const {
            AdvancedMarkerElement,
            PinElement
        } = markerLibrary;

        const hasAdvancedMarkers = typeof AdvancedMarkerElement === "function";

        const centerPoint = @json($mapCenter);
        const markers = @json($markerPayload);
        const shouldFitBounds = @json($fitToBounds);
        const shouldCenterToBounds = @json($centerToBoundsCenter);

        const map = new Map(document.getElementById(
            "{{ $mapId }}"), {
            center: centerPoint,
            zoom: {{ $zoomLevel }},
            mapTypeId: "{{ $mapType }}",
            mapId: "{{ $mapId }}"
        });

        let bounds = null;
        if (shouldFitBounds || shouldCenterToBounds) {
            bounds = new google.maps.LatLngBounds();
        }

        markers.forEach((markerData) => {
            const position = new google.maps.LatLng(
                Number(markerData.position.lat ?? markerData.position.lat),
                Number(markerData.position.lng ?? markerData.position.lng)
            );
            const markerMeta = {
                title: markerData.title ?? 'Marcador',
                info: markerData.info ?? '',
            };

            const markerContent = buildContent(markerMeta);

            const marker = hasAdvancedMarkers
                ? new AdvancedMarkerElement({
                    position,
                    map: map,
                    gmpClickable: true,
                    content: typeof PinElement === 'function'
                        ? new PinElement({
                            glyph: '👍🏻',
                            scale: 1.2,
                            background: 'green',
                            borderColor: 'black',
                            glyphColor: 'white'
                        }).element
                        : createFallbackMarkerElement(markerMeta),
                    title: markerMeta.title
                })
                : new google.maps.Marker({
                    position,
                    map: map,
                    title: markerMeta.title
                });

            if (markerData.info) {
                const infoWindow = new InfoWindow({
                    content: markerContent
                });

                const clickEvent = hasAdvancedMarkers ? 'gmp-click' : 'click';
                marker.addListener(clickEvent, () => {
                    infoWindow.open({
                        anchor: marker,
                        map: map
                    });
                });
            }

            if (bounds) {
                bounds.extend(position);
            }
        });

        if (bounds && markers.length === 0) {
            bounds.extend(centerPoint);
        }

        if (bounds && shouldFitBounds && !bounds.isEmpty()) {
            map.fitBounds(bounds);
        }

        if (bounds && shouldCenterToBounds && !bounds.isEmpty()) {
            map.setCenter(bounds.getCenter());
        }

    }

    function buildContent(place) {
        const content = document.createElement("div");
        content.className =
            "flex flex-col items-center bg-emerald-400 p-3 rounded-lg shadow-lg text-sm font-bold cursor-pointer";
        content.innerHTML = `
            <div class="text-lg">${place.title}</div>
            <div class="text-xs text-gray-700">${place.info ?? ""}</div>
        `;
        return content;
    }

    function createFallbackMarkerElement(place) {
        const element = document.createElement("div");
        element.className =
            "grid place-items-center rounded-full bg-emerald-500 p-2 text-white shadow-lg";
        element.textContent = place.title?.charAt(0) ?? "•";
        return element;
    }

    queueMicrotask(initMap{{ $mapId }});
</script>
