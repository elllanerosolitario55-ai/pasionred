import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Search, Video, Star, Users, TrendingUp, Sparkles, Clock, Heart, MapPin } from 'lucide-react';
import { CATEGORIES } from '@/lib/constants';
import { getCountryByCode, formatPrice } from '@/lib/countries/countries';
import { getProfessionals } from '@/lib/mockData';

interface CountryHomeProps {
  params: { country: string };
}

async function getProfessionalsByCountry(countryCode: string) {
  // Usar getProfessionals que maneja automáticamente mock vs real data
  const professionals = await getProfessionals({
    countryId: countryCode.toUpperCase(),
  });

  // Ya vienen ordenados por prioridad de membresía
  return professionals.slice(0, 20);
}

// Función auxiliar para ordenar (por si se necesita)
function sortByPriority(professionals: any[]) {
  const priorityOrder: Record<string, number> = {
    GOLD: 1,
    SILVER: 2,
    BRONZE: 3,
    FREE: 4,
  };

  return professionals.sort((a, b) => {
    const priorityA = priorityOrder[a.membershipType] || 999;
    const priorityB = priorityOrder[b.membershipType] || 999;
    if (priorityA !== priorityB) return priorityA - priorityB;
    return (b.rating || 0) - (a.rating || 0);
  });
}

export default async function CountryHomePage({ params }: CountryHomeProps) {
  const countryCode = params.country.toUpperCase();
  const country = getCountryByCode(countryCode);

  if (!country) {
    return <div>País no encontrado</div>;
  }

  const professionals = await getProfessionalsByCountry(countryCode);
  const onlineProfessionals = professionals.filter(p => p.isOnline).slice(0, 4);

  return (
    <div className="min-h-screen">
      {/* Hero Section específico del país */}
      <section className="relative bg-gradient-to-br from-pink-600 via-pink-500 to-blue-300 text-white py-20 px-4">
        <div className="container mx-auto max-w-6xl">
          <div className="text-center space-y-6">
            <div className="flex items-center justify-center gap-3 mb-4">
              <span className="text-6xl">{country.flag}</span>
              <h1 className="text-5xl md:text-6xl font-bold tracking-tight">
                Pasiones {country.name}
              </h1>
            </div>
            <p className="text-xl text-pink-50 max-w-2xl mx-auto">
              Conecta con profesionales verificados en {country.name}
            </p>

            {/* Búsqueda */}
            <div className="flex gap-2 max-w-2xl mx-auto mt-8">
              <div className="relative flex-1">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" />
                <Input
                  type="search"
                  placeholder={`Buscar profesionales en ${country.name}...`}
                  className="pl-10 h-12 bg-white text-slate-900"
                />
              </div>
              <Button size="lg" className="h-12 px-8 bg-white text-pink-600 hover:bg-pink-50">
                Buscar
              </Button>
            </div>

            {/* Stats específicas del país */}
            <div className="grid grid-cols-3 gap-4 max-w-2xl mx-auto mt-12 pt-8 border-t border-pink-400/30">
              <div className="text-center">
                <div className="text-3xl font-bold text-blue-100">{professionals.length}+</div>
                <div className="text-sm text-pink-50">Profesionales</div>
              </div>
              <div className="text-center">
                <div className="text-3xl font-bold text-blue-100">{onlineProfessionals.length}</div>
                <div className="text-sm text-pink-50">En Línea Ahora</div>
              </div>
              <div className="text-center">
                <div className="text-3xl font-bold text-blue-100">24/7</div>
                <div className="text-sm text-pink-50">Disponible</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Categorías */}
      <section className="py-16 px-4 bg-gradient-to-b from-pink-50 to-white">
        <div className="container mx-auto max-w-6xl">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-pink-900 mb-4">Categorías en {country.name}</h2>
            <p className="text-slate-600">Encuentra el profesional perfecto para ti</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {CATEGORIES.map((category) => (
              <Link key={category.id} href={`/${params.country}/categoria/${category.slug}`}>
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

      {/* Profesionales En Línea */}
      {onlineProfessionals.length > 0 && (
        <section className="py-16 px-4 bg-white">
          <div className="container mx-auto max-w-6xl">
            <div className="flex items-center justify-between mb-12">
              <div>
                <h2 className="text-3xl font-bold text-pink-900 mb-2 flex items-center gap-2">
                  <Video className="h-8 w-8 text-pink-600" />
                  En Línea Ahora en {country.name}
                </h2>
                <p className="text-slate-600">Profesionales disponibles para videochat</p>
              </div>
              <Link href={`/${params.country}/profesionales?filter=online`}>
                <Button variant="outline" className="border-pink-300 text-pink-600 hover:bg-pink-50">Ver Todos</Button>
              </Link>
            </div>

            <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
              {onlineProfessionals.map((professional) => (
                <Card key={professional.id} className="overflow-hidden hover:shadow-xl transition-shadow border-2 border-blue-200">
                  <div className="h-64 bg-gradient-to-br from-pink-300 to-blue-200 relative">
                    <Badge className="absolute top-4 right-4 bg-green-500 animate-pulse">
                      <div className="w-2 h-2 bg-white rounded-full mr-1" />
                      EN VIVO
                    </Badge>
                    <Badge className="absolute top-4 left-4 bg-pink-600">
                      {professional.membershipType}
                    </Badge>
                    <Badge className="absolute bottom-4 left-4 bg-white/90 text-slate-900">
                      <MapPin className="h-3 w-3 mr-1" />
                      {professional.province?.name}
                    </Badge>
                  </div>
                  <CardHeader className="pb-3">
                    <CardTitle className="text-lg">{professional.user.name}</CardTitle>
                    <CardDescription className="flex items-center gap-1">
                      <Star className="h-4 w-4 fill-yellow-400 text-yellow-400" />
                      <span className="font-medium text-slate-900">{professional.rating?.toFixed(1) || '0.0'}</span>
                      <span className="text-slate-500">({professional.reviewsCount})</span>
                    </CardDescription>
                  </CardHeader>
                  <CardContent className="pb-4">
                    <p className="text-sm text-slate-600 mb-2">{professional.category?.name}</p>
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
      )}

      {/* CTA Final específico del país */}
      <section className="py-16 px-4 bg-gradient-to-r from-pink-600 to-blue-400 text-white">
        <div className="container mx-auto max-w-4xl text-center">
          <h2 className="text-4xl font-bold mb-4">¿Eres Profesional en {country.name}?</h2>
          <p className="text-xl mb-8 text-pink-50">
            Únete a nuestra plataforma y empieza a monetizar tu contenido
          </p>
          <div className="flex gap-4 justify-center">
            <Link href={`/${params.country}/registro-modelo`}>
              <Button size="lg" variant="secondary" className="h-12 px-8 bg-white text-pink-600 hover:bg-pink-50">
                Registrarse como Profesional
              </Button>
            </Link>
            <Link href={`/${params.country}/membresias`}>
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
