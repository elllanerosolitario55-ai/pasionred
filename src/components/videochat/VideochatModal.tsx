'use client';

import { useState, useEffect, useRef } from 'react';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Mic, MicOff, Video, VideoOff, Phone, PhoneOff } from 'lucide-react';

interface VideochatModalProps {
  professionalId: string;
  professionalName: string;
  onClose: () => void;
}

export function VideochatModal({ professionalId, professionalName, onClose }: VideochatModalProps) {
  const [isConnected, setIsConnected] = useState(false);
  const [isMuted, setIsMuted] = useState(false);
  const [isVideoOff, setIsVideoOff] = useState(false);
  const [duration, setDuration] = useState(0);
  const [error, setError] = useState<string | null>(null);

  const localVideoRef = useRef<HTMLVideoElement>(null);
  const remoteVideoRef = useRef<HTMLVideoElement>(null);
  const peerConnectionRef = useRef<RTCPeerConnection | null>(null);
  const localStreamRef = useRef<MediaStream | null>(null);
  const timerRef = useRef<NodeJS.Timeout | null>(null);

  useEffect(() => {
    initializeWebRTC();
    return () => cleanup();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const initializeWebRTC = async () => {
    try {
      // Solicitar permisos de cámara y micrófono
      const stream = await navigator.mediaDevices.getUserMedia({
        video: {
          width: { ideal: 1280 },
          height: { ideal: 720 }
        },
        audio: {
          echoCancellation: true,
          noiseSuppression: true,
          autoGainControl: true
        }
      });

      localStreamRef.current = stream;

      if (localVideoRef.current) {
        localVideoRef.current.srcObject = stream;
      }

      // Configurar peer connection
      const config: RTCConfiguration = {
        iceServers: [
          { urls: 'stun:stun.l.google.com:19302' },
          { urls: 'stun:stun1.l.google.com:19302' }
        ]
      };

      const peerConnection = new RTCPeerConnection(config);
      peerConnectionRef.current = peerConnection;

      // Agregar tracks locales
      stream.getTracks().forEach(track => {
        peerConnection.addTrack(track, stream);
      });

      // Manejar tracks remotos
      peerConnection.ontrack = (event) => {
        if (remoteVideoRef.current && event.streams[0]) {
          remoteVideoRef.current.srcObject = event.streams[0];
          setIsConnected(true);
          startTimer();
        }
      };

      // Manejar candidatos ICE
      peerConnection.onicecandidate = (event) => {
        if (event.candidate) {
          // Enviar candidato al servidor (implementar con WebSocket)
          console.log('ICE candidate:', event.candidate);
        }
      };

      // Manejar cambios de estado de conexión
      peerConnection.onconnectionstatechange = () => {
        console.log('Connection state:', peerConnection.connectionState);
        if (peerConnection.connectionState === 'disconnected' ||
            peerConnection.connectionState === 'failed') {
          handleEndCall();
        }
      };

    } catch (err: unknown) {
      console.error('Error inicializando WebRTC:', err);
      setError('No se pudo acceder a la cámara o micrófono. Por favor, verifica los permisos.');
    }
  };

  const startTimer = () => {
    timerRef.current = setInterval(() => {
      setDuration(prev => prev + 1);
    }, 1000);
  };

  const toggleMute = () => {
    if (localStreamRef.current) {
      const audioTrack = localStreamRef.current.getAudioTracks()[0];
      if (audioTrack) {
        audioTrack.enabled = !audioTrack.enabled;
        setIsMuted(!audioTrack.enabled);
      }
    }
  };

  const toggleVideo = () => {
    if (localStreamRef.current) {
      const videoTrack = localStreamRef.current.getVideoTracks()[0];
      if (videoTrack) {
        videoTrack.enabled = !videoTrack.enabled;
        setIsVideoOff(!videoTrack.enabled);
      }
    }
  };

  const handleEndCall = () => {
    cleanup();
    onClose();
  };

  const cleanup = () => {
    // Detener timer
    if (timerRef.current) {
      clearInterval(timerRef.current);
    }

    // Detener streams
    if (localStreamRef.current) {
      localStreamRef.current.getTracks().forEach(track => track.stop());
    }

    // Cerrar peer connection
    if (peerConnectionRef.current) {
      peerConnectionRef.current.close();
    }
  };

  const formatDuration = (seconds: number) => {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins}:${secs.toString().padStart(2, '0')}`;
  };

  return (
    <div className="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4">
      <div className="w-full max-w-6xl">
        {/* Header */}
        <div className="bg-slate-900 text-white p-4 rounded-t-xl flex items-center justify-between">
          <div>
            <h2 className="text-xl font-bold">{professionalName}</h2>
            <p className="text-sm text-slate-400">
              {isConnected ? `Conectado - ${formatDuration(duration)}` : 'Conectando...'}
            </p>
          </div>
          {isConnected && (
            <div className="text-2xl font-bold text-emerald-400">
              {formatDuration(duration)}
            </div>
          )}
        </div>

        {/* Video Container */}
        <div className="relative bg-slate-950 aspect-video rounded-b-xl overflow-hidden">
          {error ? (
            <div className="absolute inset-0 flex items-center justify-center text-white">
              <div className="text-center p-8">
                <Phone className="h-16 w-16 mx-auto mb-4 text-red-400" />
                <p className="text-lg mb-4">{error}</p>
                <Button onClick={onClose} variant="outline">Cerrar</Button>
              </div>
            </div>
          ) : (
            <>
              {/* Remote Video (Main) */}
              <video
                ref={remoteVideoRef}
                autoPlay
                playsInline
                className="w-full h-full object-cover"
              />

              {/* Local Video (PiP) */}
              <div className="absolute top-4 right-4 w-48 h-36 rounded-lg overflow-hidden border-2 border-white shadow-xl">
                <video
                  ref={localVideoRef}
                  autoPlay
                  playsInline
                  muted
                  className="w-full h-full object-cover mirror"
                />
              </div>

              {/* Controls */}
              <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6">
                <div className="flex items-center justify-center gap-4">
                  <Button
                    size="lg"
                    variant={isMuted ? "destructive" : "secondary"}
                    className="w-14 h-14 rounded-full"
                    onClick={toggleMute}
                  >
                    {isMuted ? <MicOff className="h-6 w-6" /> : <Mic className="h-6 w-6" />}
                  </Button>

                  <Button
                    size="lg"
                    variant={isVideoOff ? "destructive" : "secondary"}
                    className="w-14 h-14 rounded-full"
                    onClick={toggleVideo}
                  >
                    {isVideoOff ? <VideoOff className="h-6 w-6" /> : <Video className="h-6 w-6" />}
                  </Button>

                  <Button
                    size="lg"
                    variant="destructive"
                    className="w-14 h-14 rounded-full"
                    onClick={handleEndCall}
                  >
                    <PhoneOff className="h-6 w-6" />
                  </Button>
                </div>
              </div>
            </>
          )}
        </div>
      </div>

      <style jsx>{`
        .mirror {
          transform: scaleX(-1);
        }
      `}</style>
    </div>
  );
}
