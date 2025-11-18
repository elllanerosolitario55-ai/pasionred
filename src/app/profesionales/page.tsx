import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Search, Filter, Star, Video, MapPin, Award } from 'lucide-react';
import Link from 'next/link';

export default function ProfesionalesPage() {
  return (
    <div className="min-h-screen bg-slate-50">
      {/* Header */}
      <section className="bg-white border-b py-8 px-4">
        <div className="container mx-auto max-w-6xl">
          <h1 className="text-4xl font-bold text-slate-900 mb-4">Encuentra tu Profesional</h1>
          <p className="text-slate-600 mb-6">Más de 1,500 profesionales verificados listos para ayudarte</p>

          {/* Search and Filters */}
          <div className="flex flex-col md:flex-row gap-4">
            <div className="flex-1 relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" />
              <Input
                type="search"
                placeholder="Buscar por nombre, especialidad..."
                className="pl-10"
              />
            </div>
            <Button variant="outline" className="gap-2">
              <Filter className="h-4 w-4" />
              Filtros
            </Button>
          </div>
        </div>
      </section>

      {/* Filters Bar */}
      <section className="bg-white border-b py-4 px-4">
        <div className="container mx-auto max-w-6xl">
          <div className="flex gap-2 overflow-x-auto pb-2">
            <Badge variant="secondary" className="cursor-pointer">Todos</Badge>
            <Badge variant="outline" className="cursor-pointer">En línea</Badge>
            <Badge variant="outline" className="cursor-pointer">Streaming ahora</Badge>
            <Badge variant="outline" className="cursor-pointer">Más valorados</Badge>
            <Badge variant="outline" className="cursor-pointer">Nuevos</Badge>
            <Badge variant="outline" className="cursor-pointer">Aleatorios</Badge>
          </div>
        </div>
      </section>

      {/* Professionals Grid */}
      <section className="py-8 px-4">
        <div className="container mx-auto max-w-6xl">
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {Array.from({ length: 9 }).map((_, i) => (
              <Card key={i} className="overflow-hidden hover:shadow-xl transition-shadow">
                {/* Cover Image */}
                <div className="h-40 bg-gradient-to-br from-emerald-400 to-cyan-500 relative">
                  <Badge className="absolute top-3 right-3 bg-green-600 gap-1">
                    <div className="w-2 h-2 bg-white rounded-full animate-pulse" />
                    En línea
                  </Badge>
                  <Badge className="absolute top-3 left-3 bg-yellow-600">
                    <Award className="h-3 w-3 mr-1" />
                    Oro
                  </Badge>
                </div>

                {/* Avatar */}
                <div className="relative px-6">
                  <div className="w-20 h-20 rounded-full bg-white border-4 border-white -mt-10 relative">
                    <div className="w-full h-full rounded-full bg-gradient-to-br from-purple-400 to-pink-400" />
                  </div>
                </div>

                <CardHeader className="pt-2">
                  <div className="flex items-start justify-between">
                    <div className="flex-1">
                      <CardTitle className="text-lg">Dra. María González</CardTitle>
                      <CardDescription className="flex items-center gap-1 mt-1">
                        <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                        <span className="font-medium text-slate-900">4.9</span>
                        <span className="text-slate-500">(243)</span>
                      </CardDescription>
                    </div>
                  </div>
                </CardHeader>

                <CardContent className="space-y-3">
                  <div className="flex items-center gap-2 text-sm text-slate-600">
                    <Badge variant="secondary" className="text-xs">Psicología</Badge>
                    <div className="flex items-center gap-1">
                      <MapPin className="h-3 w-3" />
                      <span className="text-xs">Madrid, España</span>
                    </div>
                  </div>

                  <p className="text-sm text-slate-600 line-clamp-2">
                    Psicóloga clínica especializada en terapia cognitivo-conductual con más de 10 años de experiencia.
                  </p>

                  <div className="flex items-center justify-between pt-2 border-t">
                    <div className="text-sm">
                      <span className="font-semibold text-slate-900">2.50 €</span>
                      <span className="text-slate-500">/min</span>
                    </div>
                    <div className="flex gap-2">
                      <Button size="sm" variant="outline">
                        Ver Perfil
                      </Button>
                      <Button size="sm" className="bg-emerald-600 hover:bg-emerald-700">
                        <Video className="h-4 w-4 mr-1" />
                        Conectar
                      </Button>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>

          {/* Pagination */}
          <div className="flex items-center justify-center gap-2 mt-8">
            <Button variant="outline">Anterior</Button>
            <Button variant="outline">1</Button>
            <Button className="bg-emerald-600 hover:bg-emerald-700">2</Button>
            <Button variant="outline">3</Button>
            <Button variant="outline">...</Button>
            <Button variant="outline">10</Button>
            <Button variant="outline">Siguiente</Button>
          </div>
        </div>
      </section>
    </div>
  );
}
