# 🏆 Sistema de Priorización por Membresías

## 📋 Descripción General

PASIONES Platform implementa un **sistema de priorización automática** basado en el tipo de membresía del profesional. Esto garantiza que los profesionales con membresías premium tengan mayor visibilidad en la plataforma.

---

## 🎯 Orden de Prioridad

Los profesionales se ordenan automáticamente en el siguiente orden:

### 1. 🥇 **ORO (GOLD)** - Prioridad Máxima
- Aparecen **primero** en todos los listados
- Mayor visibilidad en home, categorías y provincias
- Destacados con badge dorado
- Publicaciones ilimitadas

### 2. 🥈 **PLATA (SILVER)** - Prioridad Alta
- Aparecen **segundo** en todos los listados
- Alta visibilidad en la plataforma
- Badge plateado
- 200 publicaciones/mes

### 3. 🥉 **BRONCE (BRONZE)** - Prioridad Media
- Aparecen **tercero** en todos los listados
- Visibilidad media
- Badge bronce
- 50 publicaciones/mes

### 4. ⚪ **GRATIS (FREE)** - Prioridad Básica
- Aparecen **último** en todos los listados
- Visibilidad básica
- Sin badge premium
- 5 publicaciones/mes

---

## 📍 Dónde se Aplica la Priorización

### ✅ **Página de Inicio (Home)**
```typescript
// Los profesionales ORO aparecen primero
Orden: GOLD → SILVER → BRONZE → FREE
```

### ✅ **Páginas de Provincias**
```typescript
// Ejemplo: Madrid, España
Profesionales en Madrid ordenados por:
1. Membresía (ORO primero)
2. Rating (dentro de cada membresía)
```

### ✅ **Páginas de Categorías**
```typescript
// Ejemplo: Psicólogos
Psicólogos ordenados por:
1. Membresía (ORO primero)
2. Rating (dentro de cada membresía)
```

### ✅ **Búsquedas**
```typescript
// Cualquier búsqueda respeta la prioridad
Resultados ordenados por:
1. Membresía
2. Rating
3. Relevancia
```

### ✅ **Widgets de Elementor**
Todos los widgets de profesionales:
- `[pasiones_professionals]`
- `[pasiones_featured]`
- Professionals Grid
- Featured Professionals
- Categories Grid

---

## 🔧 Implementación Técnica

### Backend (API Routes)

```typescript
// src/app/api/professionals/route.ts
const membershipPriority: Record<string, number> = {
  'GOLD': 1,    // Más alta prioridad
  'SILVER': 2,
  'BRONZE': 3,
  'FREE': 4,    // Más baja prioridad
};

// Ordenamiento automático
professionals.sort((a, b) => {
  const priorityA = membershipPriority[a.membershipType];
  const priorityB = membershipPriority[b.membershipType];

  if (priorityA !== priorityB) {
    return priorityA - priorityB; // Orden ascendente
  }

  // Mismo nivel: ordenar por rating
  return (b.rating || 0) - (a.rating || 0);
});
```

### Frontend (Next.js)

```typescript
// src/lib/utils/prioritySort.ts
export function sortByMembershipPriority(professionals) {
  return professionals.sort((a, b) => {
    const priorityA = MEMBERSHIP_PRIORITY[a.membershipType];
    const priorityB = MEMBERSHIP_PRIORITY[b.membershipType];

    if (priorityA !== priorityB) {
      return priorityA - priorityB;
    }

    return (b.rating || 0) - (a.rating || 0);
  });
}
```

### WordPress (Widgets Elementor)

```php
// wordpress-plugin/elementor/widgets/professionals-grid.php
$membership_priority = array(
    'gold' => 1,
    'silver' => 2,
    'bronze' => 3,
    'free' => 4,
);

usort($posts, function($a, $b) use ($membership_priority) {
    $membership_a = get_user_meta(...);
    $membership_b = get_user_meta(...);

    $priority_a = $membership_priority[$membership_a] ?? 999;
    $priority_b = $membership_priority[$membership_b] ?? 999;

    if ($priority_a != $priority_b) {
        return $priority_a - $priority_b;
    }

    // Mismo nivel: ordenar por rating
    return $rating_b - $rating_a;
});
```

---

## 📊 Ejemplo Práctico

### Listado de Psicólogos en Madrid

**Antes de la Priorización:**
1. Ana García (FREE, 4.8★)
2. Carlos Ruiz (GOLD, 4.9★)
3. María López (BRONZE, 5.0★)
4. Juan Pérez (SILVER, 4.7★)

**Después de la Priorización:**
1. 🥇 **Carlos Ruiz (GOLD, 4.9★)** ← Aparece primero
2. 🥈 **Juan Pérez (SILVER, 4.7★)**
3. 🥉 **María López (BRONZE, 5.0★)**
4. ⚪ **Ana García (FREE, 4.8★)** ← Aparece último

---

## 🎨 Indicadores Visuales

### Badges de Membresía

```css
/* ORO */
.badge-gold {
  background: linear-gradient(135deg, #FFD700, #FFA500);
  color: #333;
}

/* PLATA */
.badge-silver {
  background: linear-gradient(135deg, #C0C0C0, #808080);
  color: #333;
}

/* BRONCE */
.badge-bronze {
  background: linear-gradient(135deg, #CD7F32, #8B4513);
  color: white;
}

/* GRATIS */
.badge-free {
  background: #94A3B8;
  color: white;
}
```

### Iconos de Prioridad

- 🥇 ORO: Corona dorada + estrella
- 🥈 PLATA: Medalla plateada
- 🥉 BRONCE: Medalla bronce
- ⚪ GRATIS: Sin icono especial

---

## 📈 Beneficios del Sistema

### Para Profesionales con Membresías Premium:

✅ **Mayor Visibilidad**
- Aparecen primero en búsquedas
- Más clics y visualizaciones
- Mejor posicionamiento

✅ **Más Oportunidades**
- Más solicitudes de videochat
- Más seguidores
- Más ingresos potenciales

✅ **Diferenciación**
- Badges visibles
- Indicadores de calidad
- Confianza del usuario

### Para la Plataforma:

✅ **Incentivo para Upgrades**
- Los usuarios FREE ven el beneficio de mejorar
- Monetización natural
- Conversión de FREE a PREMIUM

✅ **Calidad del Contenido**
- Profesionales premium suelen ser más activos
- Mejor experiencia de usuario
- Mayor retención

---

## 🔄 Ordenamiento Secundario

Dentro de cada nivel de membresía, se aplica un **ordenamiento secundario**:

### 1. Rating (Valoración)
```
GOLD 5.0★ > GOLD 4.8★ > GOLD 4.5★
```

### 2. Número de Reviews
```
SILVER 4.9★ (100 reviews) > SILVER 4.9★ (50 reviews)
```

### 3. Verificación
```
BRONZE Verificado > BRONZE Sin verificar
```

### 4. Estado Online
```
FREE Online > FREE Offline
```

---

## 🎯 Casos de Uso

### Caso 1: Usuario busca psicólogos online

**Query:**
```
GET /api/professionals?categoryId=psicologo&isOnline=true
```

**Resultado:**
1. Psicólogos GOLD online (ordenados por rating)
2. Psicólogos SILVER online (ordenados por rating)
3. Psicólogos BRONZE online (ordenados por rating)
4. Psicólogos FREE online (ordenados por rating)

### Caso 2: Usuario filtra por provincia

**Query:**
```
GET /api/professionals?provinceId=madrid&sortBy=membership
```

**Resultado:**
1. Todos los profesionales GOLD en Madrid
2. Todos los profesionales SILVER en Madrid
3. Todos los profesionales BRONZE en Madrid
4. Todos los profesionales FREE en Madrid

### Caso 3: Widget de Elementor en home

**Shortcode:**
```php
[pasiones_professionals_grid columns="3" posts_per_page="12"]
```

**Resultado:**
- Automáticamente ordenado por membresía
- Los 12 profesionales con mejor membresía y rating

---

## ⚙️ Configuración

### Cambiar el Ordenamiento por Defecto

Si quieres cambiar el ordenamiento por defecto:

```typescript
// src/app/api/professionals/route.ts
const sortBy = searchParams.get('sortBy') || 'membership';
// Cambiar a: 'rating', 'createdAt', etc.
```

### Desactivar la Priorización

Para desactivar temporalmente:

```typescript
// En lugar de 'membership', usar:
const sortBy = searchParams.get('sortBy') || 'rating';
```

### Personalizar Prioridades

```typescript
const membershipPriority: Record<string, number> = {
  'GOLD': 1,
  'SILVER': 2,
  'BRONZE': 3,
  'FREE': 4,
  // Agregar nuevos niveles si es necesario
};
```

---

## 📝 Notas Importantes

⚠️ **Transparencia con Usuarios**
- Los usuarios deben saber que existe priorización
- Mostrar indicadores claros (badges)
- No ocultar que es un sistema premium

✅ **Cumplimiento Legal**
- Algunos países requieren indicar contenido promocionado
- Agregar textos como "Resultados ordenados por membresía"

🔍 **SEO Considerations**
- Los motores de búsqueda ven todo el contenido
- La priorización solo afecta la UI del usuario
- Todos los profesionales son indexables

---

## 🚀 Próximas Mejoras

### Features Planeadas:

1. **Boost Temporal**
   - Profesionales pueden pagar por aparecer destacados 24h
   - Prioridad sobre su nivel de membresía

2. **Aleatorización Parcial**
   - Dentro de cada nivel, rotar aleatoriamente
   - Dar oportunidades a todos

3. **Geolocalización**
   - Priorizar profesionales cercanos al usuario
   - Combinar con membresía

4. **Performance Metrics**
   - Priorizar profesionales con mejor ratio de conversión
   - Premiar la actividad

---

## 📊 Métricas y Analytics

### KPIs a Monitorear:

- **Conversión FREE → PREMIUM**: % de upgrades
- **CTR por Membresía**: Clicks por nivel
- **Ingresos por Posición**: ROI de cada nivel
- **Tiempo en Página**: Engagement por membresía

### Dashboard Recomendado:

```sql
SELECT
  membershipType,
  COUNT(*) as total,
  AVG(viewsCount) as avg_views,
  AVG(sessionsCount) as avg_sessions,
  AVG(earnings) as avg_earnings
FROM professionals
GROUP BY membershipType
ORDER BY
  FIELD(membershipType, 'GOLD', 'SILVER', 'BRONZE', 'FREE');
```

---

## ✅ Checklist de Implementación

- [x] API Routes con ordenamiento por membresía
- [x] Función helper `sortByMembershipPriority()`
- [x] Hook `useProfessionals()` con priorización
- [x] Widgets WordPress actualizados
- [x] Widgets Elementor actualizados
- [x] Documentación completa
- [ ] Tests unitarios
- [ ] Tests E2E
- [ ] Analytics implementado
- [ ] A/B testing configurado

---

**Versión**: 1.0
**Última actualización**: Noviembre 2025
**Autor**: PASIONES Platform Team
