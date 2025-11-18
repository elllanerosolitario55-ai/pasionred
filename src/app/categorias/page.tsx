import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { CATEGORIES } from '@/lib/constants';
import Link from 'next/link';
import { Users, TrendingUp } from 'lucide-react';

export default function CategoriasPage() {
  return (
    <div className="min-h-screen bg-slate-50">
      {/* Header */}
      <section className="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-16 px-4">
        <div className="container mx-auto max-w-6xl text-center">
          <h1 className="text-4xl md:text-5xl font-bold mb-4">Categorías Profesionales</h1>
          <p className="text-xl text-slate-300 max-w-2xl mx-auto">
            Encuentra expertos en tu área de interés
          </p>
        </div>
      </section>

      {/* Categories Grid */}
      <section className="py-16 px-4">
        <div className="container mx-auto max-w-6xl">
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {CATEGORIES.map((category) => (
              <Link key={category.id} href={`/categoria/${category.slug}`}>
                <Card className="hover:shadow-xl transition-all hover:scale-105 cursor-pointer h-full">
                  <CardHeader>
                    <div className="flex items-start justify-between mb-4">
                      <div className="w-16 h-16 bg-gradient-to-br from-emerald-400 to-cyan-500 rounded-2xl flex items-center justify-center">
                        <Users className="h-8 w-8 text-white" />
                      </div>
                      <Badge variant="secondary" className="gap-1">
                        <TrendingUp className="h-3 w-3" />
                        {Math.floor(Math.random() * 200) + 50}
                      </Badge>
                    </div>
                    <CardTitle className="text-xl">{category.name}</CardTitle>
                    <CardDescription className="line-clamp-2">
                      {category.description}
                    </CardDescription>
                  </CardHeader>
                  <CardContent>
                    <div className="flex items-center justify-between text-sm">
                      <span className="text-slate-600">
                        {Math.floor(Math.random() * 50) + 10} profesionales
                      </span>
                      <span className="text-emerald-600 font-medium">Ver todos →</span>
                    </div>
                  </CardContent>
                </Card>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-16 px-4 bg-white">
        <div className="container mx-auto max-w-4xl text-center">
          <h2 className="text-3xl font-bold text-slate-900 mb-4">¿No encuentras tu categoría?</h2>
          <p className="text-slate-600 mb-8">
            Estamos agregando nuevas categorías constantemente. Contáctanos para sugerencias.
          </p>
          <Link href="/contacto" className="inline-flex items-center justify-center px-8 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">
            Contactar
          </Link>
        </div>
      </section>
    </div>
  );
}
