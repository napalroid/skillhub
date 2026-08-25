const fs = require('fs'); 
const content = \import React, { useEffect, useState } from 'react'; 
import { createPortal } from 'react-dom'; 
const transitionStyles = \@keyframes pageTransitionIn { from { opacity: 0; transform: scale(1.02); } to { opacity: 1; transform: scale(1); } } @keyframes pageTransitionOut { from { opacity: 1; transform: scale(1); } to { opacity: 0; transform: scale(0.98); } } .page-transition-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.1); backdrop-filter: blur(8px); z-index: 9999; display: flex; align-items: center; justify-content: center; pointer-events: none; } .page-transition-overlay.enter { animation: pageTransitionIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards; } .page-transition-overlay.exit { animation: pageTransitionOut 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards; }\; 
export const PageTransitionOverlay = ({ isVisible, onComplete }) =
  useEffect(() =
    if (!isVisible) return; 
    const timer = setTimeout(() =
      if (onComplete) onComplete(); 
    }, 400); 
    return () =
  }, [isVisible, onComplete]); 
  if (!isVisible) return null; 
  return createPortal( 
    <div className=\" page-transition-overlay "enter\ style={transitionStyles} />, 
