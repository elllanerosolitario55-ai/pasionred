# PASIONES Platform - Estructura del Proyecto

## 📂 Estructura de Carpetas

```
pasiones-platform/
├── src/                          # Aplicación Next.js (OPCIÓN 2)
│   ├── app/                      # App router de Next.js
│   ├── components/               # Componentes React
│   ├── lib/                      # Utilidades y helpers
│   └── types/                    # TypeScript types
│
├── wordpress-plugin/             # Plugin WordPress (OPCIÓN 1)
│   ├── pasiones-platform.php     # Archivo principal del plugin
│   ├── includes/                 # Clases PHP del plugin
│   ├── admin/                    # Panel de administración
│   ├── public/                   # Assets públicos
│   ├── templates/                # Plantillas PHP
│   └── elementor/                # Widgets de Elementor
│
└── integration/                  # Sistema Híbrido (OPCIÓN 3)
    ├── api/                      # API de integración
    ├── sync/                     # Sincronización de datos
    └── auth/                     # SSO y autenticación
```

## 🎨 Tecnologías

### Opción 2 (Next.js):
- Next.js 14+ (App Router)
- TypeScript
- Tailwind CSS + shadcn/ui
- WebRTC (simple-peer)
- Stripe + PayPal SDK
- Prisma ORM
- NextAuth.js

### Opción 1 (WordPress):
- PHP 8.0+
- WordPress 6.0+
- Elementor
- Custom Post Types
- WP REST API

### Opción 3 (Integración):
- JWT para autenticación
- REST API
- Webhooks
