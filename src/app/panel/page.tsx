import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import {
  Users,
  DollarSign,
  Eye,
  Heart,
  Video,
  MessageSquare,
  TrendingUp,
  Award,
  Settings,
  FileText
} from 'lucide-react';
import Link from 'next/link';

export default function PanelPage() {
  // En producción estos datos vendrían del servidor
  const stats = {
    totalSessions: 145,
    totalEarnings: 2450.50,
    totalViews: 12350,
    totalLikes: 890,
    activeMembership: 'GOLD',
    postsThisMonth: 15,
    postsLimit: -1, // ilimitado
    isOnline: false,
  };

  return (
    <div className="min-h-screen bg-slate-50">
      {/* Header */}
      <div className="bg-gradient-to-br from-pink-900 via-pink-800 to-blue-900 text-white py-12 px-4">
        <div className="container mx-auto max-w-6xl">
          <div className="flex items-center justify-between">
            <div>
              <h1 className="text-4xl font-bold mb-2">Panel de Control</h1>
              <p className="text-pink-100">Gestiona tu perfil y contenido</p>
            </div>
            <div className="flex items-center gap-3">
              <div className="text-right">
                <div className="text-sm text-pink-200">Estado</div>
                <div className="flex items-center gap-2 mt-1">
                  <div className={`w-3 h-3 rounded-full ${stats.isOnline ? 'bg-green-400 animate-pulse' : 'bg-gray-400'}`} />
                  <span className="font-medium">{stats.isOnline ? 'En línea' : 'Desconectado'}</span>
                </div>
              </div>
              <Button className="bg-white text-pink-600 hover:bg-pink-50">
                <Settings className="h-4 w-4 mr-2" />
                Configuración
              </Button>
            </div>
          </div>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="container mx-auto max-w-6xl px-4 -mt-8">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <Card className="border-2 border-pink-200">
            <CardHeader className="pb-3">
              <div className="flex items-center justify-between">
                <CardTitle className="text-sm font-medium text-slate-600">
                  Ingresos Totales
                </CardTitle>
                <DollarSign className="h-5 w-5 text-green-600" />
              </div>
            </CardHeader>
            <CardContent>
              <div className="text-3xl font-bold text-slate-900">
                {stats.totalEarnings.toLocaleString('es-ES', { style: 'currency', currency: 'EUR' })}
              </div>
              <p className="text-sm text-green-600 mt-1 flex items-center gap-1">
                <TrendingUp className="h-4 w-4" />
                +12% este mes
              </p>
            </CardContent>
          </Card>

          <Card className="border-2 border-blue-200">
            <CardHeader className="pb-3">
              <div className="flex items-center justify-between">
                <CardTitle className="text-sm font-medium text-slate-600">
                  Sesiones
                </CardTitle>
                <Video className="h-5 w-5 text-blue-600" />
              </div>
            </CardHeader>
            <CardContent>
              <div className="text-3xl font-bold text-slate-900">
                {stats.totalSessions}
              </div>
              <p className="text-sm text-slate-500 mt-1">
                Total de videochats
              </p>
            </CardContent>
          </Card>

          <Card className="border-2 border-purple-200">
            <CardHeader className="pb-3">
              <div className="flex items-center justify-between">
                <CardTitle className="text-sm font-medium text-slate-600">
                  Visualizaciones
                </CardTitle>
                <Eye className="h-5 w-5 text-purple-600" />
              </div>
            </CardHeader>
            <CardContent>
              <div className="text-3xl font-bold text-slate-900">
                {stats.totalViews.toLocaleString()}
              </div>
              <p className="text-sm text-slate-500 mt-1">
                En tu perfil y posts
              </p>
            </CardContent>
          </Card>

          <Card className="border-2 border-pink-200">
            <CardHeader className="pb-3">
              <div className="flex items-center justify-between">
                <CardTitle className="text-sm font-medium text-slate-600">
                  Me Gusta
                </CardTitle>
                <Heart className="h-5 w-5 text-pink-600" />
              </div>
            </CardHeader>
            <CardContent>
              <div className="text-3xl font-bold text-slate-900">
                {stats.totalLikes}
              </div>
              <p className="text-sm text-slate-500 mt-1">
                En tus publicaciones
              </p>
            </CardContent>
          </Card>
        </div>

        {/* Main Content */}
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Left Column */}
          <div className="lg:col-span-2 space-y-6">
            {/* Membership Status */}
            <Card>
              <CardHeader>
                <div className="flex items-center justify-between">
                  <div>
                    <CardTitle>Membresía Actual</CardTitle>
                    <CardDescription>Gestiona tu plan de suscripción</CardDescription>
                  </div>
                  <Award className={`h-8 w-8 ${
                    stats.activeMembership === 'GOLD' ? 'text-yellow-500' :
                    stats.activeMembership === 'SILVER' ? 'text-gray-400' :
                    stats.activeMembership === 'BRONZE' ? 'text-orange-500' :
                    'text-slate-400'
                  }`} />
                </div>
              </CardHeader>
              <CardContent>
                <div className="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg border-2 border-yellow-300">
                  <div>
                    <div className="font-bold text-2xl text-yellow-900">
                      {stats.activeMembership}
                    </div>
                    <div className="text-sm text-yellow-700 mt-1">
                      Publicaciones: {stats.postsLimit === -1 ? 'Ilimitadas' : `${stats.postsThisMonth}/${stats.postsLimit}`}
                    </div>
                  </div>
                  <Link href="/membresias">
                    <Button variant="outline" className="border-yellow-600 text-yellow-700 hover:bg-yellow-50">
                      Cambiar Plan
                    </Button>
                  </Link>
                </div>
              </CardContent>
            </Card>

            {/* Quick Actions */}
            <Card>
              <CardHeader>
                <CardTitle>Acciones Rápidas</CardTitle>
                <CardDescription>Gestiona tu contenido y perfil</CardDescription>
              </CardHeader>
              <CardContent className="grid grid-cols-2 gap-4">
                <Button className="h-24 flex-col gap-2 bg-pink-600 hover:bg-pink-700">
                  <FileText className="h-6 w-6" />
                  Nueva Publicación
                </Button>
                <Button className="h-24 flex-col gap-2 bg-blue-600 hover:bg-blue-700">
                  <Video className="h-6 w-6" />
                  Iniciar Stream
                </Button>
                <Button variant="outline" className="h-24 flex-col gap-2">
                  <Users className="h-6 w-6" />
                  Ver Seguidores
                </Button>
                <Button variant="outline" className="h-24 flex-col gap-2">
                  <MessageSquare className="h-6 w-6" />
                  Mensajes
                </Button>
              </CardContent>
            </Card>

            {/* Recent Posts */}
            <Card>
              <CardHeader>
                <div className="flex items-center justify-between">
                  <CardTitle>Publicaciones Recientes</CardTitle>
                  <Link href="/panel/posts">
                    <Button variant="ghost" size="sm">
                      Ver todas
                    </Button>
                  </Link>
                </div>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  {[1, 2, 3].map((i) => (
                    <div key={i} className="flex items-start gap-4 p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                      <div className="w-16 h-16 bg-gradient-to-br from-pink-400 to-blue-400 rounded-lg flex-shrink-0" />
                      <div className="flex-1 min-w-0">
                        <p className="text-sm text-slate-900 line-clamp-2 mb-2">
                          Nueva sesión disponible sobre manejo del estrés y ansiedad. ¡Reserva tu cita! 🌟
                        </p>
                        <div className="flex items-center gap-4 text-xs text-slate-500">
                          <span className="flex items-center gap-1">
                            <Eye className="h-3 w-3" />
                            {Math.floor(Math.random() * 500) + 100}
                          </span>
                          <span className="flex items-center gap-1">
                            <Heart className="h-3 w-3" />
                            {Math.floor(Math.random() * 100) + 20}
                          </span>
                          <span>Hace {i} días</span>
                        </div>
                      </div>
                      <Button variant="ghost" size="sm">
                        Editar
                      </Button>
                    </div>
                  ))}
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Right Column */}
          <div className="space-y-6">
            {/* Earnings */}
            <Card>
              <CardHeader>
                <CardTitle>Ganancias</CardTitle>
                <CardDescription>Balance disponible</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="text-center py-6">
                  <div className="text-4xl font-bold text-green-600 mb-2">
                    {stats.totalEarnings.toLocaleString('es-ES', { style: 'currency', currency: 'EUR' })}
                  </div>
                  <p className="text-sm text-slate-600 mb-6">
                    Disponible para retirar
                  </p>
                  <Button className="w-full bg-green-600 hover:bg-green-700">
                    Solicitar Retiro
                  </Button>
                  <p className="text-xs text-slate-500 mt-3">
                    Mínimo: 50€ | Comisión: 20%
                  </p>
                </div>
              </CardContent>
            </Card>

            {/* Schedule */}
            <Card>
              <CardHeader>
                <CardTitle>Horario</CardTitle>
                <CardDescription>Tu disponibilidad</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="space-y-3">
                  {['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'].map((day) => (
                    <div key={day} className="flex items-center justify-between text-sm">
                      <span className="font-medium">{day}</span>
                      <span className="text-slate-600">09:00 - 18:00</span>
                    </div>
                  ))}
                </div>
                <Button variant="outline" className="w-full mt-4">
                  Editar Horario
                </Button>
              </CardContent>
            </Card>

            {/* Notifications */}
            <Card>
              <CardHeader>
                <CardTitle>Notificaciones</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="space-y-3">
                  {[
                    { type: 'like', text: 'Nueva reseña de 5 estrellas', time: '2h' },
                    { type: 'message', text: 'Nuevo mensaje de cliente', time: '5h' },
                    { type: 'payment', text: 'Pago recibido: 45€', time: '1d' },
                  ].map((notif, i) => (
                    <div key={i} className="flex gap-3 text-sm">
                      <div className="w-2 h-2 rounded-full bg-pink-600 mt-2 flex-shrink-0" />
                      <div className="flex-1">
                        <p className="text-slate-900">{notif.text}</p>
                        <p className="text-xs text-slate-500">Hace {notif.time}</p>
                      </div>
                    </div>
                  ))}
                </div>
                <Button variant="ghost" className="w-full mt-4 text-pink-600">
                  Ver todas
                </Button>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>

      <div className="h-20" /> {/* Spacer */}
    </div>
  );
}
