/**
 * Pasiones Platform - WebRTC Videochat
 * HTML5 Videochat con tecnología WebRTC
 */

(function($) {
    'use strict';

    class PasionesWebRTC {
        constructor() {
            this.localStream = null;
            this.remoteStream = null;
            this.peerConnection = null;
            this.sessionId = null;
            this.config = null;
        }

        /**
         * Inicializar WebRTC
         */
        async init(professionalId) {
            try {
                // Obtener configuración WebRTC del servidor
                this.config = await this.getWebRTCConfig();

                // Solicitar permisos de cámara y micrófono
                this.localStream = await navigator.mediaDevices.getUserMedia({
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

                // Mostrar video local
                const localVideo = document.getElementById('local-video');
                if (localVideo) {
                    localVideo.srcObject = this.localStream;
                }

                // Iniciar sesión de videochat
                await this.startSession(professionalId);

                // Crear conexión peer
                this.createPeerConnection();

                return true;
            } catch (error) {
                console.error('Error iniciando WebRTC:', error);
                this.handleError(error);
                return false;
            }
        }

        /**
         * Obtener configuración WebRTC
         */
        async getWebRTCConfig() {
            const response = await fetch(pasionesData.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'get_webrtc_config',
                })
            });

            const data = await response.json();
            return data.data;
        }

        /**
         * Iniciar sesión de videochat
         */
        async startSession(professionalId) {
            const response = await fetch(pasionesData.ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'start_videochat',
                    nonce: pasionesData.nonce,
                    professional_id: professionalId,
                })
            });

            const data = await response.json();

            if (data.success) {
                this.sessionId = data.data.session_id;
                this.startSessionTimer();
            } else {
                throw new Error(data.data.message);
            }
        }

        /**
         * Crear conexión peer
         */
        createPeerConnection() {
            this.peerConnection = new RTCPeerConnection(this.config);

            // Agregar tracks locales
            this.localStream.getTracks().forEach(track => {
                this.peerConnection.addTrack(track, this.localStream);
            });

            // Manejar tracks remotos
            this.peerConnection.ontrack = (event) => {
                const remoteVideo = document.getElementById('remote-video');
                if (remoteVideo && event.streams[0]) {
                    remoteVideo.srcObject = event.streams[0];
                    this.remoteStream = event.streams[0];
                }
            };

            // Manejar candidatos ICE
            this.peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    this.sendICECandidate(event.candidate);
                }
            };

            // Manejar cambios de estado
            this.peerConnection.onconnectionstatechange = () => {
                console.log('Connection state:', this.peerConnection.connectionState);

                if (this.peerConnection.connectionState === 'connected') {
                    this.onConnected();
                } else if (this.peerConnection.connectionState === 'disconnected') {
                    this.onDisconnected();
                }
            };
        }

        /**
         * Crear oferta
         */
        async createOffer() {
            const offer = await this.peerConnection.createOffer();
            await this.peerConnection.setLocalDescription(offer);
            return offer;
        }

        /**
         * Crear respuesta
         */
        async createAnswer() {
            const answer = await this.peerConnection.createAnswer();
            await this.peerConnection.setLocalDescription(answer);
            return answer;
        }

        /**
         * Establecer descripción remota
         */
        async setRemoteDescription(description) {
            await this.peerConnection.setRemoteDescription(new RTCSessionDescription(description));
        }

        /**
         * Agregar candidato ICE
         */
        async addICECandidate(candidate) {
            await this.peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
        }

        /**
         * Enviar candidato ICE (implementar con WebSocket o AJAX)
         */
        sendICECandidate(candidate) {
            // Implementar envío de candidato al servidor
            console.log('ICE candidate:', candidate);
        }

        /**
         * Iniciar temporizador de sesión
         */
        startSessionTimer() {
            let seconds = 0;
            const timerElement = document.getElementById('session-timer');

            this.timerInterval = setInterval(() => {
                seconds++;
                const minutes = Math.floor(seconds / 60);
                const secs = seconds % 60;

                if (timerElement) {
                    timerElement.textContent = `${minutes}:${secs.toString().padStart(2, '0')}`;
                }
            }, 1000);
        }

        /**
         * Finalizar sesión
         */
        async endSession() {
            try {
                // Detener temporizador
                if (this.timerInterval) {
                    clearInterval(this.timerInterval);
                }

                // Detener streams
                if (this.localStream) {
                    this.localStream.getTracks().forEach(track => track.stop());
                }

                // Cerrar conexión peer
                if (this.peerConnection) {
                    this.peerConnection.close();
                }

                // Notificar al servidor
                if (this.sessionId) {
                    await fetch(pasionesData.ajaxUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'end_videochat',
                            nonce: pasionesData.nonce,
                            session_id: this.sessionId,
                        })
                    });
                }

                this.onSessionEnded();
            } catch (error) {
                console.error('Error finalizando sesión:', error);
            }
        }

        /**
         * Alternar mute de audio
         */
        toggleAudio() {
            if (this.localStream) {
                const audioTrack = this.localStream.getAudioTracks()[0];
                if (audioTrack) {
                    audioTrack.enabled = !audioTrack.enabled;
                    return audioTrack.enabled;
                }
            }
            return false;
        }

        /**
         * Alternar video
         */
        toggleVideo() {
            if (this.localStream) {
                const videoTrack = this.localStream.getVideoTracks()[0];
                if (videoTrack) {
                    videoTrack.enabled = !videoTrack.enabled;
                    return videoTrack.enabled;
                }
            }
            return false;
        }

        /**
         * Callbacks
         */
        onConnected() {
            console.log('WebRTC conectado');
            $(document).trigger('pasiones:webrtc:connected');
        }

        onDisconnected() {
            console.log('WebRTC desconectado');
            $(document).trigger('pasiones:webrtc:disconnected');
        }

        onSessionEnded() {
            console.log('Sesión finalizada');
            $(document).trigger('pasiones:session:ended');
        }

        handleError(error) {
            let message = 'Error en la conexión';

            if (error.name === 'NotAllowedError') {
                message = 'Por favor, permite el acceso a la cámara y micrófono';
            } else if (error.name === 'NotFoundError') {
                message = 'No se encontró cámara o micrófono';
            }

            alert(message);
            $(document).trigger('pasiones:webrtc:error', [error]);
        }
    }

    // Exponer globalmente
    window.PasionesWebRTC = PasionesWebRTC;

    // Inicializar cuando el documento esté listo
    $(document).ready(function() {
        // Event handler para botones de videochat
        $(document).on('click', '.start-videochat', function(e) {
            e.preventDefault();

            const professionalId = $(this).data('professional-id');
            const webrtc = new PasionesWebRTC();

            // Mostrar modal de videochat
            $('#videochat-modal').fadeIn();

            // Iniciar WebRTC
            webrtc.init(professionalId).then(success => {
                if (!success) {
                    $('#videochat-modal').fadeOut();
                }
            });

            // Guardar instancia
            window.currentWebRTC = webrtc;
        });

        // Event handler para finalizar videochat
        $(document).on('click', '#end-videochat', function() {
            if (window.currentWebRTC) {
                window.currentWebRTC.endSession();
                $('#videochat-modal').fadeOut();
            }
        });

        // Event handler para toggle audio
        $(document).on('click', '#toggle-audio', function() {
            if (window.currentWebRTC) {
                const enabled = window.currentWebRTC.toggleAudio();
                $(this).toggleClass('muted', !enabled);
            }
        });

        // Event handler para toggle video
        $(document).on('click', '#toggle-video', function() {
            if (window.currentWebRTC) {
                const enabled = window.currentWebRTC.toggleVideo();
                $(this).toggleClass('disabled', !enabled);
            }
        });
    });

})(jQuery);
