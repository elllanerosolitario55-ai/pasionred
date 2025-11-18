import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { COUNTRIES } from '@/lib/constants';
import Link from 'next/link';
import { MapPin, Globe } from 'lucide-react';

export default function PaisesPage() {
  const countriesList = Object.values(COUNTRIES);

  return (
    <div className="min-h-screen bg-slate-50">
      {/* Header */}
      <section className="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-16 px-4">
        <div className="container mx-auto max-w-6xl text-center">
          <Globe className="h-16 w-16 mx-auto mb-4 text-emerald-400" />
          <h1 className="text-4xl md:text-5xl font-bold mb-4">Presencia Global</h1>
          <p className="text-xl text-slate-300 max-w-2xl mx-auto">
            Profesionales verificados en 18 países
          </p>
        </div>
      </section>

      {/* Stats */}
      <section className="py-8 px-4 bg-white border-b">
        <div className="container mx-auto max-w-6xl">
          <div className="grid grid-cols-3 gap-4 text-center">
            <div>
              <div className="text-3xl font-bold text-emerald-600">{countriesList.length}</div>
              <div className="text-sm text-slate-600">Países</div>
            </div>
            <div>
              <div className="text-3xl font-bold text-emerald-600">
                {countriesList.reduce((acc, c) => acc + c.provinces.length, 0)}
              </div>
              <div className="text-sm text-slate-600">Ciudades</div>
            </div>
            <div>
              <div className="text-3xl font-bold text-emerald-600">1,500+</div>
              <div className="text-sm text-slate-600">Profesionales</div>
            </div>
          </div>
        </div>
      </section>

      {/* Countries Grid */}
      <section className="py-16 px-4">
        <div className="container mx-auto max-w-6xl">
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {countriesList.map(({ country, provinces }) => (
              <Card key={country.id} className="hover:shadow-xl transition-shadow">
                <CardHeader>
                  <div className="flex items-start justify-between mb-2">
                    <div className="flex items-center gap-2">
                      <div className="w-12 h-12 bg-gradient-to-br from-emerald-400 to-cyan-500 rounded-xl flex items-center justify-center">
                        <span className="text-2xl">{country.code === 'ES' ? '🇪🇸' : country.code === 'PT' ? '🇵🇹' : country.code === 'FR' ? '🇫🇷' : country.code === 'DE' ? '🇩🇪' : country.code === 'IT' ? '🇮🇹' : country.code === 'MX' ? '🇲🇽' : country.code === 'AR' ? '🇦🇷' : country.code === 'CO' ? '🇨🇴' : '🌍'}</span>
                      </div>
                      <div>
                        <CardTitle className="text-xl">{country.name}</CardTitle>
                      </div>
                    </div>
                    <Badge variant="secondary">{provinces.length}</Badge>
                  </div>
                  <CardDescription>
                    {provinces.length} ciudades disponibles
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  <div className="space-y-2">
                    <div className="text-sm font-medium text-slate-700 mb-2">Principales ciudades:</div>
                    <div className="flex flex-wrap gap-2">
                      {provinces.slice(0, 5).map((province) => (
                        <Link
                          key={province.id}
                          href={`/pais/${country.slug}/${province.slug}`}
                        >
                          <Badge variant="outline" className="cursor-pointer hover:bg-slate-100">
                            <MapPin className="h-3 w-3 mr-1" />
                            {province.name}
                          </Badge>
                        </Link>
                      ))}
                      {provinces.length > 5 && (
                        <Badge variant="outline">+{provinces.length - 5} más</Badge>
                      )}
                    </div>
                  </div>
                  <Link
                    href={`/pais/${country.slug}`}
                    className="block mt-4 text-sm text-emerald-600 font-medium hover:text-emerald-700"
                  >
                    Ver todos los profesionales →
                  </Link>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
