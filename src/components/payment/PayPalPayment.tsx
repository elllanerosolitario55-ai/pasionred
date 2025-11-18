'use client';

import { useState, useEffect, useRef } from 'react';
import { Button } from '@/components/ui/button';
import { Loader2 } from 'lucide-react';

interface PayPalPaymentProps {
  amount: number;
  currency: string;
  description: string;
  type: string;
  metadata?: any;
  onSuccess: (paymentData: any) => void;
  onError: (error: string) => void;
  onProcessing: (processing: boolean) => void;
}

declare global {
  interface Window {
    paypal?: any;
  }
}

export function PayPalPayment({
  amount,
  currency,
  description,
  type,
  metadata,
  onSuccess,
  onError,
  onProcessing,
}: PayPalPaymentProps) {
  const [loading, setLoading] = useState(true);
  const [scriptLoaded, setScriptLoaded] = useState(false);
  const paypalRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    // Cargar script de PayPal
    const loadPayPalScript = () => {
      if (window.paypal) {
        setScriptLoaded(true);
        setLoading(false);
        return;
      }

      const script = document.createElement('script');
      script.src = `https://www.paypal.com/sdk/js?client-id=${process.env.NEXT_PUBLIC_PAYPAL_CLIENT_ID}&currency=${currency}`;
      script.async = true;

      script.onload = () => {
        setScriptLoaded(true);
        setLoading(false);
      };

      script.onerror = () => {
        onError('Error al cargar PayPal');
        setLoading(false);
      };

      document.body.appendChild(script);
    };

    loadPayPalScript();
  }, []);

  useEffect(() => {
    if (!scriptLoaded || !window.paypal || !paypalRef.current) {
      return;
    }

    // Renderizar botones de PayPal
    window.paypal
      .Buttons({
        style: {
          layout: 'vertical',
          color: 'blue',
          shape: 'rect',
          label: 'pay',
        },

        createOrder: async () => {
          try {
            onProcessing(true);

            const response = await fetch('/api/payment/paypal/create-order', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                amount,
                currency,
                description,
                metadata: {
                  ...metadata,
                  type,
                },
              }),
            });

            if (!response.ok) {
              throw new Error('Error al crear orden de PayPal');
            }

            const data = await response.json();
            return data.orderId;
          } catch (error: any) {
            console.error('Error creating PayPal order:', error);
            onError(error.message || 'Error al crear orden');
            onProcessing(false);
            throw error;
          }
        },

        onApprove: async (data: any) => {
          try {
            const response = await fetch('/api/payment/paypal/capture-order', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                orderId: data.orderID,
              }),
            });

            if (!response.ok) {
              throw new Error('Error al capturar pago');
            }

            const orderData = await response.json();

            onSuccess({
              orderId: orderData.id,
              amount: parseFloat(orderData.purchase_units[0].amount.value),
              currency: orderData.purchase_units[0].amount.currency_code,
              status: orderData.status,
            });

            onProcessing(false);
          } catch (error: any) {
            console.error('Error capturing PayPal payment:', error);
            onError(error.message || 'Error al procesar pago');
            onProcessing(false);
          }
        },

        onError: (err: any) => {
          console.error('PayPal error:', err);
          onError('Error en el procesamiento de PayPal');
          onProcessing(false);
        },

        onCancel: () => {
          onError('Pago cancelado');
          onProcessing(false);
        },
      })
      .render(paypalRef.current);
  }, [scriptLoaded, amount, currency]);

  if (loading) {
    return (
      <div className="flex items-center justify-center py-8">
        <Loader2 className="h-6 w-6 animate-spin text-blue-600" />
      </div>
    );
  }

  return (
    <div className="space-y-4">
      <div className="bg-blue-50 rounded-lg p-4">
        <p className="text-sm text-blue-900 font-medium">Pago con PayPal</p>
        <p className="text-xs text-blue-700 mt-1">
          Serás redirigido a PayPal para completar el pago de forma segura
        </p>
      </div>

      <div ref={paypalRef} />

      <p className="text-xs text-slate-500 text-center">
        Al hacer clic en el botón de PayPal, aceptas los términos de servicio
      </p>
    </div>
  );
}
