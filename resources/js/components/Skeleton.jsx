import React from 'react';

const pulseAnimation = `
@keyframes skeleton-pulse {
  0% { background-position: -200% 0; }
  100% { background-position: 200% 0; }
}
.skeleton-pulse {
  animation: skeleton-pulse 1.5s ease-in-out infinite;
  background: linear-gradient(90deg, #EAEAEA 25%, #DDDDDD 50%, #EAEAEA 75%);
  background-size: 200% 100%;
  border-radius: 8px;
}
`;

export const SkeletonCard = ({ className = '' }) => (
  <>
    <style dangerouslySetInnerHTML={{ __html: pulseAnimation }} />
    <div className={`bg-white border border-[#DDDDDD] rounded-lg p-5 shadow-sm ${className}`}>
      <div className="flex items-start gap-3 mb-4">
        <div className="w-12 h-12 rounded-lg skeleton-pulse shrink-0" />
        <div className="min-w-0 flex-1">
          <div className="h-4 w-3/4 skeleton-pulse mb-2" />
          <div className="h-3 w-1/2 skeleton-pulse" />
        </div>
      </div>
      <div className="space-y-2 mb-4">
        <div className="h-10 px-3 rounded-md skeleton-pulse" />
        <div className="h-10 px-3 rounded-md skeleton-pulse" />
        <div className="h-10 px-3 rounded-md skeleton-pulse" />
      </div>
      <div className="h-8 w-24 skeleton-pulse" />
    </div>
  </>
);

export const SkeletonRow = ({ className = '', columns = 3 }) => (
  <>
    <style dangerouslySetInnerHTML={{ __html: pulseAnimation }} />
    <div className={`grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-${columns} gap-4 ${className}`}>
      {[...Array(6)].map((_, i) => (
        <div key={i} className="bg-white border border-[#DDDDDD] rounded-lg p-5 shadow-sm">
          <div className="flex items-start gap-3 mb-4">
            <div className="w-12 h-12 rounded-lg skeleton-pulse shrink-0" />
            <div className="min-w-0 flex-1">
              <div className="h-4 w-3/4 skeleton-pulse mb-2" />
              <div className="h-3 w-1/2 skeleton-pulse" />
            </div>
          </div>
          <div className="space-y-2 mb-4">
            <div className="h-10 px-3 rounded-md skeleton-pulse" />
            <div className="h-10 px-3 rounded-md skeleton-pulse" />
            <div className="h-10 px-3 rounded-md skeleton-pulse" />
          </div>
          <div className="h-8 w-24 skeleton-pulse" />
        </div>
      ))}
    </div>
  </>
);

export const SkeletonList = ({ className = '', items = 6 }) => (
  <>
    <style dangerouslySetInnerHTML={{ __html: pulseAnimation }} />
    <div className={`space-y-3 ${className}`}>
      {[...Array(items)].map((_, i) => (
        <div key={i} className="bg-white border border-[#DDDDDD] rounded-lg p-4 shadow-sm">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-lg skeleton-pulse shrink-0" />
            <div className="min-w-0 flex-1">
              <div className="h-4 w-1/3 skeleton-pulse mb-1" />
              <div className="h-3 w-1/4 skeleton-pulse" />
            </div>
            <div className="h-6 w-16 skeleton-pulse shrink-0" />
          </div>
        </div>
      ))}
    </div>
  </>
);

export default SkeletonCard;