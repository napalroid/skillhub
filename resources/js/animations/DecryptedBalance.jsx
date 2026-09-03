import React, { useCallback, useEffect, useRef, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { motion, useReducedMotion } from 'framer-motion';

const glyphs = '!@#$%^&*()0123456789';

function scramble(value, revealedCharacters) {
    return Array.from(value).map((character, index) => {
        if (character === '.' || character === ',' || index < revealedCharacters) {
            return character;
        }
        return glyphs[Math.floor(Math.random() * glyphs.length)];
    }).join('');
}

function DecryptedBalance({ balance, currency = 'IDR', className = '' }) {
    const reduceMotion = useReducedMotion();
    const [displayedBalance, setDisplayedBalance] = useState('');
    const timerRef = useRef(null);

    const revealBalance = useCallback(() => {
        window.clearInterval(timerRef.current);

        if (reduceMotion) {
            setDisplayedBalance(balance);
            return;
        }

        let frame = 0;
        const totalFrames = Math.max(balance.length * 3, 18);
        const decryptDuration = 700; // 0.7 detik
        const frameInterval = decryptDuration / totalFrames;

        timerRef.current = window.setInterval(() => {
            const revealedCharacters = Math.floor((frame / totalFrames) * balance.length);
            setDisplayedBalance(scramble(balance, revealedCharacters));
            frame += 1;

            if (frame > totalFrames) {
                window.clearInterval(timerRef.current);
                setDisplayedBalance(balance);
            }
        }, frameInterval);
    }, [balance, reduceMotion]);

    useEffect(() => {
        // Auto-reveal saat pertama kali mount
        revealBalance();

        return () => window.clearInterval(timerRef.current);
    }, [revealBalance]);

    return (
        <motion.div
            className={className}
            initial={reduceMotion ? false : { opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.3 }}
            aria-label={`${currency} ${balance}`}
        >
            {currency && <span className="wh-balance-currency">{currency}</span>}
            <span aria-hidden="true" style={{ fontFamily: 'monospace', letterSpacing: '-0.02em' }}>
                {displayedBalance || scramble(balance, 0)}
            </span>
        </motion.div>
    );
}

// Mount all balance components
document.addEventListener('DOMContentLoaded', () => {
    const balanceMounts = document.querySelectorAll('[data-decrypted-balance]');
    
    balanceMounts.forEach(mount => {
        const balance = mount.dataset.balance;
        const currency = mount.dataset.currency || 'IDR';
        const className = mount.className;
        
        if (balance) {
            createRoot(mount).render(
                <DecryptedBalance 
                    balance={balance} 
                    currency={currency}
                    className={className}
                />
            );
        }
    });
});
