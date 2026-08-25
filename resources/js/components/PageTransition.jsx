import React, { useEffect, useState } from 'react';
import { createPortal } from 'react-dom';

const transitionStyles = `
.page-transition-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  pointer-events: none;
  opacity: 0;
  transition: opacity 300ms ease-out;
}
.page-transition-overlay.enter {
  animation: pageTransitionIn 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
.page-transition-overlay.exit {
  animation: pageTransitionOut 0.25s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
@keyframes pageTransitionIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes pageTransitionOut {
  from { opacity: 1; }
  to { opacity: 0; }
}
.page-content {
  opacity: 0;
  transform: translateY(16px);
  animation: pageContentIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
  animation-delay: 0.08s;
}
@keyframes pageContentIn {
  from { opacity: 0; transform: translateY(16px); }
  to { opacity: 1; transform: translateY(0); }
}
.stagger-enter {
  opacity: 0;
  transform: translateY(12px);
  animation: staggerIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}
@keyframes staggerIn {
  from { opacity: 0; transform: translateY(12px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Respect reduced motion */
@media (prefers-reduced-motion: reduce) {
  .page-transition-overlay,
  .page-content,
  .stagger-enter {
    animation: none !important;
    opacity: 1 !important;
    transform: none !important;
  }
}
`;

export const PageTransition = ({ 
  children, 
  className = '', 
  staggerItems = [],
  delay = 0.08 
}) => {
  const [isMounted, setIsMounted] = useState(false);

  useEffect(() => {
    setIsMounted(true);
    return () => setIsMounted(false);
  }, []);

  if (!isMounted) {
    return null;
  }

  return (
    <>
      <style dangerouslySetInnerHTML={{ __html: transitionStyles }} />
      <div className={`page-content ${className}`}>
        {React.Children.map(children, (child, index) => {
          if (!React.isValidElement(child)) return child;
          
          const isStagger = staggerItems.includes(index) || staggerItems.length === 0;
          return React.cloneElement(child, {
            className: `${child.props.className || ''} ${isStagger ? 'stagger-enter' : ''}`,
            style: {
              ...child.props.style,
              animationDelay: isStagger ? `${delay + index * 0.05}s` : undefined,
            },
          });
        })}
      </div>
    </>
  );
};

export const PageTransitionOverlay = ({ isVisible, onComplete }) => {
  if (!isVisible) return null;

  useEffect(() => {
    const timer = setTimeout(() => {
      if (onComplete) onComplete();
    }, 350);
    return () => clearTimeout(timer);
  }, [isVisible, onComplete]);

  return createPortal(
    <div className="page-transition-overlay enter" />,
    document.body
  );
};

export default PageTransition;