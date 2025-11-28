import { Moon, Sun } from 'lucide-react';
import { useTheme } from 'next-themes';
import { Button } from '@/components/ui/button';
import { useEffect, useState } from 'react';

export function ThemeToggle() {
  const { setTheme, theme } = useTheme();
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
  }, []);

  const toggleTheme = () => {
    setTheme(theme === 'dark' ? 'light' : 'dark');
  };

  // Show the icon that represents what clicking will do
  // If dark mode, show sun (to switch to light)
  // If light mode, show moon (to switch to dark)
  const isDark = theme === 'dark';
  const Icon = isDark ? Sun : Moon;

  if (!mounted) {
    return (
      <Button size="icon" className="h-9 w-9" aria-label="Toggle theme">
        <Sun className="h-4 w-4" />
      </Button>
    );
  }

  return (
    <Button
      size="icon"
      onClick={toggleTheme}
      variant="ghost"
      className="h-9 w-9 cursor-pointer"
      aria-label={isDark ? 'Switch to light mode' : 'Switch to dark mode'}
    >
      <Icon className="h-4 w-4 transition-all" />
    </Button>
  );
}
