import type { Metadata } from "next";
import { Inter } from "next/font/google";
import "./globals.css";
import { ClientBody } from "./ClientBody";
import Link from "next/link";
import { Button } from "@/components/ui/button";
import { Video, Heart, Menu, User, LogIn } from "lucide-react";

const inter = Inter({ subsets: ["latin"] });

export const metadata: Metadata = {
  title: "Pasiones Platform - Conecta con Modelos en Tiempo Real",
  description: "Plataforma de videochat y streaming con modelos verificados. Chat en vivo, contenido exclusivo y mucho más.",
  keywords: "videochat, streaming, modelos, contenido exclusivo, chat en vivo, webcam",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="es">
      <ClientBody className={inter.className}>
        {/* Header/Navigation */}
        <header className="sticky top-0 z-50 bg-white border-b shadow-sm">
          <div className="container mx-auto px-4">
            <div className="flex items-center justify-between h-16">
              <Link href="/" className="flex items-center gap-2">
                <div className="w-10 h-10 bg-gradient-to-br from-pink-600 to-blue-300 rounded-lg flex items-center justify-center">
                  <Heart className="h-6 w-6 text-white" fill="currentColor" />
                </div>
                <span className="text-xl font-bold bg-gradient-to-r from-pink-600 to-blue-400 bg-clip-text text-transparent">PASIONES</span>
              </Link>

              <nav className="hidden md:flex items-center gap-6">
                <Link href="/modelos" className="text-slate-600 hover:text-pink-600 font-medium transition-colors">
                  Modelos
                </Link>
                <Link href="/categorias" className="text-slate-600 hover:text-pink-600 font-medium transition-colors">
                  Categorías
                </Link>
                <Link href="/paises" className="text-slate-600 hover:text-pink-600 font-medium transition-colors">
                  Países
                </Link>
                <Link href="/membresias" className="text-slate-600 hover:text-pink-600 font-medium transition-colors">
                  Membresías
                </Link>
              </nav>

              <div className="flex items-center gap-3">
                <Button variant="ghost" size="sm" className="hidden md:flex text-pink-600 hover:text-pink-700 hover:bg-pink-50">
                  <LogIn className="h-4 w-4 mr-2" />
                  Iniciar Sesión
                </Button>
                <Button size="sm" className="bg-gradient-to-r from-pink-600 to-blue-400 hover:from-pink-700 hover:to-blue-500 text-white">
                  <User className="h-4 w-4 mr-2" />
                  Registrarse
                </Button>
                <Button variant="ghost" size="icon" className="md:hidden">
                  <Menu className="h-5 w-5" />
                </Button>
              </div>
            </div>
          </div>
        </header>

        <main>{children}</main>

        <footer className="bg-gradient-to-br from-pink-900 via-pink-800 to-blue-900 text-white">
          <div className="container mx-auto px-4 py-12">
            <div className="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
              <div>
                <h3 className="font-bold text-lg mb-4 text-pink-100">Plataforma</h3>
                <ul className="space-y-2">
                  <li><Link href="/modelos" className="text-pink-200/80 hover:text-white transition-colors">Buscar Modelos</Link></li>
                  <li><Link href="/categorias" className="text-pink-200/80 hover:text-white transition-colors">Categorías</Link></li>
                  <li><Link href="/paises" className="text-pink-200/80 hover:text-white transition-colors">Países</Link></li>
                </ul>
              </div>
              <div>
                <h3 className="font-bold text-lg mb-4 text-pink-100">Modelos</h3>
                <ul className="space-y-2">
                  <li><Link href="/registro-modelo" className="text-pink-200/80 hover:text-white transition-colors">Registrarse</Link></li>
                  <li><Link href="/membresias" className="text-pink-200/80 hover:text-white transition-colors">Membresías</Link></li>
                  <li><Link href="/panel" className="text-pink-200/80 hover:text-white transition-colors">Panel</Link></li>
                </ul>
              </div>
              <div>
                <h3 className="font-bold text-lg mb-4 text-pink-100">Empresa</h3>
                <ul className="space-y-2">
                  <li><Link href="/sobre-nosotros" className="text-pink-200/80 hover:text-white transition-colors">Sobre Nosotros</Link></li>
                  <li><Link href="/blog" className="text-pink-200/80 hover:text-white transition-colors">Blog</Link></li>
                  <li><Link href="/contacto" className="text-pink-200/80 hover:text-white transition-colors">Contacto</Link></li>
                </ul>
              </div>
              <div>
                <h3 className="font-bold text-lg mb-4 text-pink-100">Legal</h3>
                <ul className="space-y-2">
                  <li><Link href="/terminos" className="text-pink-200/80 hover:text-white transition-colors">Términos</Link></li>
                  <li><Link href="/privacidad" className="text-pink-200/80 hover:text-white transition-colors">Privacidad</Link></li>
                  <li><Link href="/cookies" className="text-pink-200/80 hover:text-white transition-colors">Cookies</Link></li>
                </ul>
              </div>
            </div>
            <div className="border-t border-pink-700/30 pt-8 text-center text-pink-100/70 text-sm">
              <p>© 2025 Pasiones Platform. Todos los derechos reservados. +18</p>
            </div>
          </div>
        </footer>
      </ClientBody>
    </html>
  );
}
