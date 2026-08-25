import React, { useCallback, useEffect, useRef, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { motion, useReducedMotion } from 'framer-motion';

const mountPoint = document.getElementById('skillhub-decrypted-greeting');
const glyphs = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*';

function scramble(value, revealedCharacters) {
    return Array.from(value).map((character, index) => {
        if (character === ' ' || index < revealedCharacters) return character;

        return glyphs[Math.floor(Math.random() * glyphs.length)];
    }).join('');
}

function DecryptedGreeting({ name }) {
    const reduceMotion = useReducedMotion();
    const [displayedName, setDisplayedName] = useState(reduceMotion ? name : '');
    const timerRef = useRef(null);

    const revealName = useCallback(() => {
        window.clearInterval(timerRef.current);

        if (reduceMotion) {
            setDisplayedName(name);
            return;
        }

        let frame = 0;
        const totalFrames = Math.max(name.length * 3, 12);

        timerRef.current = window.setInterval(() => {
            const revealedCharacters = Math.floor((frame / totalFrames) * name.length);
            setDisplayedName(scramble(name, revealedCharacters));
            frame += 1;

            if (frame > totalFrames) {
                window.clearInterval(timerRef.current);
                setDisplayedName(name);
            }
        }, 38);
    }, [name, reduceMotion]);

    useEffect(() => {
        revealName();

        return () => window.clearInterval(timerRef.current);
    }, [revealName]);

    return <motion.p
        className="skillhub-decrypted-greeting"
        initial={reduceMotion ? false : { opacity: 0, y: 8 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: .42, ease: [0.16, 1, 0.3, 1] }}
        onMouseEnter={revealName}
        onFocus={revealName}
        tabIndex="0"
        aria-label={`Halo, ${name}`}
    >
        Halo, <span aria-hidden="true">{displayedName}</span>
    </motion.p>;
}

if (mountPoint && mountPoint.dataset.name) {
    createRoot(mountPoint).render(<DecryptedGreeting name={mountPoint.dataset.name} />);
}
