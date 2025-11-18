'use client';

import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Check, X, Crown, Award, Shield } from 'lucide-react';
import { MEMBERSHIP_CONFIG } from '@/lib/constants';
import { PaymentModal } from '@/components/payment/PaymentModal';

export default function MembresiasPage() {
  const [isPaymentOpen, setIsPaymentOpen] = useState(false);
  const [selectedMembership, setSelectedMembership] = useState<{
    type: string;
    amount: number;
    description: string;
  } | null>(null);

  const handleSelectMembership = (type: string, amount: number, description: string) => {
    setSelectedMembership({ type, amount, description });
    setIsPaymentOpen(true);
  };

  const handlePaymentSuccess = (paymentData: any) => {
    console.log('Payment successful:', paymentData);
    // Aquí podrías:
    // 1. Actualizar la membresía del usuario en la BD
    // 2. Mostrar mensaje de éxito
    // 3. Redirigir al panel
    // 4. Enviar email de confirmación
  };

  const handlePaymentError = (error: string) => {
    console.error('Payment error:', error);
    // Aquí podrías:
    // 1. Mostrar toast de error
    // 2. Log error para analytics
  };

  return (
    <div className="min-h-screen bg-slate-50">
      {/* Hero */}
      <section className="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white py-20 px-4">
        <div className="container mx-auto max-w-6xl text-center">
          <h1 className="text-5xl font-bold mb-4">Elige tu Membresía</h1>
          <p className="text-xl text-slate-300 max-w-2xl mx-auto">
            Selecciona el plan perfecto para empezar a monetizar tu experiencia profesional
          </p>
        </div>
      </section>

      {/* Pricing Cards */}
      <section className="py-16 px-4">
        <div className="container mx-auto max-w-6xl">
          <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            {/* Free */}
            <Card className="relative">
              <CardHeader>
                <div className="flex items-center gap-2 mb-2">
                  <Shield className="h-5 w-5 text-slate-500" />
                  <CardTitle>Gratis</CardTitle>
                </div>
                <div className="text-4xl font-bold">0€<span className="text-lg font-normal text-slate-500">/mes</span></div>
                <CardDescription>Para empezar</CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                <Button className="w-full" variant="outline">Comenzar Gratis</Button>
                <ul className="space-y-2">
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>5 publicaciones al mes</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Perfil básico</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm text-slate-400">
                    <X className="h-4 w-4 text-red-400" />
                    <span>Imágenes de pago</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm text-slate-400">
                    <X className="h-4 w-4 text-red-400" />
                    <span>Videos</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm text-slate-400">
                    <X className="h-4 w-4 text-red-400" />
                    <span>Videochat</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm text-slate-400">
                    <X className="h-4 w-4 text-red-400" />
                    <span>Streaming</span>
                  </li>
                </ul>
              </CardContent>
            </Card>

            {/* Bronze */}
            <Card className="relative border-orange-200 bg-gradient-to-br from-orange-50 to-white">
              <CardHeader>
                <div className="flex items-center gap-2 mb-2">
                  <Award className="h-5 w-5 text-orange-600" />
                  <CardTitle className="text-orange-900">Bronce</CardTitle>
                </div>
                <div className="text-4xl font-bold text-orange-900">20€<span className="text-lg font-normal text-slate-500">/mes</span></div>
                <CardDescription>Para profesionales activos</CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                <Button
                  className="w-full bg-orange-600 hover:bg-orange-700"
                  onClick={() => handleSelectMembership('BRONZE', 20, 'Membresía Bronce - Mensual')}
                >
                  Elegir Bronce
                </Button>
                <ul className="space-y-2">
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>50 publicaciones al mes</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Imágenes de pago</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Videos</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Videochat</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Reviews y valoraciones</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm text-slate-400">
                    <X className="h-4 w-4 text-red-400" />
                    <span>Streaming</span>
                  </li>
                </ul>
              </CardContent>
            </Card>

            {/* Silver */}
            <Card className="relative border-gray-300 bg-gradient-to-br from-gray-100 to-white">
              <Badge className="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-gray-400 to-gray-600">
                Popular
              </Badge>
              <CardHeader>
                <div className="flex items-center gap-2 mb-2">
                  <Award className="h-5 w-5 text-gray-600" />
                  <CardTitle className="text-gray-900">Plata</CardTitle>
                </div>
                <div className="text-4xl font-bold text-gray-900">45€<span className="text-lg font-normal text-slate-500">/mes</span></div>
                <CardDescription>Para expertos</CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                <Button
                  className="w-full bg-gradient-to-r from-gray-500 to-gray-700 hover:from-gray-600 hover:to-gray-800"
                  onClick={() => handleSelectMembership('SILVER', 45, 'Membresía Plata - Mensual')}
                >
                  Elegir Plata
                </Button>
                <ul className="space-y-2">
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>200 publicaciones al mes</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Todo lo de Bronce</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Streaming en vivo</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Alta visibilidad</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Precios flexibles</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Horarios personalizados</span>
                  </li>
                </ul>
              </CardContent>
            </Card>

            {/* Gold */}
            <Card className="relative border-yellow-300 bg-gradient-to-br from-yellow-50 to-white">
              <Badge className="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-yellow-400 to-yellow-600">
                Recomendado
              </Badge>
              <CardHeader>
                <div className="flex items-center gap-2 mb-2">
                  <Crown className="h-5 w-5 text-yellow-600" />
                  <CardTitle className="text-yellow-900">Oro</CardTitle>
                </div>
                <div className="text-4xl font-bold text-yellow-900">65€<span className="text-lg font-normal text-slate-500">/mes</span></div>
                <CardDescription>Para líderes</CardDescription>
              </CardHeader>
              <CardContent className="space-y-4">
                <Button
                  className="w-full bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-yellow-950"
                  onClick={() => handleSelectMembership('GOLD', 65, 'Membresía Oro - Mensual')}
                >
                  Elegir Oro
                </Button>
                <ul className="space-y-2">
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span className="font-semibold">Publicaciones ilimitadas</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Todo lo de Plata</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Perfil destacado</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Visibilidad premium</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Soporte prioritario</span>
                  </li>
                  <li className="flex items-center gap-2 text-sm">
                    <Check className="h-4 w-4 text-green-600" />
                    <span>Todas las funciones</span>
                  </li>
                </ul>
              </CardContent>
            </Card>
          </div>

          {/* Comparison Table */}
          <div className="mt-16">
            <h2 className="text-3xl font-bold text-center mb-8">Comparación Detallada</h2>
            <div className="bg-white rounded-lg shadow overflow-x-auto">
              <table className="w-full">
                <thead className="bg-slate-100">
                  <tr>
                    <th className="px-6 py-4 text-left">Característica</th>
                    <th className="px-6 py-4 text-center">Gratis</th>
                    <th className="px-6 py-4 text-center">Bronce</th>
                    <th className="px-6 py-4 text-center">Plata</th>
                    <th className="px-6 py-4 text-center">Oro</th>
                  </tr>
                </thead>
                <tbody className="divide-y">
                  <tr>
                    <td className="px-6 py-4">Publicaciones mensuales</td>
                    <td className="px-6 py-4 text-center">5</td>
                    <td className="px-6 py-4 text-center">50</td>
                    <td className="px-6 py-4 text-center">200</td>
                    <td className="px-6 py-4 text-center">Ilimitadas</td>
                  </tr>
                  <tr>
                    <td className="px-6 py-4">Imágenes de pago</td>
                    <td className="px-6 py-4 text-center"><X className="h-5 w-5 text-red-400 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                  </tr>
                  <tr>
                    <td className="px-6 py-4">Videos</td>
                    <td className="px-6 py-4 text-center"><X className="h-5 w-5 text-red-400 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                  </tr>
                  <tr>
                    <td className="px-6 py-4">Videochat</td>
                    <td className="px-6 py-4 text-center"><X className="h-5 w-5 text-red-400 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                  </tr>
                  <tr>
                    <td className="px-6 py-4">Streaming en vivo</td>
                    <td className="px-6 py-4 text-center"><X className="h-5 w-5 text-red-400 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><X className="h-5 w-5 text-red-400 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                  </tr>
                  <tr>
                    <td className="px-6 py-4">Reviews y valoraciones</td>
                    <td className="px-6 py-4 text-center"><X className="h-5 w-5 text-red-400 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                  </tr>
                  <tr>
                    <td className="px-6 py-4">Perfil destacado</td>
                    <td className="px-6 py-4 text-center"><X className="h-5 w-5 text-red-400 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><X className="h-5 w-5 text-red-400 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><X className="h-5 w-5 text-red-400 mx-auto" /></td>
                    <td className="px-6 py-4 text-center"><Check className="h-5 w-5 text-green-600 mx-auto" /></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      {/* Payment Modal */}
      {selectedMembership && (
        <PaymentModal
          isOpen={isPaymentOpen}
          onClose={() => {
            setIsPaymentOpen(false);
            setSelectedMembership(null);
          }}
          amount={selectedMembership.amount}
          currency="EUR"
          description={selectedMembership.description}
          type="membership"
          metadata={{
            membershipType: selectedMembership.type,
          }}
          onSuccess={handlePaymentSuccess}
          onError={handlePaymentError}
        />
      )}
    </div>
  );
}
