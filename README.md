# Task Manager — Ejercicio de Arquitectura

Proyecto de práctica basado en una prueba técnica real, implementado en **dos ramas** con arquitecturas distintas sobre el mismo dominio, para comparar enfoques.

## Stack

- Laravel 13
- Livewire 4 (Single File Components)
- Flux UI
- PHPUnit / Pest
- MySQL

## Ramas

| Rama | Enfoque |
|---|---|
| `layered` | Service + Repository + Policy, sin ports/adapters explícitos |
| `hexagonal` | Ports & Adapters organizados por módulo; Eloquent Model como entidad de dominio (pragmático) |
| `hexagonal-pure` | Ports & Adapters con entidades de dominio desacopladas de Eloquent (POPOs + mapeo explícito) |

## Dominio

- **Project**: dueño (`owner`), miembros (`belongsToMany` vía `project_user`), soft delete con cascada hacia sus `Task`.
- **Task**: pertenece a un `Project`, tiene creador y asignado (`assigned_to`), estado y prioridad como enums traducibles, soft delete.
- **Comment**: pertenece a una `Task` y a un `User` (autor), sin soft delete — borrado físico.

## Reglas de negocio destacadas

- Un `Project` no puede repetir nombre para el mismo `owner` (validado en Service, no en BD — soft delete lo impide).
- Soft delete de `Project` cascadea a sus `Task` vía Observer (la FK de BD no cubre este caso porque no hay `DELETE` físico).
- El `owner` de un proyecto es miembro implícito; no puede agregarse a sí mismo como miembro explícito.
- Solo el `owner` puede eliminar tareas; el asignado puede editarlas.
- Un comentario solo puede eliminarlo su autor o el dueño del proyecto.

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

El seeder crea 3 usuarios de prueba:
- `test@example.com` / `password`
- `luis@test.cl` / `password` (owner de "Task Manager - Pruebas")
- `maria@test.cl` / `password` (miembro del proyecto anterior)

## Tests

```bash
php artisan test
```

69 tests / 137 assertions cubriendo CRUD, autorización por rol, cascadas de soft delete, y reglas de unicidad.

## Decisiones de diseño documentadas

Ver historial de commits para el razonamiento detrás de cada decisión arquitectónica (soft delete selectivo, cuándo usar Observer vs. constraint de BD, por qué `Comment` no tiene soft delete, etc.) — cada una fue evaluada explícitamente antes de implementarse.
