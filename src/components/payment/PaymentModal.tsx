'use client';

import { useState } from 'react';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { CreditCard, Wallet, Loader2, CheckCircle, XCircle } from 'lucide-react';
import { StripePayment } from './StripePayment';
import { PayPalPayment } from './PayPalPayment';
import { cn } from '@/lib/utils';

interface PaymentModalProps {
  isOpen: boolean;
  onClose: () => void;
  amount: number;
  currency?: string;
  description: string;
  type: 'membership' | 'credits' | 'session' | 'content';
  metadata?: any;
  onSuccess?: (paymentData: any) => void;
  onError?: (error: string) => void;
}

export function PaymentModal({
  isOpen,
  onClose,
  amount,
  currency = 'EUR',
  description,
  type,
  metadata,
  onSuccess,
  onError,
}: PaymentModalProps) {
  const [paymentMethod, setPaymentMethod] = useState<'stripe' | 'paypal' | null>(null);
  const [processing, setProcessing] = useState(false);
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleSuccess = (paymentData: any) => {
    setSuccess(true);
    setProcessing(false);

    if (onSuccess) {
      onSuccess(paymentData);
    }

    // Cerrar modal después de 2 segundos
    setTimeout(() => {
      onClose();
      setSuccess(false);
      setPaymentMethod(null);
    }, 2000);
  };

  const handleError = (errorMessage: string) => {
    setError(errorMessage);
    setProcessing(false);

    if (onError) {
      onError(errorMessage);
    }

    // Limpiar error después de 5 segundos
    setTimeout(() => {
      setError(null);
    }, 5000);
  };

  const handleBack = () => {
    setPaymentMethod(null);
    setError(null);
  };

  return (
    <Dialog open={isOpen} onOpenChange={onClose}>
      <DialogContent className="sm:max-w-[500px]">
        <DialogHeader>
          <DialogTitle className="text-2xl">
            {success ? '¡Pago Completado!' : 'Completar Pago'}
          </DialogTitle>
          <DialogDescription>
            {success
              ? 'Tu pago ha sido procesado correctamente'
              : description
            }
          </DialogDescription>
        </DialogHeader>

        {/* Estado de éxito */}
        {success && (
          <div className="flex flex-col items-center justify-center py-8">
            <div className="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mb-4">
              <CheckCircle className="h-12 w-12 text-green-600" />
            </div>
            <p className="text-lg font-medium text-slate-900">Pago Exitoso</p>
            <p className="text-sm text-slate-600 mt-1">
              {amount.toLocaleString('es-ES', { style: 'currency', currency })}
            </p>
          </div>
        )}

        {/* Estado de error */}
        {error && !success && (
          <div className="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
            <XCircle className="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" />
            <div className="flex-1">
              <p className="text-sm font-medium text-red-900">Error en el pago</p>
              <p className="text-sm text-red-700 mt-1">{error}</p>
            </div>
          </div>
        )}

        {!success && (
          <>
            {/* Resumen del pago */}
            <div className="bg-slate-50 rounded-lg p-4 space-y-2">
              <div className="flex items-center justify-between">
                <span className="text-sm text-slate-600">Total a pagar:</span>
                <span className="text-2xl font-bold text-slate-900">
                  {amount.toLocaleString('es-ES', { style: 'currency', currency })}
                </span>
              </div>
              {metadata?.membershipType && (
                <div className="flex items-center justify-between">
                  <span className="text-sm text-slate-600">Membresía:</span>
                  <span className="text-sm font-medium text-slate-900">
                    {metadata.membershipType.toUpperCase()}
                  </span>
                </div>
              )}
            </div>

            {/* Selección de método de pago */}
            {!paymentMethod && (
              <div className="space-y-3">
                <p className="text-sm font-medium text-slate-700">Selecciona método de pago:</p>

                <button
                  onClick={() => setPaymentMethod('stripe')}
                  className={cn(
                    'w-full p-4 rounded-lg border-2 transition-all',
                    'hover:border-pink-500 hover:bg-pink-50',
                    'flex items-center gap-3'
                  )}
                >
                  <div className="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center">
                    <CreditCard className="h-6 w-6 text-white" />
                  </div>
                  <div className="flex-1 text-left">
                    <p className="font-semibold text-slate-900">Tarjeta de Crédito/Débito</p>
                    <p className="text-sm text-slate-600">Visa, Mastercard, American Express</p>
                  </div>
                </button>

                <button
                  onClick={() => setPaymentMethod('paypal')}
                  className={cn(
                    'w-full p-4 rounded-lg border-2 transition-all',
                    'hover:border-blue-500 hover:bg-blue-50',
                    'flex items-center gap-3'
                  )}
                >
                  <div className="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
                    <Wallet className="h-6 w-6 text-white" />
                  </div>
                  <div className="flex-1 text-left">
                    <p className="font-semibold text-slate-900">PayPal</p>
                    <p className="text-sm text-slate-600">Paga con tu cuenta PayPal</p>
                  </div>
                </button>
              </div>
            )}

            {/* Formulario de Stripe */}
            {paymentMethod === 'stripe' && (
              <div className="space-y-4">
                <Button
                  onClick={handleBack}
                  variant="ghost"
                  className="mb-2"
                >
                  ← Volver a métodos de pago
                </Button>

                <StripePayment
                  amount={amount}
                  currency={currency}
                  description={description}
                  type={type}
                  metadata={metadata}
                  onSuccess={handleSuccess}
                  onError={handleError}
                  onProcessing={setProcessing}
                />
              </div>
            )}

            {/* Formulario de PayPal */}
            {paymentMethod === 'paypal' && (
              <div className="space-y-4">
                <Button
                  onClick={handleBack}
                  variant="ghost"
                  className="mb-2"
                >
                  ← Volver a métodos de pago
                </Button>

                <PayPalPayment
                  amount={amount}
                  currency={currency}
                  description={description}
                  type={type}
                  metadata={metadata}
                  onSuccess={handleSuccess}
                  onError={handleError}
                  onProcessing={setProcessing}
                />
              </div>
            )}

            {/* Procesando */}
            {processing && (
              <div className="flex items-center justify-center py-4">
                <Loader2 className="h-6 w-6 animate-spin text-pink-600 mr-2" />
                <span className="text-sm text-slate-600">Procesando pago...</span>
              </div>
            )}

            {/* Seguridad */}
            <div className="border-t pt-4">
              <p className="text-xs text-slate-500 text-center">
                🔒 Pago seguro y encriptado. Tus datos están protegidos.
              </p>
            </div>
          </>
        )}
      </DialogContent>
    </Dialog>
  );
}
