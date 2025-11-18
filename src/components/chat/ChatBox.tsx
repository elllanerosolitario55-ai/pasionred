'use client';

import { useState, useEffect, useRef } from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Send, Image as ImageIcon, Paperclip } from 'lucide-react';
import { socketClient } from '@/lib/socket/client';

interface Message {
  id: string;
  senderId: string;
  receiverId: string;
  content: string;
  type: 'TEXT' | 'IMAGE' | 'VIDEO' | 'AUDIO';
  mediaUrl?: string;
  isRead: boolean;
  createdAt: Date;
  sender?: {
    id: string;
    name: string;
    image?: string;
  };
}

interface ChatBoxProps {
  userId: string;
  receiverId: string;
  receiverName: string;
  receiverImage?: string;
  initialMessages?: Message[];
}

export function ChatBox({
  userId,
  receiverId,
  receiverName,
  receiverImage,
  initialMessages = [],
}: ChatBoxProps) {
  const [messages, setMessages] = useState<Message[]>(initialMessages);
  const [newMessage, setNewMessage] = useState('');
  const [isTyping, setIsTyping] = useState(false);
  const [isReceiverOnline, setIsReceiverOnline] = useState(false);
  const messagesEndRef = useRef<HTMLDivElement>(null);
  const typingTimeoutRef = useRef<NodeJS.Timeout | null>(null);

  useEffect(() => {
    // Conectar socket
    socketClient.connect(userId);

    // Escuchar mensajes nuevos
    socketClient.on('message:receive', (message: Message) => {
      setMessages((prev) => [...prev, message]);

      // Marcar como leído si el chat está abierto
      socketClient.markAsRead(message.senderId);

      scrollToBottom();
    });

    // Escuchar cuando el mensaje fue enviado
    socketClient.on('message:sent', (message: Message) => {
      setMessages((prev) => [...prev, message]);
      scrollToBottom();
    });

    // Escuchar estado de typing
    socketClient.on('typing:start', (data: { userId: string }) => {
      if (data.userId === receiverId) {
        setIsTyping(true);
      }
    });

    socketClient.on('typing:stop', (data: { userId: string }) => {
      if (data.userId === receiverId) {
        setIsTyping(false);
      }
    });

    // Escuchar estado online/offline
    socketClient.on('user:online', (data: { userId: string; online: boolean }) => {
      if (data.userId === receiverId) {
        setIsReceiverOnline(data.online);
      }
    });

    // Cleanup
    return () => {
      socketClient.off('message:receive');
      socketClient.off('message:sent');
      socketClient.off('typing:start');
      socketClient.off('typing:stop');
      socketClient.off('user:online');
    };
  }, [userId, receiverId]);

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  const handleSendMessage = (e: React.FormEvent) => {
    e.preventDefault();

    if (!newMessage.trim()) return;

    socketClient.sendMessage({
      receiverId,
      content: newMessage.trim(),
      type: 'TEXT',
    });

    setNewMessage('');
    socketClient.stopTyping(receiverId);
  };

  const handleTyping = (e: React.ChangeEvent<HTMLInputElement>) => {
    setNewMessage(e.target.value);

    // Emitir evento de typing
    socketClient.startTyping(receiverId);

    // Detener typing después de 2 segundos de inactividad
    if (typingTimeoutRef.current) {
      clearTimeout(typingTimeoutRef.current);
    }

    typingTimeoutRef.current = setTimeout(() => {
      socketClient.stopTyping(receiverId);
    }, 2000);
  };

  return (
    <Card className="h-[600px] flex flex-col">
      {/* Header */}
      <CardHeader className="border-b">
        <div className="flex items-center gap-3">
          <div className="relative">
            <div className="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-cyan-500 flex items-center justify-center text-white font-bold">
              {receiverName.charAt(0)}
            </div>
            {isReceiverOnline && (
              <div className="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white" />
            )}
          </div>
          <div className="flex-1">
            <CardTitle className="text-lg">{receiverName}</CardTitle>
            <p className="text-sm text-slate-500">
              {isReceiverOnline ? 'En línea' : 'Desconectado'}
            </p>
          </div>
        </div>
      </CardHeader>

      {/* Messages */}
      <CardContent className="flex-1 overflow-y-auto p-4 space-y-4">
        {messages.map((message) => {
          const isOwn = message.senderId === userId;

          return (
            <div
              key={message.id}
              className={`flex ${isOwn ? 'justify-end' : 'justify-start'}`}
            >
              <div
                className={`max-w-[70%] px-4 py-2 rounded-2xl ${
                  isOwn
                    ? 'bg-emerald-600 text-white'
                    : 'bg-slate-100 text-slate-900'
                }`}
              >
                <p className="text-sm">{message.content}</p>
                <p className={`text-xs mt-1 ${isOwn ? 'text-emerald-100' : 'text-slate-500'}`}>
                  {new Date(message.createdAt).toLocaleTimeString('es-ES', {
                    hour: '2-digit',
                    minute: '2-digit',
                  })}
                </p>
              </div>
            </div>
          );
        })}

        {isTyping && (
          <div className="flex justify-start">
            <div className="bg-slate-100 px-4 py-2 rounded-2xl">
              <div className="flex gap-1">
                <span className="w-2 h-2 bg-slate-400 rounded-full animate-bounce" />
                <span className="w-2 h-2 bg-slate-400 rounded-full animate-bounce delay-100" />
                <span className="w-2 h-2 bg-slate-400 rounded-full animate-bounce delay-200" />
              </div>
            </div>
          </div>
        )}

        <div ref={messagesEndRef} />
      </CardContent>

      {/* Input */}
      <div className="border-t p-4">
        <form onSubmit={handleSendMessage} className="flex gap-2">
          <Button type="button" variant="ghost" size="icon">
            <ImageIcon className="h-5 w-5" />
          </Button>
          <Button type="button" variant="ghost" size="icon">
            <Paperclip className="h-5 w-5" />
          </Button>
          <Input
            value={newMessage}
            onChange={handleTyping}
            placeholder="Escribe un mensaje..."
            className="flex-1"
          />
          <Button type="submit" className="bg-emerald-600 hover:bg-emerald-700">
            <Send className="h-5 w-5" />
          </Button>
        </form>
      </div>
    </Card>
  );
}
