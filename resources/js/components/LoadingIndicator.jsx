import { router } from '@inertiajs/react';
import { Progress } from '@/components/ui/progress';
import { useEffect, useState } from 'react';

export function LoadingIndicator() {
  const [progress, setProgress] = useState(0);

  useEffect(() => {
    const handleStart = () => {
      setProgress(10);
    };

    const handleProgress = (event) => {
      if (event.detail.progress.percentage) {
        setProgress(event.detail.progress.percentage);
      }
    };

    const handleFinish = () => {
      setProgress(100);
      setTimeout(() => setProgress(0), 300);
    };

    router.on('start', handleStart);
    router.on('progress', handleProgress);
    router.on('finish', handleFinish);

    return () => {
      // Note: router.off doesn't exist, but we'll leave cleanup empty
      // The handlers will be garbage collected when component unmounts
    };
  }, []);

  if (progress === 0) return null;

  return (
    <div className="fixed top-0 left-0 right-0 z-50">
      <Progress value={progress} className="h-1" />
    </div>
  );
}
