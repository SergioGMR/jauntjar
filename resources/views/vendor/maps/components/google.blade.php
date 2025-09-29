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

        let openInfoWindow = null;

        markers.forEach((markerData) => {
            const position = new google.maps.LatLng(
                Number(markerData.position.lat ?? markerData.position.lat),
                Number(markerData.position.lng ?? markerData.position.lng)
            );
            const score = evaluateScore(markerData.info);
            const appearance = getScoreAppearance(score.value);
            const markerMeta = {
                title: markerData.title ?? 'Marcador',
                scoreDisplay: score.display,
                scoreValue: score.value,
                appearance,
            };

            const markerContent = buildContent(markerMeta);

            const marker = hasAdvancedMarkers
                ? new AdvancedMarkerElement({
                    position,
                    map: map,
                    gmpClickable: true,
                    content: typeof PinElement === 'function'
                        ? new PinElement({
                            glyph: appearance.emoji,
                            scale: 1.2,
                            background: appearance.pinFill,
                            borderColor: appearance.pinBorder,
                            glyphColor: '#ffffff'
                        }).element
                        : createFallbackMarkerElement(markerMeta),
                    title: markerMeta.title
                })
                : new google.maps.Marker({
                    position,
                    map: map,
                    title: markerMeta.title
                });

            const infoWindow = new InfoWindow({
                content: markerContent,
            });

            const closeInfoWindow = () => {
                infoWindow.close();
                if (openInfoWindow === infoWindow) {
                    openInfoWindow = null;
                }
            };

            const clickEvent = hasAdvancedMarkers ? 'gmp-click' : 'click';
            marker.addListener(clickEvent, () => {
                if (openInfoWindow && openInfoWindow !== infoWindow) {
                    openInfoWindow.close();
                }

                infoWindow.open({
                    anchor: marker,
                    map: map,
                });

                openInfoWindow = infoWindow;
            });

            infoWindow.addListener('closeclick', closeInfoWindow);

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
        const title = place.title ?? "Marcador";

        content.className = [
            "flex max-w-xs flex-col gap-3 rounded-2xl border p-4 text-slate-900 shadow-xl",
            place.appearance.cardClass,
            place.appearance.cardBorderClass,
        ].join(" ");

        const titleEl = document.createElement("div");
        titleEl.className = "text-lg font-semibold leading-tight text-slate-900";
        titleEl.textContent = title;

        const divider = document.createElement("div");
        divider.className = "h-px w-full " + place.appearance.dividerClass;

        const infoRow = document.createElement("div");
        infoRow.className = "flex items-center justify-between text-sm";

        const badge = document.createElement("span");
        badge.className = "inline-flex items-center justify-center rounded-full px-2 py-0.5 text-[0.7rem] font-semibold uppercase tracking-[0.08em] " + place.appearance.badgeClass;
        badge.textContent = "Info";

        const scoreWrap = document.createElement("span");
        scoreWrap.className = "flex items-center gap-1 text-base font-semibold " + place.appearance.scoreTextClass;

        const scoreEmoji = document.createElement("span");
        scoreEmoji.className = "text-lg";
        scoreEmoji.textContent = place.appearance.emoji;

        const scoreValue = document.createElement("span");
        scoreValue.textContent = place.scoreDisplay;

        scoreWrap.append(scoreEmoji, scoreValue);
        infoRow.append(badge, scoreWrap);
        content.append(titleEl, divider, infoRow);

        return content;
    }

    function createFallbackMarkerElement(place) {
        const element = document.createElement("div");
        element.className = [
            "flex h-12 w-12 items-center justify-center rounded-full border-2 text-lg font-semibold text-white shadow-lg ring",
            place.appearance.pinFallbackClass,
        ].concat(place.appearance.pinBorderClasses, place.appearance.ringClasses).join(" ");
        element.textContent = place.appearance.emoji;

        return element;
    }

    function evaluateScore(rawValue) {
        const numeric = Number(rawValue);

        if (!Number.isFinite(numeric)) {
            return {
                value: 0,
                display: '0.0',
            };
        }

        const normalized = numeric > 10 ? numeric / 10 : numeric;
        const value = Math.min(10, Math.max(0, normalized));

        return {
            value,
            display: value.toFixed(1),
        };
    }

    function getScoreAppearance(scoreValue) {
        const clamped = Math.min(10, Math.max(0, Number(scoreValue) || 0));

        const themes = [
            {
                threshold: 9,
                emoji: '🌟',
                pinFill: '#047857',
                pinBorder: '#065f46',
                cardClass: 'bg-gradient-to-br from-emerald-50 via-emerald-100 to-emerald-200',
                cardBorderClass: 'border-emerald-200',
                dividerClass: 'bg-gradient-to-r from-emerald-400 via-emerald-200 to-transparent',
                badgeClass: 'bg-emerald-100 text-emerald-700',
                scoreTextClass: 'text-emerald-700',
                pinFallbackClass: 'bg-gradient-to-br from-emerald-500 to-emerald-400',
                pinBorderClasses: ['border-emerald-600'],
                ringClasses: ['ring-emerald-200'],
            },
            {
                threshold: 7.5,
                emoji: '😄',
                pinFill: '#15803D',
                pinBorder: '#166534',
                cardClass: 'bg-gradient-to-br from-lime-50 via-lime-100 to-lime-200',
                cardBorderClass: 'border-lime-200',
                dividerClass: 'bg-gradient-to-r from-lime-400 via-lime-200 to-transparent',
                badgeClass: 'bg-lime-100 text-lime-700',
                scoreTextClass: 'text-lime-700',
                pinFallbackClass: 'bg-gradient-to-br from-lime-500 to-lime-400',
                pinBorderClasses: ['border-lime-600'],
                ringClasses: ['ring-lime-200'],
            },
            {
                threshold: 5,
                emoji: '🙂',
                pinFill: '#0284C7',
                pinBorder: '#0369A1',
                cardClass: 'bg-gradient-to-br from-sky-50 via-sky-100 to-sky-200',
                cardBorderClass: 'border-sky-200',
                dividerClass: 'bg-gradient-to-r from-sky-400 via-sky-200 to-transparent',
                badgeClass: 'bg-sky-100 text-sky-700',
                scoreTextClass: 'text-sky-700',
                pinFallbackClass: 'bg-gradient-to-br from-sky-500 to-sky-400',
                pinBorderClasses: ['border-sky-600'],
                ringClasses: ['ring-sky-200'],
            },
            {
                threshold: 3,
                emoji: '😕',
                pinFill: '#F59E0B',
                pinBorder: '#D97706',
                cardClass: 'bg-gradient-to-br from-amber-50 via-amber-100 to-amber-200',
                cardBorderClass: 'border-amber-200',
                dividerClass: 'bg-gradient-to-r from-amber-400 via-amber-200 to-transparent',
                badgeClass: 'bg-amber-100 text-amber-700',
                scoreTextClass: 'text-amber-700',
                pinFallbackClass: 'bg-gradient-to-br from-amber-500 to-amber-400',
                pinBorderClasses: ['border-amber-600'],
                ringClasses: ['ring-amber-200'],
            },
            {
                threshold: 0,
                emoji: '😞',
                pinFill: '#DC2626',
                pinBorder: '#B91C1C',
                cardClass: 'bg-gradient-to-br from-rose-50 via-rose-100 to-rose-200',
                cardBorderClass: 'border-rose-200',
                dividerClass: 'bg-gradient-to-r from-rose-400 via-rose-200 to-transparent',
                badgeClass: 'bg-rose-100 text-rose-700',
                scoreTextClass: 'text-rose-700',
                pinFallbackClass: 'bg-gradient-to-br from-rose-500 to-rose-400',
                pinBorderClasses: ['border-rose-600'],
                ringClasses: ['ring-rose-200'],
            },
        ];

        return themes.find(({ threshold }) => clamped >= threshold) ?? themes[themes.length - 1];
    }


    queueMicrotask(initMap{{ $mapId }});
</script>
