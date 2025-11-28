import { useTheme } from 'next-themes';
import { useEffect, useState } from 'react';

export function Logo({ className = 'h-6 w-6', alt = 'Starter' }) {
  const { theme, systemTheme } = useTheme();
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  // Determine which logo to show
  // Dark theme → use light logo (logo-light.png)
  // Light theme → use dark logo (logo-dark.png)
  const getLogo = () => {
    if (!mounted) {
      // Default to dark logo while loading
      return '/logo-dark.png';
    }

    const currentTheme = theme === 'system' ? systemTheme : theme;
    return currentTheme === 'dark' ? '/logo-light.png' : '/logo-dark.png';
  };

  return (
    <img 
      src={getLogo()} 
      alt={alt} 
      className={className}
    />
  );
}

