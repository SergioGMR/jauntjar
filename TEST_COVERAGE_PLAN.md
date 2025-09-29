# Pest Coverage Roadmap

## Diagnóstico inicial
- [ ] Ejecutar `./vendor/bin/pest --coverage` y registrar resultados base (global y por namespace).  
  Nota: Bloqueado hasta habilitar Xdebug o PCOV (`No code coverage driver is available.`)
- [x] Priorizar módulos críticos (`app/Models`, `app/Filament`, `app/Livewire`) según impacto de negocio.  
  Prioridad: 1) Recursos Filament (Budget, Destination, Country) por exposición administrativa. 2) Modelos con relaciones y scopes (`Budget`, `Destination`, `Country`, `City`, `Classification`). 3) Componentes Livewire (`VisitadosListado`, `PlaneadosListado`, `ClasificadosListado`, `Components/MapsGoogle`). 4) Acciones y rutas HTTP auxiliares (`app/Http`, `routes/web.php`).

## Planificación de casos
- [ ] Mapear comportamientos nominales, bordes y fallos esperados para cada recurso (migraciones, policies, acciones de Filament, componentes Livewire).
- [ ] Documentar el plan por módulo en tickets o tablas, asignando responsables y fechas objetivo.

## Datos y fixtures
- [ ] Crear/ajustar factories y seeders específicos en `database/factories` y `database/seeders` para cubrir asociaciones y estados relevantes.
- [ ] Incorporar `refreshDatabase()` y `artisan migrate:fresh --seed` en los `beforeEach` donde se requiera un estado consistente.

## Pruebas Feature
- [ ] Expandir `tests/Feature` para cubrir rutas, policies y flujos completos usando helpers Pest (`describe`, `dataset`).
- [ ] Simular eventos externos con `Bus::fake()`, `Event::fake()` y validar side-effects (jobs, notificaciones, broadcasts).

## Pruebas Unitarias
- [ ] Añadir coberturas unitarias para lógica pura (scopes, castings, servicios) en `tests/Unit`.
- [ ] Usar datasets Pest para ampliar inputs edge-case (nulos, duplicados, límites) y asegurar contratos de interfaces.

## Automatización y control
- [ ] Configurar verificación local/CI que ejecute `vendor/bin/pint` y `./vendor/bin/pest --coverage` en cada push o PR.
- [ ] Definir umbral mínimo (p.ej. 85%) y fallar el pipeline cuando el coverage caiga por debajo del objetivo.
- [ ] Revisar PRs con checklist de rutas testadas, mocks utilizados y fixtures añadidos; refactorizar tests duplicados a traits/helpers compartidos.

## Seguimiento continuo
- [ ] Repetir `./vendor/bin/pest --coverage` tras cada bloque de cambios y actualizar métricas.  
  Plan: Agendar corrida semanal en CI una vez habilitado el driver y registrar porcentajes en el tablero de calidad.
- [ ] Ajustar el plan cuando se introduzcan módulos o features nuevos para evitar regresiones en la cobertura.  
  Acción inicial: Revaluar cobertura al cerrar cada sprint y abrir tickets de refuerzo si baja el umbral definido.
