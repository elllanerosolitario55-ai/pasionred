"use client";

import Script from "next/script";

export function ClientBody({
  children,
  className,
}: {
  children: React.ReactNode;
  className?: string;
}) {
  return (
    <body suppressHydrationWarning className={`antialiased ${className || ""}`}>
      <Script
        crossOrigin="anonymous"
        src="//unpkg.com/same-runtime/dist/index.global.js"
      />
      {children}
    </body>
  );
}
