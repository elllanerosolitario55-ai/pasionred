import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Search, Video, Star, Users, TrendingUp, Sparkles, Clock, Heart } from 'lucide-react';
import { CATEGORIES } from '@/lib/constants';

export default function Home() {
  return (
    <div className="min-h-screen">
      {/* Hero Section */}
      <section className="relative bg-gradient-to-br from-pink-600 via-pink-500 to-blue-300 text-white py-20 px-4">
        <div className="container mx-auto max-w-6xl">
          <div className="text-center space-y-6">
            <h1 className="text-5xl md:text-6xl font-bold tracking-tight">
              Conecta con Modelos
              <span className="block text-blue-100 mt-2">En Tiempo Real</span>
            </h1>
            <p className="text-xl text-pink-50 max-w-2xl mx-auto">
              Videochat, streaming y contenido exclusivo con los mejores modelos
            </p>

            {/* Búsqueda */}
            <div className="flex gap-2 max-w-2xl mx-auto mt-8">
              <div className="relative flex-1">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" />
                <Input
                  type="search"
                  placeholder="Buscar modelos..."
                  className="pl-10 h-12 bg-white text-slate-900"
                />
              </div>
              <Button size="lg" className="h-12 px-8 bg-white text-pink-600 hover:bg-pink-50">
                Buscar
              </Button>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-3 gap-4 max-w-2xl mx-auto mt-12 pt-8 border-t border-pink-400/30">
              <div className="text-center">
                <div className="text-3xl font-bold text-blue-100">1,500+</div>
                <div className="text-sm text-pink-50">Modelos</div>
              </div>
              <div className="text-center">
                <div className="text-3xl font-bold text-blue-100">10,000+</div>
                <div className="text-sm text-pink-50">Sesiones</div>
              </div>
              <div className="text-center">
                <div className="text-3xl font-bold text-blue-100">24/7</div>
                <div className="text-sm text-pink-50">En Línea</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Categorías */}
      <section className="py-16 px-4 bg-gradient-to-b from-pink-50 to-white">
        <div className="container mx-auto max-w-6xl">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-pink-900 mb-4">Categorías</h2>
            <p className="text-slate-600">Encuentra el modelo perfecto para ti</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {CATEGORIES.map((category) => (
              <Link key={category.id} href={`/categoria/${category.slug}`}>
                <Card className="hover:shadow-xl transition-all hover:scale-105 cursor-pointer h-full border-2 border-pink-200 hover:border-pink-400">
                  <CardContent className="p-8 text-center">
                    <div className="text-6xl mb-4">{category.icon}</div>
                    <h3 className="text-2xl font-bold text-pink-900 mb-2">{category.name}</h3>
                    <p className="text-slate-600">{category.description}</p>
                  </CardContent>
                </Card>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* Modelos En Línea */}
      <section className="py-16 px-4 bg-white">
        <div className="container mx-auto max-w-6xl">
          <div className="flex items-center justify-between mb-12">
            <div>
              <h2 className="text-3xl font-bold text-pink-900 mb-2 flex items-center gap-2">
                <Video className="h-8 w-8 text-pink-600" />
                En Línea Ahora
              </h2>
              <p className="text-slate-600">Modelos disponibles para videochat y streaming</p>
            </div>
            <Link href="/modelos?filter=online">
              <Button variant="outline" className="border-pink-300 text-pink-600 hover:bg-pink-50">Ver Todos</Button>
            </Link>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            {[1, 2, 3, 4].map((i) => (
              <Card key={i} className="overflow-hidden hover:shadow-xl transition-shadow border-2 border-blue-200">
                <div className="h-64 bg-gradient-to-br from-pink-300 to-blue-200 relative">
                  <Badge className="absolute top-4 right-4 bg-green-500 animate-pulse">
                    <div className="w-2 h-2 bg-white rounded-full mr-1" />
                    EN VIVO
                  </Badge>
                  <Badge className="absolute top-4 left-4 bg-pink-600">
                    Oro
                  </Badge>
                </div>
                <CardHeader className="pb-3">
                  <CardTitle className="text-lg">Modelo {i}</CardTitle>
                  <CardDescription className="flex items-center gap-1">
                    <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                    <span className="font-medium text-slate-900">4.9</span>
                    <span className="text-slate-500">(328)</span>
                  </CardDescription>
                </CardHeader>
                <CardContent className="pb-4">
                  <Button className="w-full bg-pink-600 hover:bg-pink-700">
                    <Video className="h-4 w-4 mr-2" />
                    Conectar
                  </Button>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Más Activos */}
      <section className="py-16 px-4 bg-gradient-to-b from-blue-50 to-pink-50">
        <div className="container mx-auto max-w-6xl">
          <div className="flex items-center justify-between mb-12">
            <div>
              <h2 className="text-3xl font-bold text-pink-900 mb-2 flex items-center gap-2">
                <TrendingUp className="h-8 w-8 text-pink-600" />
                Más Activos
              </h2>
              <p className="text-slate-600">Los modelos con más actividad</p>
            </div>
            <Link href="/modelos?sort=active">
              <Button variant="outline" className="border-pink-300 text-pink-600 hover:bg-pink-50">Ver Todos</Button>
            </Link>
          </div>

          <div className="grid md:grid-cols-3 lg:grid-cols-5 gap-4">
            {[1, 2, 3, 4, 5].map((i) => (
              <Card key={i} className="overflow-hidden hover:shadow-lg transition-shadow">
                <div className="h-48 bg-gradient-to-br from-pink-200 to-blue-100" />
                <CardContent className="p-4">
                  <p className="font-semibold text-pink-900">Modelo {i}</p>
                  <p className="text-xs text-slate-500">2.5K seguidores</p>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Últimos Posts */}
      <section className="py-16 px-4 bg-white">
        <div className="container mx-auto max-w-6xl">
          <div className="flex items-center justify-between mb-12">
            <div>
              <h2 className="text-3xl font-bold text-pink-900 mb-2 flex items-center gap-2">
                <Sparkles className="h-8 w-8 text-pink-600" />
                Últimos Posts
              </h2>
              <p className="text-slate-600">Contenido reciente en el muro</p>
            </div>
            <Link href="/feed">
              <Button variant="outline" className="border-pink-300 text-pink-600 hover:bg-pink-50">Ver Feed</Button>
            </Link>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {[1, 2, 3].map((i) => (
              <Card key={i} className="overflow-hidden hover:shadow-xl transition-shadow">
                <div className="h-56 bg-gradient-to-br from-pink-300 via-pink-200 to-blue-200" />
                <CardContent className="p-4">
                  <div className="flex items-center gap-2 mb-2">
                    <div className="w-8 h-8 rounded-full bg-pink-400" />
                    <span className="font-semibold text-pink-900">Modelo {i}</span>
                  </div>
                  <p className="text-sm text-slate-600 mb-3">Nuevo contenido exclusivo disponible 🔥</p>
                  <div className="flex gap-2">
                    <Button size="sm" variant="ghost" className="text-pink-600">
                      <Heart className="h-4 w-4 mr-1" />
                      245
                    </Button>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Más Valorados */}
      <section className="py-16 px-4 bg-gradient-to-b from-pink-50 to-blue-50">
        <div className="container mx-auto max-w-6xl">
          <div className="flex items-center justify-between mb-12">
            <div>
              <h2 className="text-3xl font-bold text-pink-900 mb-2 flex items-center gap-2">
                <Star className="h-8 w-8 text-yellow-500 fill-yellow-500" />
                Más Valorados
              </h2>
              <p className="text-slate-600">Modelos con mejor calificación</p>
            </div>
            <Link href="/modelos?sort=rating">
              <Button variant="outline" className="border-pink-300 text-pink-600 hover:bg-pink-50">Ver Todos</Button>
            </Link>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            {[1, 2, 3, 4].map((i) => (
              <Card key={i} className="overflow-hidden hover:shadow-xl transition-shadow border-2 border-yellow-200">
                <div className="h-56 bg-gradient-to-br from-yellow-200 via-pink-200 to-blue-200 relative">
                  <Badge className="absolute top-4 left-4 bg-yellow-500 text-yellow-900">
                    <Star className="h-3 w-3 mr-1 fill-current" />
                    5.0
                  </Badge>
                </div>
                <CardContent className="p-4">
                  <p className="font-semibold text-pink-900 mb-1">Modelo Top {i}</p>
                  <p className="text-xs text-slate-500 mb-3">543 reseñas</p>
                  <Button size="sm" className="w-full bg-pink-600 hover:bg-pink-700">Ver Perfil</Button>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Últimos Inscritos */}
      <section className="py-16 px-4 bg-white">
        <div className="container mx-auto max-w-6xl">
          <div className="flex items-center justify-between mb-12">
            <div>
              <h2 className="text-3xl font-bold text-pink-900 mb-2 flex items-center gap-2">
                <Clock className="h-8 w-8 text-blue-400" />
                Nuevos Modelos
              </h2>
              <p className="text-slate-600">Últimos inscritos en la plataforma</p>
            </div>
            <Link href="/modelos?sort=newest">
              <Button variant="outline" className="border-pink-300 text-pink-600 hover:bg-pink-50">Ver Todos</Button>
            </Link>
          </div>

          <div className="grid md:grid-cols-3 lg:grid-cols-6 gap-4">
            {[1, 2, 3, 4, 5, 6].map((i) => (
              <Card key={i} className="overflow-hidden hover:shadow-lg transition-shadow">
                <div className="h-40 bg-gradient-to-br from-blue-200 to-pink-200 relative">
                  <Badge className="absolute top-2 right-2 bg-blue-400 text-xs">NUEVO</Badge>
                </div>
                <CardContent className="p-3">
                  <p className="font-semibold text-pink-900 text-sm">Modelo {i}</p>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Aleatorios */}
      <section className="py-16 px-4 bg-gradient-to-b from-blue-50 to-white">
        <div className="container mx-auto max-w-6xl">
          <div className="flex items-center justify-between mb-12">
            <div>
              <h2 className="text-3xl font-bold text-pink-900 mb-2 flex items-center gap-2">
                <Sparkles className="h-8 w-8 text-blue-400" />
                Descubre Modelos
              </h2>
              <p className="text-slate-600">Selección aleatoria para ti</p>
            </div>
            <Button variant="outline" className="border-pink-300 text-pink-600 hover:bg-pink-50">
              <Sparkles className="h-4 w-4 mr-2" />
              Aleatorio
            </Button>
          </div>

          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {[1, 2, 3].map((i) => (
              <Card key={i} className="overflow-hidden hover:shadow-xl transition-shadow">
                <div className="h-64 bg-gradient-to-br from-blue-300 to-pink-300" />
                <CardHeader>
                  <CardTitle className="flex items-center justify-between">
                    <span>Modelo Aleatorio {i}</span>
                    <Badge variant="secondary">Verificado</Badge>
                  </CardTitle>
                  <CardDescription className="flex items-center gap-1">
                    <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                    <span className="font-medium text-slate-900">4.8</span>
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  <Button className="w-full bg-pink-600 hover:bg-pink-700">Conectar</Button>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Final */}
      <section className="py-16 px-4 bg-gradient-to-r from-pink-600 to-blue-400 text-white">
        <div className="container mx-auto max-w-4xl text-center">
          <h2 className="text-4xl font-bold mb-4">¿Quieres ser Modelo?</h2>
          <p className="text-xl mb-8 text-pink-50">
            Únete a nuestra plataforma y empieza a monetizar tu contenido
          </p>
          <div className="flex gap-4 justify-center">
            <Link href="/registro-modelo">
              <Button size="lg" variant="secondary" className="h-12 px-8 bg-white text-pink-600 hover:bg-pink-50">
                Registrarse como Modelo
              </Button>
            </Link>
            <Link href="/membresias">
              <Button size="lg" variant="outline" className="h-12 px-8 border-white text-white hover:bg-white/10">
                Ver Membresías
              </Button>
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
}
