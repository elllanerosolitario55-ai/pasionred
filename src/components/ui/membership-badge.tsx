import { Crown, Award, Medal } from 'lucide-react';
import { cn } from '@/lib/utils';

interface MembershipBadgeProps {
  membershipType: 'FREE' | 'BRONZE' | 'SILVER' | 'GOLD';
  showPriority?: boolean;
  size?: 'sm' | 'md' | 'lg';
  className?: string;
}

export function MembershipBadge({
  membershipType,
  showPriority = false,
  size = 'md',
  className
}: MembershipBadgeProps) {

  const configs = {
    GOLD: {
      label: 'Oro',
      icon: Crown,
      gradient: 'from-yellow-400 via-yellow-500 to-yellow-600',
      textColor: 'text-yellow-900',
      priority: 'Prioridad Máxima',
      priorityIcon: '🥇',
    },
    SILVER: {
      label: 'Plata',
      icon: Award,
      gradient: 'from-gray-300 via-gray-400 to-gray-500',
      textColor: 'text-gray-900',
      priority: 'Prioridad Alta',
      priorityIcon: '🥈',
    },
    BRONZE: {
      label: 'Bronce',
      icon: Medal,
      gradient: 'from-orange-400 via-orange-500 to-orange-600',
      textColor: 'text-white',
      priority: 'Prioridad Media',
      priorityIcon: '🥉',
    },
    FREE: {
      label: 'Gratis',
      icon: null,
      gradient: 'from-slate-400 to-slate-500',
      textColor: 'text-white',
      priority: 'Sin prioridad',
      priorityIcon: '⚪',
    },
  };

  const config = configs[membershipType];
  const Icon = config.icon;

  const sizes = {
    sm: 'px-2 py-1 text-xs',
    md: 'px-3 py-1.5 text-sm',
    lg: 'px-4 py-2 text-base',
  };

  return (
    <div className={cn('inline-flex flex-col gap-1', className)}>
      <div
        className={cn(
          'inline-flex items-center gap-1.5 rounded-full font-semibold',
          'bg-gradient-to-r',
          config.gradient,
          config.textColor,
          sizes[size],
          'shadow-md'
        )}
      >
        {Icon && <Icon className={cn(
          'flex-shrink-0',
          size === 'sm' && 'h-3 w-3',
          size === 'md' && 'h-4 w-4',
          size === 'lg' && 'h-5 w-5'
        )} />}
        <span>{config.label}</span>
      </div>

      {showPriority && membershipType !== 'FREE' && (
        <div className="text-xs text-slate-600 flex items-center gap-1">
          <span>{config.priorityIcon}</span>
          <span>{config.priority}</span>
        </div>
      )}
    </div>
  );
}
