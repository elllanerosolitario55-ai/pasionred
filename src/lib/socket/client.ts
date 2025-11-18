import { io, Socket } from 'socket.io-client';

class SocketClient {
  private socket: Socket | null = null;
  private userId: string | null = null;

  connect(userId: string) {
    if (this.socket?.connected) {
      return this.socket;
    }

    const socketUrl = process.env.NEXT_PUBLIC_SOCKET_URL || 'http://localhost:3001';

    this.socket = io(socketUrl, {
      autoConnect: true,
      reconnection: true,
      reconnectionDelay: 1000,
      reconnectionAttempts: 5,
    });

    this.userId = userId;

    this.socket.on('connect', () => {
      console.log('Conectado a Socket.io');
      this.socket?.emit('auth', userId);
    });

    this.socket.on('disconnect', () => {
      console.log('Desconectado de Socket.io');
    });

    this.socket.on('error', (error) => {
      console.error('Error de Socket.io:', error);
    });

    return this.socket;
  }

  disconnect() {
    if (this.socket) {
      this.socket.disconnect();
      this.socket = null;
      this.userId = null;
    }
  }

  sendMessage(data: {
    receiverId: string;
    content: string;
    type?: 'TEXT' | 'IMAGE' | 'VIDEO' | 'AUDIO';
    mediaUrl?: string;
  }) {
    if (!this.socket) {
      throw new Error('Socket no conectado');
    }

    this.socket.emit('message:send', {
      ...data,
      type: data.type || 'TEXT',
    });
  }

  markAsRead(senderId: string) {
    if (!this.socket) return;

    this.socket.emit('messages:read', { senderId });
  }

  startTyping(receiverId: string) {
    if (!this.socket) return;

    this.socket.emit('typing:start', { receiverId });
  }

  stopTyping(receiverId: string) {
    if (!this.socket) return;

    this.socket.emit('typing:stop', { receiverId });
  }

  sendWebRTCOffer(to: string, offer: RTCSessionDescriptionInit) {
    if (!this.socket) return;

    this.socket.emit('webrtc:offer', { to, offer });
  }

  sendWebRTCAnswer(to: string, answer: RTCSessionDescriptionInit) {
    if (!this.socket) return;

    this.socket.emit('webrtc:answer', { to, answer });
  }

  sendICECandidate(to: string, candidate: RTCIceCandidate) {
    if (!this.socket) return;

    this.socket.emit('webrtc:ice-candidate', { to, candidate });
  }

  on(event: string, callback: (...args: any[]) => void) {
    if (!this.socket) {
      throw new Error('Socket no conectado');
    }

    this.socket.on(event, callback);
  }

  off(event: string, callback?: (...args: any[]) => void) {
    if (!this.socket) return;

    this.socket.off(event, callback);
  }

  getSocket() {
    return this.socket;
  }

  isConnected() {
    return this.socket?.connected || false;
  }
}

export const socketClient = new SocketClient();
export default socketClient;
