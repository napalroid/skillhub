import React, { useEffect, useState } from 'react';
import { createRoot } from 'react-dom/client';

const CHARACTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';

function DecryptedGreeting({ text }) {
    const [displayText, setDisplayText] = useState('');
    const [isDecrypting, setIsDecrypting] = useState(true);

    useEffect(() => {
        let frame = 0;
        const maxFrames = 60;
        
        const interval = setInterval(() => {
            if (frame >= maxFrames) {
                setDisplayText(text);
                setIsDecrypting(false);
                clearInterval(interval);
                return;
            }

            const progress = frame / maxFrames;
            const revealedLength = Math.floor(text.length * progress);
            
            let result = '';
            for (let i = 0; i < text.length; i++) {
                if (i < revealedLength) {
                    result += text[i];
                } else if (text[i] === ' ') {
                    result += ' ';
                } else {
                    result += CHARACTERS[Math.floor(Math.random() * CHARACTERS.length)];
                }
            }
            
            setDisplayText(result);
            frame++;
        }, 20);

        return () => clearInterval(interval);
    }, [text]);

    return (
        <div className="font-heading font-extrabold text-lg tracking-tight">
            <span className="text-black">Halo, </span>
            <span className="text-black" style={{ fontFamily: 'monospace', letterSpacing: '0.01em' }}>
                {displayText}
            </span>
        </div>
    );
}

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('greeting-decrypted');
    if (container) {
        const text = container.dataset.text;
        if (text) {
            createRoot(container).render(<DecryptedGreeting text={text} />);
        }
    }
});

export default DecryptedGreeting;
